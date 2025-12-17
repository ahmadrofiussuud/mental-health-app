<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;

use App\Services\RiskAssessmentService;

class DashboardController extends Controller
{
    public function __construct(
        protected \App\Services\ChatbotService $chatbotService,
        protected RiskAssessmentService $riskAssessmentService
    ) {}

    public function analyzeConflicts()
    {
        $recentEntries = Journal::with('user')
            ->latest()
            ->take(20) // Analyze last 20 entries
            ->get();

        $analysis = $this->chatbotService->analyzeJournalConflicts($recentEntries);

        return response()->json([
            'success' => true,
            'analysis' => $analysis
        ]);
    }

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

        // 3. Behavior Alerts -> Refined to use Risk Score > 30 OR pattern
        $riskStudents = User::where('role', 'student')
            ->where('risk_score', '>', 30) // Use the new Risk Logic
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

    // --- New Features Methods ---

    public function riskOverview()
    {
        // Calculate risks for all students (Demo purpose: ideally run via Job/Command)
        $students = User::where('role', 'student')->get();
        foreach($students as $student) {
            $this->riskAssessmentService->updateRiskProfile($student);
        }

        $highRiskStudents = User::where('role', 'student')
            ->orderByDesc('risk_score')
            ->get();

        return view('teacher.risk-overview', compact('highRiskStudents'));
    }

    public function showStudent($id)
    {
        $student = User::findOrFail($id);
        
        // Ensure accurate risk profile on view
        $this->riskAssessmentService->updateRiskProfile($student);

        $journals = Journal::where('user_id', $student->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('teacher.student-detail', compact('student', 'journals'));
    }
}
