<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotService
{
    private $availableModels = [
        'gemini-1.5-flash',
        'gemini-1.5-pro',
        'gemini-2.0-flash',
        'gemini-2.0-flash-001',
        'gemini-2.5-flash',
        'gemini-2.5-pro'
    ];

    /**
     * Global rate limiter: Enforce minimum 5-second delay between API calls
     */
    private function enforceGlobalRateLimit()
    {
        $lastCallKey = 'gemini_last_api_call';
        $minDelay = 5; // Minimum 5 seconds between calls
        
        $lastCall = Cache::get($lastCallKey);
        
        if ($lastCall) {
            $elapsed = now()->diffInSeconds($lastCall);
            
            if ($elapsed < $minDelay) {
                $waitTime = $minDelay - $elapsed;
                Log::info("Global rate limit: waiting {$waitTime}s (last call was {$elapsed}s ago)");
                sleep($waitTime);
            }
        }
        
        // Update last call timestamp
        Cache::put($lastCallKey, now(), now()->addMinutes(5));
    }

    /**
     * Validate if model is available
     */
    private function validateModel($modelName): bool
    {
        if (!in_array($modelName, $this->availableModels)) {
            Log::error("Invalid Gemini model attempted: {$modelName}. Valid models: " . implode(', ', $this->availableModels));
            return false;
        }
        return true;
    }

    /**
     * Log token usage for monitoring
     */
    private function logTokenUsage($promptTokens, $completionTokens, $model, $sessionId = null)
    {
        $today = now()->format('Y-m-d');
        $key = "gemini_tokens_{$today}";
        
        $current = Cache::get($key, [
            'date' => $today,
            'total_requests' => 0,
            'total_prompt_tokens' => 0,
            'total_completion_tokens' => 0,
            'total_tokens' => 0,
            'model' => $model
        ]);
        
        $current['total_requests']++;
        $current['total_prompt_tokens'] += $promptTokens;
        $current['total_completion_tokens'] += $completionTokens;
        $current['total_tokens'] += ($promptTokens + $completionTokens);
        
        Cache::put($key, $current, now()->addDays(30));
        
        // Per-session tracking for individual user monitoring
        if ($sessionId) {
            $sessionKey = "gemini_session_{$sessionId}_{$today}";
            $sessionData = Cache::get($sessionKey, [
                'requests' => 0,
                'tokens' => 0
            ]);
            
            $sessionData['requests']++;
            $sessionData['tokens'] += ($promptTokens + $completionTokens);
            
            Cache::put($sessionKey, $sessionData, now()->addDays(7));
            
            Log::info("Session Token Usage", [
                'session_id' => $sessionId,
                'session_requests' => $sessionData['requests'],
                'session_tokens' => $sessionData['tokens']
            ]);
        }
        
        Log::info("Gemini Token Usage", [
            'prompt_tokens' => $promptTokens,
            'completion_tokens' => $completionTokens,
            'total' => $promptTokens + $completionTokens,
            'daily_total' => $current['total_tokens']
        ]);
    }

    /**
     * Summarize journals efficiently to save tokens
     */
    public function summarizeJournals($journals): string
    {
        if ($journals->isEmpty()) {
            return "No journals to summarize.";
        }

        // Only summarize if more than 10 entries
        if ($journals->count() <= 10) {
            return $this->formatJournalsForContext($journals);
        }

        // Split: Recent (full detail) + Old (summarized)
        $recent = $journals->take(5);
        $old = $journals->skip(5);

        $oldText = "";
        foreach ($old as $j) {
            $oldText .= "- [{$j->created_at->format('Y-m-d')}] {$j->mood}: " . 
                        Str::limit($j->content, 50) . "\n";
        }

        $prompt = "Ringkas jurnal berikut dalam 2-3 kalimat, fokus pada pola emosi:

{$oldText}

Output: Ringkasan singkat dalam Bahasa Indonesia.";

        $summary = $this->processMessage($prompt);

        // Combine summary with recent detailed entries
        $context = "**Ringkasan Riwayat Lama:**\n{$summary}\n\n";
        $context .= "**Jurnal Terbaru (Detail):**\n";
        $context .= $this->formatJournalsForContext($recent);

        return $context;
    }

    /**
     * Format journals for AI context
     */
    private function formatJournalsForContext($journals): string
    {
        $text = "";
        foreach ($journals as $j) {
            $text .= "- [{$j->created_at->format('Y-m-d')}] Mood: {$j->mood}. \"{$j->content}\"\n";
        }
        return $text;
    }
    /**
     * Process user message and return AI response using Google Gemini
     * Acting as a "Teacher Copilot" for mental health support.
     */
    public function processMessage(string $message, array $context = []): string
    {
        // Enforce global rate limit (5s minimum between calls)
        $this->enforceGlobalRateLimit();
        
        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-1.5-flash'); // Default to 1.5-flash for speed
        
        // Validate model with detailed logging
        if (!$this->validateModel($model)) {
            return "Konfigurasi model AI tidak valid ('{$model}'). Gunakan gemini-1.5-flash atau gemini-1.5-pro.";
        }
        
        // Validate endpoint format
        $apiUrl = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";
        
        Log::info("API Request initiated", ['model' => $model, 'endpoint' => $apiUrl]);

        // Prepare Context String
        $contextInfo = "";
        if (!empty($context)) {
            $contextInfo = "CURRENT CONTEXT:\n";
            if(isset($context['student_name'])) $contextInfo .= "- Student: {$context['student_name']}\n";
            if(isset($context['risk_score'])) $contextInfo .= "- Risk Score: {$context['risk_score']} (0-100)\n";
            if(isset($context['risk_summary'])) $contextInfo .= "- Risk Factors: {$context['risk_summary']}\n";
            if(isset($context['recent_journals'])) $contextInfo .= "- Recent Journals:\n{$context['recent_journals']}\n";
        }

        // System instruction to define the persona
        $systemPrompt = "You are 'MindCare AI', an empathetic expert assistant for school teachers. 
        Your goal is to help teachers handle student behavior and mental health issues using psychological principles like Restorative Justice and Non-Violent Communication.
        
        Guidelines:
        1.  **Never judge or scold.** Be supportive to the teacher.
        2.  **Provide Context.** Explain *why* a student might be acting out (e.g., hidden stress, trauma).
        3.  **Actionable Scripts.** Give the teacher exact words to say. Example: 'Try saying: I noticed you seem down...'
        4.  **Safety First.** If there is a risk of self-harm or violence, advise immediate professional intervention.
        5.  **Language.** Reply in Indonesian (Bahasa Indonesia) that is professional but warm.
        
        {$contextInfo}
        
        Scenario Analysis:
        User Input: {$message}";

        $maxRetries = 3;
        $attempt = 0;
        
        while ($attempt < $maxRetries) {
            try {
                $response = Http::timeout(30)->post($apiUrl, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $systemPrompt]
                            ]
                        ]
                    ]
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Extract and log token usage with session tracking
                    $usageMetadata = $data['usageMetadata'] ?? null;
                    if ($usageMetadata) {
                        $sessionId = session()->getId() ?? 'unknown';
                        $this->logTokenUsage(
                            $usageMetadata['promptTokenCount'] ?? 0,
                            $usageMetadata['candidatesTokenCount'] ?? 0,
                            $model,
                            $sessionId
                        );
                    }
                    
                    return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti.";
                } 
                
                // Handle rate limiting with EXPONENTIAL BACKOFF + 60s fallback
                if ($response->status() === 429 && $attempt < $maxRetries - 1) {
                    // Exponential backoff: 10s, 20s, then 60s final fallback
                    $waitTime = min(60, pow(2, $attempt + 3)); // 2^3=8s, 2^4=16s, 2^5=32s, cap at 60
                    
                    Log::warning("⚠️ Rate limit 429 detected! Exponential backoff: waiting {$waitTime}s before retry (attempt " . ($attempt + 1) . "/$maxRetries)");
                    Log::info("Retry strategy: Exponential backoff with 60s max");
                    
                    sleep($waitTime);
                    $attempt++;
                    continue;
                }
                
                // Other errors
                Log::error('Gemini API Error: ' . $response->body());
                
                if ($response->status() === 429) {
                    return "⚠️ API mencapai batas request. Silakan tunggu 1 menit dan coba lagi.";
                }
                
                return "Maaf, layanan AI sedang sibuk. Kode Error: " . $response->status();

            } catch (\Exception $e) {
                if ($attempt < $maxRetries - 1) {
                    $attempt++;
                    sleep(pow(2, $attempt));
                    continue;
                }
                
                Log::error('Chatbot Exception: ' . $e->getMessage());
                return "Terjadi kesalahan sistem. Mohon hubungi administrator.";
            }
        }
        
        return "Maaf, sistem sedang sibuk. Silakan coba beberapa saat lagi.";
    }

    /**
     * Analyze journal entries to detect conflicts between students.
     */
    public function analyzeJournalConflicts($entries): string
    {
        $apiKey = env('GEMINI_API_KEY');
        // Using Gemini 2.0 Flash with v1 API
        $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        if ($entries->isEmpty()) {
            return "No recent journal entries to analyze.";
        }

        // Format entries for the prompt with Pre-filtering to save tokens
        $context = "";
        $relevantCount = 0;
        
        $conflictKeywords = ['berantem', 'musuh', 'benci', 'ejek', 'bully', 'pukul', 'tampar', 'nyindir', 'sindir', 'curang', 'bohong', 'khianat', 'fake', 'ancam', 'takut', 'nangis', 'sedih', 'kesel', 'marah'];

        foreach ($entries as $entry) {
            $isNegative = in_array($entry->mood, ['angry', 'sad', 'anxious']);
            $hasKeyword = false;

             // If not explicitly negative mood, check content for hidden conflicts
            if (!$isNegative) {
                foreach ($conflictKeywords as $keyword) {
                    if (str_contains(strtolower($entry->content), $keyword)) {
                        $hasKeyword = true;
                        break;
                    }
                }
            }

            // Only include if negative mood OR contains conflict keyword
            if ($isNegative || $hasKeyword) {
                 $context .= "- User: {$entry->user->name} | Mood: {$entry->mood} | Content: \"{$entry->content}\"\n";
                 $relevantCount++;
            }
        }

        if ($relevantCount == 0) {
            return "<p>No potential conflict-related content found in recent entries (scanned " . $entries->count() . " entries).</p>";
        }

        $systemPrompt = "You are a School Conflict Detective. 
        Your task is to analyze the following student journal entries and detect if any students are in conflict with each other or referencing the same negative event.
        
        Journal Entries:
        {$context}

        TASK:
        1. Identify any potential conflicts (e.g., Student A complaining about bullying, Student B admitting to teasing or complaining about Student A).
        2. If a conflict is found, name the students involved.
        3. Suggest a quick resolution strategy for the teacher.

        OUTPUT FORMAT (Return HTML):
        - If NO conflict: output <p>No specific interpersonal conflicts detected in the recent feed.</p>
        - If Conflict Detected:
          <div class='bg-red-50 p-4 rounded-xl border border-red-200'>
             <h4 class='font-bold text-red-700 mb-2'>⚠️ Conflict Detected: [Student Name] vs [Student Name]</h4>
             <p class='text-sm text-red-600 mb-3'><strong>Evidence:</strong> [Quote brief reasons]</p>
             <div class='bg-white p-3 rounded-lg border border-red-100'>
                <p class='text-xs font-bold text-slate-500 uppercase'>Suggested Action:</p>
                <p class='text-sm text-slate-700'>[Actionable Advice]</p>
             </div>
          </div>
        ";

        try {
            $response = Http::post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Analysis failed.";
            } else {
                Log::error('Gemini Analysis Error: ' . $response->body());
                return "AI Analysis Service Unavailable.";
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Analysis Exception: ' . $e->getMessage());
            return "System Error during analysis.";
        }
    }
}
