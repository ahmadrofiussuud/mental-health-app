<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Journal;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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

        // Calculate percentages for the view
        $totalMoodsCount = $todayMoods->sum('total');
        $moodPercentages = [];
        
        if ($totalMoodsCount > 0) {
            foreach ($todayMoods as $mood) {
                $moodPercentages[$mood->mood] = ($mood->total / $totalMoodsCount) * 100;
            }
        }

        // 3. Behavior Alerts -> Refined to use Risk Score > 30 OR pattern
        $riskStudents = User::where('role', 'student')
            ->where('risk_score', '>', 30) // Use the new Risk Logic
            ->get();

        // --- NEW: Critical Alerts (Siswa Perlu Perhatian Segera) ---
        // Logic: Students with negative journals in last 3 days
        $negativeKeywords = ['takut', 'benci', 'marah', 'sedih', 'sakit', 'bullied', 'nyindir', 'bunuh diri', 'mati'];
        $criticalStudents = User::where('role', 'student')
            ->whereHas('journals', function($q) use ($negativeKeywords) {
                $q->where('created_at', '>=', now()->subDays(3))
                  ->where(function($subQ) use ($negativeKeywords) {
                      $subQ->whereIn('mood', ['sad', 'angry'])
                           ->orWhere(function($contentQ) use ($negativeKeywords) {
                               foreach($negativeKeywords as $k) {
                                   $contentQ->orWhere('content', 'LIKE', "%$k%");
                               }
                           });
                  });
            })
            ->with(['journals' => function($q) {
                $q->latest()->limit(1);
            }])
            ->limit(4)
            ->get();

        // --- NEW: Mood Trends (Last 7 Days) for Chart ---
        $endDate = now();
        $startDate = now()->subDays(6);
        $moodTrendsData = Journal::whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, mood, COUNT(*) as count')
            ->groupBy('date', 'mood')
            ->orderBy('date')
            ->get();
            
        // Prepare data structure for Chart.js
        $chartData = [
            'labels' => [],
            'datasets' => [
                'happy' => [],
                'calm' => [],
                'neutral' => [],
                'sad' => [],
                'angry' => []
            ]
        ];

        // Fill last 7 days labels
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData['labels'][] = now()->subDays($i)->format('d M');
            
            foreach(['happy', 'calm', 'neutral', 'sad', 'angry'] as $m) {
                $count = $moodTrendsData->where('date', $date)->where('mood', $m)->first()->count ?? 0;
                $chartData['datasets'][$m][] = $count;
            }
        }

        // --- NEW: AI Weekly Summary (Mock Logic for MVP) ---
        $weeklyAiSummary = "Kondisi kelas relatif stabil.";
        $sadCount = array_sum($chartData['datasets']['sad']);
        $angryCount = array_sum($chartData['datasets']['angry']);
        
        if ($criticalStudents->count() > 0) {
            $weeklyAiSummary = "Perhatian diperlukan: " . $criticalStudents->count() . " siswa menunjukkan indikasi stres minggu ini. ";
            if ($angryCount > $sadCount) {
                $weeklyAiSummary .= "Tren emosi dominan mengarah ke iritabilitas/kemarahan.";
            } else {
                $weeklyAiSummary .= "Kecenderungan emosional mengarah pada kesedihan atau kelelahan.";
            }
        } elseif ($sadCount + $angryCount > 10) {
            $weeklyAiSummary = "Tingkat stres kelas sedikit meningkat menjelang akhir pekan. Disarankan untuk mengadakan sesi relaksasi ringan.";
        } else {
            $weeklyAiSummary = "Suasana kelas positif minggu ini. Mayoritas siswa dalam kondisi tenang dan bahagia.";
        }

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
            'moodMap',
            'moodPercentages',
            'criticalStudents', // NEW
            'chartData',      // NEW
            'weeklyAiSummary' // NEW
        ));
    }

    public function journals(Request $request)
    {
        $query = Journal::with(['user'])
            ->whereHas('user', function($q) {
                $q->where('role', 'student');
            })
            ->orderBy('created_at', 'desc');
        
        // Filter by student name/ID
        if ($request->filled('student')) {
            $query->where('user_id', $request->student);
        }
        
        // Filter by mood
        if ($request->filled('mood')) {
            $query->where('mood', $request->mood);
        }
        
        $journals = $query->paginate(20);
        $students = User::where('role', 'student')->orderBy('name')->get();
        
        // Reuse mood map
        $moodMap = [
            'happy' => ['emoji' => '😄', 'label' => 'Happy', 'color' => 'text-yellow-500', 'bg' => 'bg-yellow-50'],
            'calm' => ['emoji' => '😌', 'label' => 'Calm', 'color' => 'text-blue-500', 'bg' => 'bg-blue-50'],
            'neutral' => ['emoji' => '😐', 'label' => 'Neutral', 'color' => 'text-slate-500', 'bg' => 'bg-slate-50'],
            'sad' => ['emoji' => '😢', 'label' => 'Sad', 'color' => 'text-purple-500', 'bg' => 'bg-purple-50'],
            'angry' => ['emoji' => '😠', 'label' => 'Angry', 'color' => 'text-red-500', 'bg' => 'bg-red-50'],
        ];
        
        return view('teacher.journals', compact('journals', 'students', 'moodMap'));
    }

    // --- New Features Methods ---

    public function riskOverview()
    {
        // Calculate risks for all students (Demo purpose: ideally run via Job/Command)
        $students = User::where('role', 'student')->get();
        $count = 0;
        foreach($students as $student) {
            $this->riskAssessmentService->updateRiskProfile($student);
            $count++;
            
            // Add 10-second delay between requests (except for the last one)
            if ($count < $students->count()) {
                Log::info("Waiting 10 seconds before processing next student (processed {$count}/{$students->count()})");
                sleep(10);
            }
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

    public function aiAdvisor($studentId = null)
    {
        $student = null;
        $analysis = null;

        if ($studentId) {
            $student = User::findOrFail($studentId);
            
            // Get recent journals - fetch more for summarization
            $journals = Journal::where('user_id', $student->id)
                ->latest()
                ->take(20) // Increased from 5 to enable summarization
                ->get();

            // Use summarization to save tokens
            $journalContext = $this->chatbotService->summarizeJournals($journals);

            $prompt = "Kamu adalah psikolog sekolah profesional. Analisis siswa berikut:

**Nama Siswa:** {$student->name}
**Skor Risiko:** {$student->risk_score}/100

**Jurnal Terbaru:**
{$journalContext}

**Format Output HTML:**

### Analisis Situasi Azid

Skor risiko 45 direkomendasikan dengan kata kunci \"takut\" dan \"mati\" (meskipun dalam konteks jurnal, mengindikasikan bahwa Azid sedang mengalami tekanan emosional yang signifikan.

**Konteks Psikologis:** Jurnal nya menunjukkan bahwa emosian dan konflik belumterselesaikan. Ketakutan untuk melaporkan, ide percakapan, atau menunjukkan perilaku takut pada sesama akan tantang dengan hati-hati dan penuh empati. Bagaimana kita menyikapi anak merasa terancam dan takut akanmenyikapi perbedaan di antara menunjukkan perilaku tampak tenang tapi memotivasi isu serius (\u003cstrong\u003esinyal bahaya potensial\u003c/strong\u003e yang harus diawasi).

**Prioritas Utama Saat Ini:** Membangun kepercayaan (trust) agar Azid merasa aman untuk berbagi lebih lanjut mengenai intimidasi yang dialaminya.

---

### Rekomendasi Langkah Aksi (Pendekatan Restoratif dan Empati)

**1. Percakapan Pribadi Segera**
- **Apa yang harus Bapak/Ibu Guru katakan:**  
  > \"Azid, saya perhatikan kamu sepertinya punya sesuatu yang mengganggu pikiran. Saya di sini untuk mendengarkan, bukan untuk menghakimi. Apapun yang kamu ceritakan aman bersama saya.\"
  
- **Goal:** Buat space aman untuk Azid membuka diri.

Buat dalam format HTML yang rapi dengan menggunakan class Tailwind CSS untuk styling.";

            $analysis = $this->chatbotService->processMessage($prompt);
        }

        return view('teacher.ai-advisor', compact('student', 'analysis'));
    }

    public function aiAdvisorAnalyze(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'question' => 'required|string|max:500'
        ]);

        $student = User::findOrFail($request->student_id);
        
        // Get recent journals for context
        $journals = Journal::where('user_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $journalText = "";
        foreach ($journals as $j) {
            $journalText .= "- [{$j->created_at->format('Y-m-d')}] Mood: {$j->mood}. \"{$j->content}\"\n";
        }

        $prompt = "Konteks Siswa:
Nama: {$student->name}
Skor Risiko: {$student->risk_score}/100
Risiko Summary: {$student->risk_summary}

Jurnal Terbaru:
{$journalText}

Pertanyaan Guru: {$request->question}

Berikan jawaban praktis dan spesifik dalam Bahasa Indonesia dengan format HTML menggunakan Tailwind CSS.";

        $analysis = $this->chatbotService->processMessage($prompt);

        return redirect()->route('teacher.ai-advisor', ['studentId' => $student->id])
            ->with('analysis', $analysis);
    }

    public function apiUsageStats()
    {
        $today = now()->format('Y-m-d');
        $stats = Cache::get("gemini_tokens_{$today}", [
            'total_requests' => 0,
            'total_tokens' => 0,
            'total_prompt_tokens' => 0,
            'total_completion_tokens' => 0
        ]);
        
        return view('teacher.api-stats', compact('stats'));
    }
}
