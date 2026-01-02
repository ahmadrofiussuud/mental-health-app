<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-12">
        <!-- Header -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 border-b border-indigo-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <a href="{{ route('teacher.risk.overview') }}" class="text-indigo-200 hover:text-white text-sm font-medium mb-2 inline-block">
                            ← Back to Risk Overview
                        </a>
                        <h1 class="text-3xl font-bold flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-2xl">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            {{ $student->name }}
                        </h1>
                        <p class="text-indigo-200 mt-1">{{ $student->email }}</p>
                    </div>
                    
                    <!-- Risk Score Badge -->
                    <div class="text-center">
                        <div class="inline-flex flex-col items-center bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <span class="text-5xl font-black">{{ $student->risk_score }}</span>
                            <span class="text-xs font-bold uppercase tracking-wide text-indigo-200">Risk Score</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column: Risk Summary & AI Diagnosis -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- AI Diagnosis Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-6 border-b border-indigo-100">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                AI Psychological Analysis
                            </h2>
                            <p class="text-sm text-slate-600 mt-1">Auto-generated insights based on recent journal patterns</p>
                        </div>
                        
                        <div class="p-6">
                            @if($student->risk_summary)
                                <div class="prose prose-sm max-w-none text-slate-700">
                                    {!! nl2br(e($student->risk_summary)) !!}
                                </div>
                            @else
                                <p class="text-slate-500 italic">No analysis available yet. The system will analyze journal entries automatically.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Journal History -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 bg-slate-50">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Recent Journal Entries
                            </h2>
                            <p class="text-sm text-slate-500 mt-1">Monitor emotional patterns over time</p>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @php
                                $moodMap = [
                                    'happy' => ['emoji' => '😄', 'label' => 'Happy', 'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50', 'border' => 'border-yellow-200'],
                                    'calm' => ['emoji' => '😌', 'label' => 'Calm', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-200'],
                                    'neutral' => ['emoji' => '😐', 'label' => 'Neutral', 'color' => 'text-slate-600', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200'],
                                    'sad' => ['emoji' => '😢', 'label' => 'Sad', 'color' => 'text-purple-600', 'bg' => 'bg-purple-50', 'border' => 'border-purple-200'],
                                    'angry' => ['emoji' => '😠', 'label' => 'Angry', 'color' => 'text-red-600', 'bg' => 'bg-red-50', 'border' => 'border-red-200'],
                                ];
                            @endphp

                            @forelse($journals as $journal)
                                @php
                                    $mood = $moodMap[$journal->mood] ?? $moodMap['neutral'];
                                @endphp
                                <div class="p-6 hover:bg-slate-50 transition">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="{{ $mood['bg'] }} {{ $mood['border'] }} border-2 w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                                                {{ $mood['emoji'] }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="{{ $mood['bg'] }} {{ $mood['color'] }} px-3 py-1 rounded-full text-xs font-bold border {{ $mood['border'] }}">
                                                    {{ strtoupper($mood['label']) }}
                                                </span>
                                                <span class="text-xs text-slate-400">{{ $journal->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                            <p class="text-slate-700 leading-relaxed">{{ $journal->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-12 text-center">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl">📝</div>
                                    <p class="text-slate-500 font-medium">No journal entries yet</p>
                                    <p class="text-slate-400 text-sm">This student hasn't written any journals.</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Pagination -->
                        @if($journals->hasPages())
                            <div class="p-4 bg-slate-50 border-t border-slate-100">
                                {{ $journals->links() }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column: Quick Actions & Stats -->
                <div class="space-y-6">
                    
                    <!-- AI Copilot Card -->
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
                        <h3 class="font-bold text-lg mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            AI Copilot
                        </h3>
                        <p class="text-purple-100 text-sm mb-4">Get personalized intervention strategies and guidance for this student</p>
                        <a href="{{ route('teacher.ai-advisor', $student->id) }}" class="block w-full bg-white hover:bg-purple-50 text-purple-700 font-bold py-3 px-4 rounded-lg transition text-center shadow-md">
                            Open AI Advisor
                        </a>
                    </div>

                    <!-- Risk Level Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-800 mb-4">Risk Level Guidelines</h3>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                <div class="text-sm">
                                    <p class="font-bold text-slate-700">0-30: Stable</p>
                                    <p class="text-slate-500 text-xs">Regular monitoring</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="text-sm">
                                    <p class="font-bold text-slate-700">31-69: Monitor</p>
                                    <p class="text-slate-500 text-xs">Check in weekly</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="text-sm">
                                    <p class="font-bold text-slate-700">70+: Critical</p>
                                    <p class="text-slate-500 text-xs">Immediate action needed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-800 mb-4">Quick Stats</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Total Journals</span>
                                <span class="font-bold text-slate-800">{{ $journals->total() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Risk Score</span>
                                <span class="font-bold text-slate-800">{{ $student->risk_score }}/100</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Last Activity</span>
                                <span class="font-bold text-slate-800 text-xs">
                                    @if($journals->first())
                                        {{ $journals->first()->created_at->diffForHumans() }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
