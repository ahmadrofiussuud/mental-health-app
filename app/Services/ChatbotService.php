<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Process user message and return AI response using Google Gemini
     * Acting as a "Teacher Copilot" for mental health support.
     */
    public function processMessage(string $message, array $context = []): string
    {
        $apiKey = env('GEMINI_API_KEY');
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

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
                return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Maaf, saya sedang mengalami gangguan. Silakan coba lagi nanti.";
            } else {
                Log::error('Gemini API Error: ' . $response->body());
                return "Maaf, layanan AI sedang sibuk. Kode Error: " . $response->status();
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Exception: ' . $e->getMessage());
            return "Terjadi kesalahan sistem. Mohon hubungi administrator.";
        }
    }

    /**
     * Analyze journal entries to detect conflicts between students.
     */
    public function analyzeJournalConflicts($entries): string
    {
        $apiKey = env('GEMINI_API_KEY');
        // Using specific version gemini-1.5-flash-001/gemini-1.5-flash
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}";

        if ($entries->isEmpty()) {
            return "No recent journal entries to analyze.";
        }

        // Format entries for the prompt
        $context = "";
        foreach ($entries as $entry) {
            $context .= "- User: {$entry->user->name} | Mood: {$entry->mood} | Content: \"{$entry->content}\"\n";
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
