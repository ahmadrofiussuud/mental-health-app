<x-app-layout>
    <style>
        /* Hide global chatbot on this page */
        #mindcare-chatbot { display: none !important; }
        
        /* Drawer Animations */
        .drawer-enter { transform: translateX(100%); }
        .drawer-enter-active { transform: translateX(0); transition: transform 0.3s ease-out; }
        .drawer-leave-active { transform: translateX(100%); transition: transform 0.2s ease-in; }
        
        /* Custom Scrollbar for Chat */
        .chat-scroll::-webkit-scrollbar { width: 6px; }
        .chat-scroll::-webkit-scrollbar-track { bg-slate-50; }
        .chat-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>

    <div class="min-h-screen bg-slate-50 pb-12 flex relative overflow-hidden">
        
        <!-- Main Content Area -->
        <div class="flex-1 transition-all duration-300 w-full" id="main-content">
            <!-- Breadcrumb -->
            <div class="bg-white border-b border-slate-200 sticky top-0 z-30 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <a href="{{ route('teacher.risk.overview') }}" class="text-sm text-slate-500 hover:text-indigo-600 font-bold flex items-center gap-2 group">
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-indigo-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </div>
                        Back to Risk Overview
                    </a>
                    <div class="flex items-center gap-2">
                         <button onclick="openCopilot()" class="bg-white border border-indigo-200 text-indigo-700 hover:bg-indigo-50 px-4 py-2 rounded-full text-sm font-bold shadow-sm transition flex items-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                             Open Copilot
                         </button>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Left Column: Student Profile (4 cols) -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Profile Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-100 relative overflow-hidden text-center group">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-indigo-500 to-purple-600 opacity-10"></div>
                        
                        <div class="relative z-10 w-24 h-24 bg-white p-1 rounded-full mx-auto shadow-md mb-4 -mt-2">
                            <div class="w-full h-full bg-slate-100 rounded-full flex items-center justify-center text-3xl font-black text-slate-400">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        </div>
                        
                        <h1 class="text-xl font-bold text-slate-800 relative z-10">{{ $student->name }}</h1>
                        <p class="text-slate-500 text-sm mb-4 relative z-10">{{ $student->email }}</p>
                        
                        <div class="border-t border-slate-100 pt-4 flex justify-between text-sm text-slate-600">
                             <span class="flex items-center gap-1.5">
                                 <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                 {{ $journals->total() }} Journals
                             </span>
                             <span class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Last: {{ $journals->first()?->created_at->format('M d') ?? 'N/A' }}
                            </span>
                        </div>
                    </div>

                    <!-- Risk Analysis Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                                <span class="w-2 h-6 bg-red-500 rounded-full"></span>
                                Risk Profile
                            </h2>
                            <div class="text-right">
                                <span class="block text-3xl font-black {{ $student->risk_score > 50 ? 'text-red-500' : ($student->risk_score > 30 ? 'text-yellow-500' : 'text-green-500') }}">
                                    {{ $student->risk_score }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase">Risk Score (0-100)</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-sm leading-relaxed text-slate-600 mb-6 font-medium">
                            <ul class="space-y-2">
                                @foreach(explode("\n", $student->risk_summary) as $line)
                                    @if(trim($line))
                                        <li class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-indigo-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            <span>{{ str_replace(['Flags:', 'AI Insight:', 'Base Score:'], '', $line) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>

                        <button 
                            data-question="Analisis profil risiko siswa ini dan berikan strategi pendekatan psikologis yang tepat."
                            onclick="askCopilot(this.getAttribute('data-question'))" 
                            class="group w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl text-sm font-bold transition-all shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 transform active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            Generate Strategy with AI
                        </button>
                    </div>
                </div>

                <!-- Right Column: Journal History (8 cols) -->
                <div class="lg:col-span-8 space-y-6">
                     <div class="flex justify-between items-center mb-2">
                        <h2 class="text-xl font-bold text-slate-800">Recent Journals</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400 bg-white px-2 py-1 rounded-md border border-slate-200">Sort by Date</span>
                        </div>
                    </div>

                    <div class="grid gap-6">
                        @forelse($journals as $journal)
                             @php
                                $moodMap = [
                                    'happy' => ['emoji' => '😄', 'bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'border' => 'border-amber-200'],
                                    'sad' => ['emoji' => '😢', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'border' => 'border-indigo-200'],
                                    'angry' => ['emoji' => '😠', 'bg' => 'bg-red-100', 'text' => 'text-red-700', 'border' => 'border-red-200'],
                                    'calm' => ['emoji' => '😌', 'bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'border' => 'border-teal-200'],
                                    'neutral' => ['emoji' => '😐', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
                                ];
                                $style = $moodMap[$journal->mood] ?? $moodMap['neutral'];
                            @endphp
                            
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all group relative">
                                <!-- Top Row -->
                                <div class="flex justify-between items-start mb-4">
                                    <span class="{{ $style['bg'] }} {{ $style['text'] }} {{ $style['border'] }} border px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide flex items-center gap-2">
                                        <span class="text-base">{{ $style['emoji'] }}</span> {{ $journal->mood }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-400">{{ $journal->created_at->format('l, d M Y • H:i') }}</span>
                                </div>

                                <!-- Content -->
                                <div class="mb-5">
                                    <h3 class="font-bold text-lg text-slate-800 mb-2">{{ $journal->title }}</h3>
                                    <p class="text-slate-600 text-sm leading-7">
                                        {{ $journal->content }}
                                    </p>
                                </div>

                                <!-- Action Footer -->
                                <div class="flex items-center justify-between border-t border-slate-50 pt-4 mt-2">
                                    <div class="text-xs text-slate-400 italic">Analyzed by system</div>
                                    <button 
                                        data-question="Siswa menulis: '{{ Str::limit(str_replace(["\r", "\n", "'"], " ", $journal->content), 150) }}'. Analisis emosi yang mendasarinya dan sarankan respons empatik untuk guru."
                                        onclick="askCopilot(this.getAttribute('data-question'))" 
                                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-full transition flex items-center gap-2 group-hover:scale-105">
                                        <span>✨ AI Advisor</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-slate-200">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-3xl opacity-50">📂</div>
                                <h3 class="font-bold text-slate-900">No journals found</h3>
                                <p class="text-slate-500 text-sm">This student hasn't written any entry yet.</p>
                            </div>
                        @endforelse

                        <div class="mt-4">
                            {{ $journals->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT DRAWER: Copilot (Sidebar) -->
        <div id="copilot-drawer" class="fixed top-0 right-0 h-full w-[400px] bg-white shadow-2xl border-l border-slate-200 transform translate-x-full transition-transform duration-300 z-[100] flex flex-col">
            <!-- Drawer Header -->
            <div class="bg-indigo-600 p-5 flex justify-between items-center text-white shrink-0">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">MindCare Copilot</h3>
                        <p class="text-xs text-indigo-200 font-medium">Assistant for {{ $student->name }}</p>
                    </div>
                </div>
                <button onclick="closeCopilot()" class="text-white/70 hover:text-white transition bg-white/10 hover:bg-white/20 rounded-full p-2 cursor-pointer relative z-50">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Chat Area -->
            <div id="copilot-messages" class="flex-1 p-5 overflow-y-auto space-y-5 bg-slate-50 chat-scroll">
                 <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 border border-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-slate-600 text-sm leading-relaxed">
                        Halo Bapak/Ibu Guru! 👋 <br><br>
                        Saya telah menganalisis data <strong>{{ $student->name }}</strong> (Skor Risiko: <strong>{{ $student->risk_score }}</strong>).
                        <br><br>
                        Saya siap membantu memberikan saran pendekatan, ide percakapan, atau strategi resolusi konflik. Klik tombol "AI Advisor" pada jurnal untuk saran spesifik.
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="p-5 bg-white border-t border-slate-200 shrink-0">
                <div class="relative">
                    <input type="text" id="copilot-input" 
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-12 py-3.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm font-medium transition-all"
                        placeholder="Tanya strategi pendekatan..." onkeypress="handleEnterCopilot(event)">
                    
                    <button onclick="sendCopilotMessage()" class="absolute right-2 top-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg p-1.5 transition-all shadow-md shadow-indigo-200">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </div>
                <p class="text-center text-[10px] text-slate-400 mt-2">MindCare AI dapat membuat kesalahan. Harap gunakan penilaian profesional.</p>
            </div>
        </div>

        <!-- Overlay -->
        <div id="drawer-overlay" onclick="closeCopilot()" class="fixed inset-0 bg-slate-900/20 backdrop-blur-sm z-[90] hidden transition-opacity opacity-0"></div>

    </div>

    <script>
        // Use JSON Encode for SAFETY
        const studentContext = {
            student_name: {!! json_encode($student->name) !!},
            risk_score: {!! json_encode($student->risk_score) !!},
            risk_summary: {!! json_encode($student->risk_summary) !!},
            recent_journals: {!! json_encode($journals->take(3)->map(function($j) {
                return "- [" . $j->created_at->format('Y-m-d') . "] Mood: " . $j->mood . ". " . Str::limit($j->content, 100);
            })->implode("\n")) !!}
        };

        function openCopilot() {
            const drawer = document.getElementById('copilot-drawer');
            const overlay = document.getElementById('drawer-overlay');
            
            drawer.classList.remove('translate-x-full');
            overlay.classList.remove('hidden');
            
            // Small delay to allow display:block to apply before opacity transition
            requestAnimationFrame(() => {
                overlay.classList.remove('opacity-0');
            });
            document.getElementById('copilot-input').focus();
        }

        function closeCopilot() {
            const drawer = document.getElementById('copilot-drawer');
            const overlay = document.getElementById('drawer-overlay');
            
            drawer.classList.add('translate-x-full');
            overlay.classList.add('opacity-0');
            
            // Wait for transition to finish before hiding display
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        function toggleCopilot() {
             const drawer = document.getElementById('copilot-drawer');
             if (drawer.classList.contains('translate-x-full')) {
                 openCopilot();
             } else {
                 closeCopilot();
             }
        }

        function askCopilot(question) {
            const drawer = document.getElementById('copilot-drawer');
            // If closed, open it first
            if (drawer.classList.contains('translate-x-full')) {
                toggleCopilot();
            }
            
            const input = document.getElementById('copilot-input');
            input.value = question;
            sendCopilotMessage();
        }

        function handleEnterCopilot(e) {
            if (e.key === 'Enter') sendCopilotMessage();
        }

        async function sendCopilotMessage() {
            const input = document.getElementById('copilot-input');
            const message = input.value.trim();
            if (!message) return;

            appendCopilotMessage(message, 'user');
            input.value = '';

            const loadingId = 'loading-' + Date.now();
            appendCopilotLoading(loadingId);
            
            // Generate Strategy: Scroll to bottom
            const messagesDiv = document.getElementById('copilot-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ 
                        message: message,
                        context: studentContext
                    })
                });

                document.getElementById(loadingId).remove();

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        appendCopilotMessage(data.message, 'ai');
                    } else {
                        appendCopilotMessage("Maaf, saya bingung. Bisa coba tanya yang lain?", 'ai');
                    }
                } else if (response.status === 429) {
                     appendCopilotMessage("⚠️ <strong>Sistem sedang sibuk (Rate Limit).</strong><br>Mohon tunggu 1 menit sebelum mencoba lagi.", 'ai');
                } else {
                     appendCopilotMessage("Maaf, terjadi kesalahan teknis (Error " + response.status + ").", 'ai');
                }

            } catch (error) {
                if(document.getElementById(loadingId)) document.getElementById(loadingId).remove();
                appendCopilotMessage("Gagal terhubung ke server. Periksa koneksi internet Anda.", 'ai');
            }
        }

        function appendCopilotMessage(text, sender) {
            const messagesDiv = document.getElementById('copilot-messages');
            const div = document.createElement('div');
            
            if (sender === 'user') {
                div.className = 'flex items-end justify-end gap-2';
                div.innerHTML = `
                    <div class="bg-indigo-600 text-white p-3 rounded-2xl rounded-tr-none shadow-md max-w-[85%] text-sm leading-relaxed">
                        ${text.replace(/\n/g, '<br>')}
                    </div>
                `;
            } else {
                div.className = 'flex items-start gap-3';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 shrink-0 border border-indigo-200 mt-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-slate-100 text-slate-700 text-sm leading-relaxed max-w-[90%]">
                        ${formatAIResponse(text)}
                    </div>
                `;
            }
            
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function appendCopilotLoading(id) {
            const messagesDiv = document.getElementById('copilot-messages');
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex items-start gap-3';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 border border-slate-200 mt-1 animate-pulse">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                </div>
                <div class="bg-slate-50 p-3 rounded-2xl rounded-tl-none text-slate-400 text-xs font-medium italic">
                    Sedang berpikir...
                </div>
            `;
            messagesDiv.appendChild(div);
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // Simple formatter for bold text from AI
        function formatAIResponse(text) {
            // Convert **text** to <strong>text</strong>
            return text
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\n/g, '<br>');
        }
    </script>
</x-app-layout>
