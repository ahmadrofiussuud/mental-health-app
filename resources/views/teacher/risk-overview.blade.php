<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-12">
        <div class="bg-red-50 border-b border-red-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-2xl font-bold text-red-800 flex items-center gap-2">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Emotional Risk Overview
                </h1>
                <p class="text-red-600 mt-2">
                    Screening result based on recent journal analysis. <br>
                    <span class="font-bold">Note:</span> This is an automated support tool, not a clinical diagnosis.
                </p>
                <div class="mt-4">
                     <a href="{{ route('teacher.dashboard') }}" class="text-sm font-bold text-red-700 hover:underline">← Back to Dashboard</a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-4">
                @forelse($highRiskStudents as $student)
                    @php
                        $score = $student->risk_score;
                        // Color coding
                        if($score >= 70) {
                            $borderColor = 'border-red-500';
                            $bgBadge = 'bg-red-100 text-red-800';
                            $barColor = 'bg-red-500';
                            $glow = 'shadow-red-100';
                        } elseif($score >= 31) {
                             $borderColor = 'border-yellow-400';
                             $bgBadge = 'bg-yellow-100 text-yellow-800';
                             $barColor = 'bg-yellow-500';
                             $glow = 'shadow-yellow-100';
                        } else {
                            $borderColor = 'border-green-300';
                            $bgBadge = 'bg-green-100 text-green-800';
                            $barColor = 'bg-green-500';
                            $glow = 'shadow-green-100';
                        }
                    @endphp

                    <div class="bg-white rounded-xl shadow-sm border-l-8 {{ $borderColor }} p-6 flex flex-col md:flex-row gap-6 items-start md:items-center hover:shadow-md transition-all">
                        
                        <!-- Score Circle -->
                        <div class="flex-shrink-0 flex flex-col items-center justify-center w-20">
                            <div class="w-16 h-16 rounded-full border-4 {{ str_replace('bg-', 'border-', $barColor) }} flex items-center justify-center text-xl font-black text-slate-700">
                                {{ $score }}
                            </div>
                            <span class="text-xs font-bold text-slate-400 mt-1">RISK SCORE</span>
                        </div>

                        <!-- Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-xl font-bold text-slate-800">{{ $student->name }}</h3>
                                <span class="px-2 py-0.5 rounded textxs font-bold {{ $bgBadge }}">
                                    @if($score >= 70) CRITICAL @elseif($score >= 31) MONITOR @else STABLE @endif
                                </span>
                            </div>
                            <p class="text-slate-500 text-sm mb-3">Email: {{ $student->email }}</p>
                            
                            <div class="bg-slate-50 p-3 rounded-lg border border-slate-100 text-sm text-slate-600">
                                <strong>System Analysis:</strong> <br>
                                {!! nl2br(e($student->risk_summary)) !!}
                            </div>
                        </div>

                        <!-- Action -->
                        <div>
                            <a href="{{ route('teacher.student.show', $student->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-lg font-bold transition shadow-lg shadow-indigo-200">
                                View Detail & Copilot 
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white rounded-xl border border-dashed border-slate-300">
                        <p class="text-slate-500">No students found. Run analysis first.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
