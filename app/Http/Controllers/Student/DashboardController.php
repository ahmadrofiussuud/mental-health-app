<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Calculate journal streak (consecutive days with journal entries)
        $streak = $this->calculateStreak($userId);
        
        // Count total journal entries
        $totalEntries = Journal::where('user_id', $userId)->count();
        
        // Get latest journal entry for mood tracking
        $latestJournal = Journal::where('user_id', $userId)
            ->latest()
            ->first();
        
        // Daily wisdom quotes array
        $wisdomQuotes = [
            [
                'title' => 'Smile More!',
                'quote' => '"Did you know? Smiling releases endorphins which helps you feel better instantly. Try it now!"',
                'emoji' => '😄'
            ],
            [
                'title' => 'Take a Break!',
                'quote' => '"Rest is not a luxury, it\'s a necessity. Your mind needs breaks to stay sharp and focused."',
                'emoji' => '☕'
            ],
            [
                'title' => 'You Are Enough!',
                'quote' => '"Remember: You don\'t have to be perfect. Progress, not perfection, is what matters."',
                'emoji' => '🌟'
            ],
            [
                'title' => 'Breathe Deep!',
                'quote' => '"Take 5 deep breaths. Breathing exercises can reduce stress and improve your mood instantly."',
                'emoji' => '🧘'
            ],
            [
                'title' => 'Stay Positive!',
                'quote' => '"Every day may not be good, but there is something good in every day. Look for it!"',
                'emoji' => '🌈'
            ],
        ];
        
        // Get random daily wisdom
        $dailyWisdom = $wisdomQuotes[array_rand($wisdomQuotes)];
        
        return view('student.dashboard', compact('streak', 'totalEntries', 'latestJournal', 'dailyWisdom'));
    }
    
    private function calculateStreak($userId)
    {
        $journals = Journal::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($journal) {
                return Carbon::parse($journal->created_at)->format('Y-m-d');
            });
        
        if ($journals->isEmpty()) {
            return 0;
        }
        
        $streak = 0;
        $currentDate = Carbon::today();
        
        while ($journals->has($currentDate->format('Y-m-d'))) {
            $streak++;
            $currentDate->subDay();
        }
        
        // If no entry today, check if there was one yesterday
        if ($streak === 0 && $journals->has(Carbon::yesterday()->format('Y-m-d'))) {
            $currentDate = Carbon::yesterday();
            while ($journals->has($currentDate->format('Y-m-d'))) {
                $streak++;
                $currentDate->subDay();
            }
        }
        
        return $streak;
    }
}
