<x-app-layout>
    @section('title', 'Log Jurnal Siswa')

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Log Jurnal Siswa</h1>
                <p class="text-lg text-gray-600">Kelola dan pantau semua jurnal siswa dengan filter pencarian</p>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
                <form method="GET" action="{{ route('teacher.journals') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Student Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Filter by Student
                        </label>
                        <select name="student" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Mood Filter -->
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Filter by Mood
                        </label>
                        <select name="mood" class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Moods</option>
                            <option value="happy" {{ request('mood') == 'happy' ? 'selected' : '' }}>😊 Happy</option>
                            <option value="calm" {{ request('mood') == 'calm' ? 'selected' : '' }}>😌 Calm</option>
                            <option value="neutral" {{ request('mood') == 'neutral' ? 'selected' : '' }}>😐 Neutral</option>
                            <option value="sad" {{ request('mood') == 'sad' ? 'selected' : '' }}>😢 Sad</option>
                            <option value="angry" {{ request('mood') == 'angry' ? 'selected' : '' }}>😠 Angry</option>
                        </select>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition-all duration-300 shadow-lg hover:shadow-xl flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Apply Filters
                        </button>
                        <a href="{{ route('teacher.journals') }}" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition-all duration-300 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Summary -->
            <div class="mb-6 flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing <strong>{{ $journals->count() }}</strong> of <strong>{{ $journals->total() }}</strong> journal entries
                    @if(request('student') || request('mood'))
                        <span class="text-indigo-600 font-semibold">(Filtered)</span>
                    @endif
                </p>
                @if($journals->hasPages())
                    <p class="text-sm text-gray-500">Page {{ $journals->currentPage() }} of {{ $journals->lastPage() }}</p>
                @endif
            </div>

            <!-- Journal Cards -->
            <div class="space-y-4">
                @forelse($journals as $journal)
                    @php
                         $moodData = $moodMap[$journal->mood] ?? $moodMap['neutral'];
                         $suggestion = "Pantau rutin";
                         $isStress = false;
                         $cardClass = "bg-white";
                         
                         $negativeKeywords = ['nyindir', 'ganggu', 'ejek', 'nangis', 'sedih', 'marah', 'benci', 'sakit', 'bullied', 'takut'];
                         foreach($negativeKeywords as $k) {
                             if(str_contains(strtolower($journal->content), $k)) {
                                 $suggestion = "Terdeteksi Stress";
                                 $isStress = true;
                                 $cardClass = "bg-red-50/40";
                                 break;
                             }
                         }
                    @endphp

                    <div class="{{ $cardClass }} border border-gray-100 rounded-2xl p-6 hover:shadow-xl hover:scale-[1.01] transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-slate-200 to-slate-300 rounded-full flex items-center justify-center flex-shrink-0 shadow-sm">
                                <span class="text-base font-bold text-slate-700">{{ substr($journal->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $journal->user->name }}</h4>
                                    <span class="text-sm text-gray-500">{{ $journal->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <!-- Enhanced Mood Badge -->
                                <div class="mb-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium {{ $moodData['bg'] }} {{ $moodData['color'] }} border {{ str_replace('bg-', 'border-', $moodData['bg']) }}">
                                        <span class="text-base">{{ $moodData['emoji'] }}</span>
                                        {{ strtoupper($moodData['label']) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 mb-4 leading-relaxed">"{{ $journal->content }}"</p>
                                
                                <!-- Visual Color-Coded AI Badge -->
                                @if($isStress)
                                    <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 border-2 border-red-200 rounded-xl shadow-sm">
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                        </svg>
                                        <span class="text-sm font-bold text-red-700">AI Insight: {{ $suggestion }}</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-50 border-2 border-green-200 rounded-xl shadow-sm">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                        <span class="text-sm font-bold text-green-700">AI Insight: {{ $suggestion }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-gray-100 rounded-2xl p-16 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-lg font-semibold text-gray-900">No Journals Found</h3>
                        <p class="mt-1 text-base text-gray-500">
                            @if(request('student') || request('mood'))
                                Try adjusting your filters to see more results
                            @else
                                Journal entries will appear here once students start journaling
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($journals->hasPages())
                <div class="mt-8">
                    {{ $journals->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
