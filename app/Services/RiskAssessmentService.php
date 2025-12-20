<?php

namespace App\Services;

use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RiskAssessmentService
{
    /**
     * Update the Risk Profile for a specific student.
     * Combines Heuristic Scoring (Keywords/Trends) with AI Analysis.
     */
    public function updateRiskProfile(User $student)
    {
        // 1. Fetch recent journals (last 7 days)
        $recentJournals = Journal::where('user_id', $student->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->get();

        if ($recentJournals->isEmpty()) {
            $student->update([
                'risk_score' => 0,
                'risk_summary' => "No recent activity to analyze."
            ]);
            return;
        }

        // 2. Heuristic Scoring (Local Logic)
        $score = 0;
        $heuristicFlags = [];

        // Check for specific negative keywords
        $criticalKeywords = ['mati', 'bunuh diri', 'sakit hati', 'benci hidup', 'lukai', 'darah'];
        $warningKeywords = ['sedih', 'marah', 'capek', 'bingung', 'takut', 'cemas'];
        
        $consecutiveNegativeMoods = 0;
        
        foreach ($recentJournals as $journal) {
            $content = strtolower($journal->content);
            
            // Critical Keyword Check
            foreach ($criticalKeywords as $word) {
                if (str_contains($content, $word)) {
                    $score += 30; // High impact
                    $heuristicFlags[] = "Critical keyword found: '{$word}'";
                    break; // Count once per journal to avoid double counting
                }
            }

            // Warning Keyword Check
            foreach ($warningKeywords as $word) {
                if (str_contains($content, $word)) {
                    $score += 10;
                    $heuristicFlags[] = "Warning keyword found: '{$word}'";
                    break; 
                }
            }

            // Mood Streak Check (Sad/Angry)
            if (in_array($journal->mood, ['sad', 'angry'])) {
                $consecutiveNegativeMoods++;
            } else {
                $consecutiveNegativeMoods = 0; // Reset streak
            }
        }

        // Add bonus score for streaks
        if ($consecutiveNegativeMoods >= 3) {
            $score += 20;
            $heuristicFlags[] = "3+ consecutive negative mood entries";
        }

        // Cap heuristic score at 60 before AI verification (unless critical)
        if ($score > 60) $score = 60;


        // 3. AI Analysis (Gemini) for Context & Nuance
        // Only call AI if there's some indication of negativity to save tokens, 
        // OR if the score is already elevated.
        $aiAnalysis = "AI Analysis not required.";
        
        if ($score > 10 || $recentJournals->count() > 0) {
           $aiResult = $this->analyzeWithAI($recentJournals);
           
           if ($aiResult) {
               $score += $aiResult['score_adjustment'];
               $aiAnalysis = $aiResult['summary'];
           }
        }

        // Final Cap logic
        $finalScore = min(100, max(0, $score));

        // 4. Update User
        $summary = "Base Score: " . ($finalScore - ($aiResult['score_adjustment'] ?? 0)) . ". \n";
        $summary .= "Flags: " . implode(", ", array_unique($heuristicFlags)) . ". \n";
        $summary .= "AI Insight: " . $aiAnalysis;

        $student->update([
            'risk_score' => $finalScore,
            'risk_summary' => $summary
        ]);
    }

    private function analyzeWithAI($journals)
    {
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        $journalText = "";
        foreach ($journals as $j) {
            $journalText .= "- [{$j->created_at->format('Y-m-d')}] Mood: {$j->mood}. Content: \"{$j->content}\"\n";
        }

        $systemPrompt = "You are a School Psychologist Risk Assessor. 
        Analyze these student journal entries for signs of Severe Emotional Distress or Self-Harm Risk.
        
        Journals:
        {$journalText}

        Output JSON ONLY:
        {
            \"score_adjustment\": (int between -10 and 40. Use positive for increased risk, negative if the context is actually positive/coping well),
            \"summary\": \"One sentence explaining the risk factor.\"
        }";

        try {
            $response = Http::post($apiUrl, [
                'contents' => [['parts' => [['text' => $systemPrompt]]]]
            ]);

            if ($response->successful()) {
                $text = $response->json()['candidates'][0]['content']['parts'][0]['text'];
                // Clean markdown code fence if present
                $text = str_replace(["```json", "```"], "", $text);
                return json_decode($text, true);
            }
        } catch (\Exception $e) {
            Log::error("Risk AI Error: " . $e->getMessage());
        }

        return null;
    }
}
