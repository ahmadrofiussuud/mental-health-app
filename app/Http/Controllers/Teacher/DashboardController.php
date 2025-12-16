<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Students
        $totalStudents = User::where('role', 'student')->count();

        // 2. Class Mood Average (Today)
        $todayMoods = Journal::whereDate('created_at', now()->today())
            ->select('mood', DB::raw('count(*) as total'))
            ->groupBy('mood')
            ->orderByDesc('total')
            ->get();

        $dominantMood = $todayMoods->first() ? $todayMoods->first()->mood : 'neutral';
        
        // Map mood to emoji/label
        $moodMap = [
            'happy' => ['emoji' => '😄', 'label' => 'Happy', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
            'calm' => ['emoji' => '😌', 'label' => 'Calm', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
            'neutral' => ['emoji' => '😐', 'label' => 'Neutral', 'color' => 'text-slate-500', 'bg' => 'bg-slate-50'],
            'sad' => ['emoji' => '😢', 'label' => 'Sad', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
            'angry' => ['emoji' => '😠', 'label' => 'Angry', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
        ];

        $currentMoodStats = $moodMap[$dominantMood] ?? $moodMap['neutral'];

        // 3. Behavior Alerts (Logic: Students with 'sad' or 'angry' mood in last 3 days)
        $riskStudents = Journal::whereIn('mood', ['sad', 'angry'])
            ->where('created_at', '>=', now()->subDays(3))
            ->with('user')
            ->select('user_id', DB::raw('count(*) as risk_count'))
            ->groupBy('user_id')
            ->having('risk_count', '>=', 1) // Flag if even 1 negative entry
            ->get();

        // 4. Recent Activities (Journal Entries)
        $recentActivities = Journal::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('teacher.dashboard', compact(
            'totalStudents', 
            'currentMoodStats', 
            'todayMoods',
            'riskStudents', 
            'recentActivities',
            'moodMap'
        ));
    }
}
