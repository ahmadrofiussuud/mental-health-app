<x-app-layout>
    <div class="min-h-screen bg-slate-50 pb-12">
        <!-- Header Section -->
        <div class="bg-white border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                            <span class="text-3xl">👨‍🏫</span> Teacher Dashboard
                        </h1>
                        <p class="text-slate-500 text-sm mt-1">Monitor your students' well-being and provide timely support.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-full text-sm font-semibold border border-indigo-100">
                            {{ $totalStudents }} Students Active
                        </span>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-full text-sm font-bold shadow-md shadow-indigo-200 transition-all active:scale-95">
                            + Broadcast Message
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
            
            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- 1. Behavior Alerts -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all cursor-pointer">
                    <!-- Decorative Background Icon -->
                    <div class="absolute -right-6 -top-6 p-4 opacity-5 group-hover:opacity-10 transition-opacity z-0 pointer-events-none transform rotate-12">
                        <svg class="w-32 h-32 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"></path></svg>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-red-50 text-red-600 p-3 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            @if($riskStudents->count() > 0)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse shadow-sm shadow-red-200">ACTION NEEDED</span>
                            @endif
                        </div>
                        <h3 class="text-slate-500 font-medium text-sm uppercase tracking-wide">Behavior Alerts</h3>
                        <div class="flex items-end gap-2 mt-1">
                            <span class="text-4xl font-black text-slate-800">{{ $riskStudents->count() }}</span>
                            <span class="text-sm text-slate-400 mb-1">Students flagged</span>
                        </div>
                        <p class="text-xs text-red-500 mt-2 font-medium">Based on negative mood patterns</p>
                    </div>
                </div>

                <!-- 2. Class Mood -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all">
                    <!-- Decorative Background Icon -->
                    <div class="absolute -right-4 -top-4 p-4 opacity-5 group-hover:opacity-10 transition-opacity z-0 pointer-events-none transform -rotate-12">
                        <svg class="w-32 h-32 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"></path></svg>
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="{{ $currentMoodStats['bg'] }} {{ $currentMoodStats['color'] }} p-3 rounded-xl shadow-sm">
                                <span class="text-2xl">{{ $currentMoodStats['emoji'] }}</span>
                            </div>
                        </div>
                        <h3 class="text-slate-500 font-medium text-sm uppercase tracking-wide">Class Mood Today</h3>
                        <div class="flex items-end gap-2 mt-1">
                            <span class="text-2xl font-black text-slate-800">{{ $currentMoodStats['label'] }}</span>
                            <span class="text-sm text-slate-400 mb-1">Dominant</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">from {{ $todayMoods->sum('total') }} daily check-ins</p>
                    </div>
                </div>

                 <!-- 3. Quick Tips -->
                 <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 shadow-md text-white relative overflow-hidden">
                    <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-white/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-2 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Teacher Tip
                        </h3>
                        <p class="text-indigo-100 text-sm leading-relaxed mb-4">
                            "Acknowledging students' feelings before correcting behavior can reduce resistance and build trust."
                        </p>
                        <button class="bg-white/20 hover:bg-white/30 text-white text-xs font-bold py-1.5 px-3 rounded-lg transition-colors backdrop-blur-sm shadow-sm">
                            Refresh Tip
                        </button>
                    </div>
                </div>
            </div>

            <!-- Student Insights Section -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div>
                        <h2 class="font-bold text-lg text-slate-800">Student Insights Feed</h2>
                        <p class="text-slate-500 text-xs">Real-time updates from student journals and mood checks.</p>
                    </div>
                    <button class="text-indigo-600 hover:text-indigo-700 text-sm font-bold flex items-center gap-1">
                        View All History <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </button>
                </div>

                <!-- Feed List -->
                <div class="divide-y divide-slate-100">
                    @forelse($recentActivities as $activity)
                        @php
                            $moodData = $moodMap[$activity->mood] ?? $moodMap['neutral'];
                            
                            // Simple "AI" Psychology Suggestion logic
                            $suggestion = "Keep monitoring. Encourage positive habits.";
                            $suggestionColor = "text-slate-500";
                            
                            if($activity->mood == 'sad') {
                                $suggestion = "Student seems down. A gentle 'How are you?' might help.";
                                $suggestionColor = "text-blue-600";
                            } elseif($activity->mood == 'angry') {
                                $suggestion = "High stress detected. Suggest a cooling-off activity.";
                                $suggestionColor = "text-red-600";
                            } elseif($activity->mood == 'calm' || $activity->mood == 'happy') {
                                $suggestion = "Positive state. Great time for engagement!";
                                $suggestionColor = "text-green-600";
                            }
                        @endphp
                        
                        <div class="p-6 hover:bg-indigo-50/30 transition-colors group">
                            <div class="flex items-start gap-4">
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-lg shadow-inner">
                                    {{ substr($activity->user->name, 0, 1) }}
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-bold text-slate-800">{{ $activity->user->name }}</h4>
                                        <span class="text-xs text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <!-- Mood & Content -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="{{ $moodData['bg'] }} {{ $moodData['color'] }} px-2 py-0.5 rounded text-xs font-bold border border-current opacity-75 flex items-center gap-1">
                                            {{ $moodData['emoji'] }} {{ strtoupper($moodData['label']) }}
                                        </span>
                                    </div>
                                    
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-600 text-sm italic mb-3">
                                        "{{ Str::limit($activity->content, 120) }}"
                                    </div>

                                    <!-- AI Insight Box -->
                                    <div class="flex items-start gap-2 bg-indigo-50/50 p-2 rounded-lg border border-indigo-100">
                                        <svg class="w-4 h-4 text-indigo-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <div>
                                            <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-wide block">Psychology Insight</span>
                                            <p class="text-xs font-medium {{ $suggestionColor }}">{{ $suggestion }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl text-slate-400">📭</div>
                            <p class="text-slate-500 font-medium">No student activity yet.</p>
                            <p class="text-slate-400 text-sm">When students check in, they will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
