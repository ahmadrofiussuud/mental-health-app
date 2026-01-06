<x-app-layout>
    @section('title', 'Dashboard Guru')

    <!-- Compact Professional Hero Section -->
    <div class="relative min-h-[60vh] flex items-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/teacher-hero.png') }}" alt="Teacher Dashboard" class="w-full h-full object-cover object-center">
            <!-- Bottom-to-top gradient for better text visibility -->
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/95 via-slate-900/85 to-slate-900/40"></div>
        </div>
        
        <!-- Hero Content - Left-aligned, 50% width -->
        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-8 py-16">
            <div class="max-w-2xl">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-500/20 border-2 border-teal-400/40 rounded-full mb-6 backdrop-blur-md">
                    <span class="w-2 h-2 bg-teal-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-teal-300 uppercase tracking-wider">Portal Guru BK</span>
                </div>
                
                <!-- Main Heading - Compact & Dashboard-appropriate -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    Berdayakan Kelas Anda,<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-teal-400 to-emerald-400">Bentuk Pemimpin Masa Depan</span>
                </h1>
                
                <!-- Description - Compact -->
                <p class="text-lg md:text-xl text-slate-100 mb-10 leading-relaxed font-normal">
                    Selamat datang di pusat kontrol kelas. Pantau kesejahteraan <strong class="text-teal-300 font-semibold">{{ $totalStudents }} siswa</strong> dengan wawasan berbasis AI.
                </p>

                <!-- CTA Buttons - Compact -->
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    <button onclick="document.getElementById('feed-section').scrollIntoView({behavior: 'smooth'})" 
                            class="group px-6 py-3 bg-teal-500 hover:bg-teal-400 text-white font-semibold text-base rounded-xl shadow-lg shadow-teal-500/30 transition-all duration-300 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span>Lihat Aktivitas Siswa</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <a href="{{ route('teacher.risk.overview') }}" 
                       class="px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-semibold text-base rounded-xl border-2 border-white/30 backdrop-blur-md transition-all duration-300 flex items-center justify-center hover:border-white/50">
                        Analisis Risiko
                    </a>
                </div>

                <!-- Premium Glassmorphism Stats Cards -->
                <div class="flex flex-wrap items-stretch gap-4">
                    <div class="backdrop-blur-md bg-white/10 px-8 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 min-w-[160px]">
                        <p class="text-4xl font-bold text-white mb-1">{{ $riskStudents->count() }}</p>
                        <p class="text-sm text-slate-200 font-medium uppercase tracking-wide">Siswa Berisiko</p>
                    </div>
                    <div class="backdrop-blur-md bg-white/10 px-8 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 min-w-[160px]">
                        <p class="text-4xl font-bold text-white flex items-center gap-2 mb-1">
                            <span class="text-3xl">{{ $currentMoodStats['emoji'] }}</span>
                            <span>{{ $currentMoodStats['label'] }}</span>
                        </p>
                        <p class="text-sm text-slate-200 font-medium uppercase tracking-wide">Mood Dominan</p>
                    </div>
                    <div class="backdrop-blur-md bg-white/10 px-8 py-5 rounded-3xl border-2 border-white/20 shadow-lg shadow-black/20 min-w-[160px]">
                        <p class="text-4xl font-bold text-white mb-1">{{ $totalStudents }}</p>
                        <p class="text-sm text-slate-200 font-medium uppercase tracking-wide">Total Siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider for Seamless Transition -->
    <div class="relative -mt-1">
        <svg viewBox="0 0 1440 120" class="w-full h-24" preserveAspectRatio="none" fill="none">
            <path d="M0,64 C240,90 480,90 720,64 C960,38 1200,38 1440,64 L1440,120 L0,120 Z" fill="#F9FAFB"/>
        </svg>
    </div>

    <!-- Summary View (Bento Box Layout) -->
    <div class="relative z-20 max-w-7xl mx-auto px-6 lg:px-8 -mt-12 mb-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Section 1: Siswa Perlu Perhatian Segera (Critical Alerts) -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            Perlu Perhatian Segera
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Siswa dengan indikasi stres atau emosi negatif tinggi</p>
                    </div>
                    <a href="{{ route('teacher.journals', ['mood' => 'sad']) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($criticalStudents as $student)
                        <div class="flex items-start gap-3 p-4 rounded-2xl bg-red-50/50 border border-red-100 hover:shadow-md transition-all duration-300 cursor-pointer group">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center text-red-600 font-bold text-sm">
                                    {{ substr($student->name, 0, 1) }}
                                </div>
                                <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                    <span class="block w-3 h-3 bg-red-500 rounded-full border-2 border-white"></span>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-red-700 transition-colors">{{ $student->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded textxs font-medium bg-red-100 text-red-700">
                                        {{ $student->journals->first()->mood ?? 'Stress' }}
                                    </span>
                                    <span class="text-xs text-gray-400">{{ $student->journals->first()->created_at->diffForHumans() ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 flex flex-col items-center justify-center py-8 text-center text-gray-500">
                             <svg class="w-12 h-12 text-green-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-medium">Tidak ada peringatan kritis saat ini.</p>
                            <p class="text-xs">Kelas dalam kondisi baik.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section 3: Ringkasan Mingguan AI (AI Summary) -->
            <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-3xl p-6 shadow-xl shadow-indigo-200 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-32 h-32 bg-black/10 rounded-full blur-2xl"></div>
                
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Ringkasan AI Minggu Ini
                </h3>
                
                <div class="prose prose-sm prose-invert relative z-10">
                    <p class="text-indigo-100 leading-relaxed text-sm">
                        "{{ $weeklyAiSummary }}"
                    </p>
                </div>
                
                <div class="mt-6 pt-4 border-t border-white/20 relative z-10">
                    <div class="flex items-center justify-between text-xs text-indigo-200">
                        <span>Update: {{ now()->format('d M H:i') }}</span>
                        <span class="flex items-center gap-1">
                            Powered by Gemini
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-1.07 3.97-2.9 5.4z"/></svg>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Statistik Mood Kelas (Chart) -->
            <div class="lg:col-span-3 bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Analisis Mood 7 Hari Terakhir</h3>
                        <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-teal-400"></span> Happy/Calm</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-orange-300"></span> Sad</span>
                            <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-400"></span> Angry</span>
                        </div>
                    </div>
                </div>
                <div class="w-full h-64">
                    <canvas id="moodTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>


    <!-- Journal Preview Section -->
    <div id="feed-section" class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Jurnal Terbaru</h2>
                    <p class="text-gray-600 mt-2">5 jurnal terbaru dari siswa</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('teacher.journals') }}" 
                       class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Lihat Semua Jurnal
                    </a>
                    <button onclick="analyzeFeed()" id="btn-analyze" 
                            class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center gap-2 hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Generate Analisis AI
                    </button>
                </div>
            </div>

            <!-- AI Result -->
            <div id="conflict-analysis-result" class="hidden mb-8 p-6 bg-indigo-50 border-2 border-indigo-200 rounded-2xl text-gray-700"></div>

            <!-- Modern Activity List - Limited to 5 -->
            <div class="space-y-4">
                @forelse($recentActivities->take(5) as $activity)
                    @php
                         $moodData = $moodMap[$activity->mood] ?? $moodMap['neutral'];
                         $suggestion = "Pantau rutin";
                         $isStress = false;
                         $cardClass = "bg-white";
                         
                         $negativeKeywords = ['nyindir', 'ganggu', 'ejek', 'nangis', 'sedih', 'marah', 'benci', 'sakit', 'bullied', 'takut'];
                         foreach($negativeKeywords as $k) {
                             if(str_contains(strtolower($activity->content), $k)) {
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
                                <span class="text-base font-bold text-slate-700">{{ substr($activity->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $activity->user->name }}</h4>
                                    <span class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <!-- Enhanced Mood Badge -->
                                <div class="mb-4">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium {{ $moodData['bg'] }} {{ $moodData['color'] }} border {{ str_replace('bg-', 'border-', $moodData['bg']) }}">
                                        <span class="text-base">{{ $moodData['emoji'] }}</span>
                                        {{ strtoupper($moodData['label']) }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-700 mb-4 leading-relaxed">"{{ $activity->content }}"</p>
                                
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
                        <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum ada aktivitas</h3>
                        <p class="mt-2 text-base text-gray-500">Aktivitas siswa akan muncul di sini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Floating Chatbot -->
    <div id="mindcare-chatbot" class="fixed bottom-6 right-6 z-50">
         <button onclick="toggleChatWindow()" 
                class="w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-white bg-slate-900 hover:bg-slate-800 transition">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
        </button>
        
        <div id="chat-window-container" class="hidden absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden flex flex-col h-[500px]">
            <div class="bg-slate-900 p-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                    <h3 class="font-semibold">MindCare Assistant</h3>
                </div>
                <button onclick="toggleChatWindow()" class="text-slate-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="chat-messages-body" class="flex-1 p-4 overflow-y-auto bg-gray-50 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs">AI</div>
                    <div class="bg-white p-3 rounded-lg border border-gray-200 text-sm text-gray-700 shadow-sm">
                        Halo! Ada yang bisa saya bantu hari ini?
                    </div>
                </div>
            </div>
            <div class="p-3 bg-white border-t border-gray-200">
                <form onsubmit="handleChatSubmit(event)" class="flex gap-2">
                    <input type="text" id="chat-input-field" placeholder="Ketik pesan..." autocomplete="off" class="flex-1 rounded-md border-gray-300 text-sm focus:ring-slate-900 focus:border-slate-900">
                    <button type="submit" class="p-2 rounded-md text-white bg-slate-900 hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleChatWindow() {
            const w = document.getElementById('chat-window-container');
            w.classList.toggle('hidden');
            if(!w.classList.contains('hidden')) document.getElementById('chat-input-field').focus();
        }
        function handleChatSubmit(e) { e.preventDefault(); sendChatMessage(); }
        async function sendChatMessage() {
            const input = document.getElementById('chat-input-field');
            const msg = input.value.trim();
            if(!msg) return;
            addMsg(msg, 'user');
            input.value = '';
            
            const loadId = 'loading-' + Date.now();
            document.getElementById('chat-messages-body').innerHTML += `<div id="${loadId}" class="text-xs text-gray-400">Mengetik...</div>`;
            scrollToBottomChat();

            try {
                const res = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                    body: JSON.stringify({ message: msg })
                });
                const data = await res.json();
                document.getElementById(loadId)?.remove();
                if(data.success) addMsg(data.message, 'ai');
                else addMsg("Maaf, terjadi kesalahan.", 'ai');
            } catch(e) {
                document.getElementById(loadId)?.remove();
                addMsg("Gagal terhubung.", 'ai');
            }
        }
        function addMsg(text, sender) {
            const container = document.getElementById('chat-messages-body');
            const div = document.createElement('div');
            if(sender === 'user') {
                div.className = 'flex justify-end';
                div.innerHTML = `<div class="bg-slate-800 text-white p-3 rounded-lg text-sm max-w-[85%]">${text}</div>`;
            } else {
                div.className = 'flex items-start gap-3';
                div.innerHTML = `<div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-xs">AI</div><div class="bg-white p-3 rounded-lg border border-gray-200 text-sm text-gray-700 shadow-sm max-w-[85%]">${text.replace(/\n/g, '<br>')}</div>`;
            }
            container.appendChild(div);
            scrollToBottomChat();
        }
        function scrollToBottomChat() {
            const c = document.getElementById('chat-messages-body');
            setTimeout(() => { c.scrollTop = c.scrollHeight; }, 50);
        }
        function analyzeFeed() {
             const btn = document.getElementById('btn-analyze');
             btn.innerHTML = 'Menganalisis...';
             fetch("{{ route('teacher.analyze') }}", {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}"},
                body: JSON.stringify({})
            }).then(r=>r.json()).then(d => {
                const res = document.getElementById('conflict-analysis-result');
                res.classList.remove('hidden');
                res.innerHTML = d.success ? d.analysis : 'Gagal menganalisis.';
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg> Generate Analisis AI';
            });
        }
    </script>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mood Chart Initialization
        const ctx = document.getElementById('moodTrendChart').getContext('2d');
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Happy/Calm',
                        data: chartData.datasets.happy.map((v, i) => v + chartData.datasets.calm[i]),
                        borderColor: '#2dd4bf', // Teal-400
                        backgroundColor: 'rgba(45, 212, 191, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Sad',
                        data: chartData.datasets.sad,
                        borderColor: '#fdba74', // Orange-300
                        backgroundColor: 'rgba(253, 186, 116, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Angry',
                        data: chartData.datasets.angry,
                        borderColor: '#f87171', // Red-400
                        backgroundColor: 'rgba(248, 113, 113, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            borderDash: [2, 4],
                            color: '#f3f4f6'
                        },
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
</x-app-layout>
