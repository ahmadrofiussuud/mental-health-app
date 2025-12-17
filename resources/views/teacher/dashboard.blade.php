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
                <a href="{{ route('teacher.risk.overview') }}" class="block bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all cursor-pointer ring-2 ring-transparent hover:ring-red-200">
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
                </a>

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
                    <div class="flex items-center gap-3">
                         <button onclick="analyzeFeed()" id="btn-analyze" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 px-4 py-2 rounded-lg text-xs font-bold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            AI Analyze Feed
                        </button>
                    </div>
                </div>

                <!-- AI Analysis Result Container -->
                <div id="conflict-analysis-result" class="hidden border-b border-indigo-100 bg-indigo-50/30 p-6 animate-fade-in">
                    <!-- Content injected by JS -->
                </div>

                <!-- Feed List -->
                <div class="divide-y divide-slate-100">
                    @forelse($recentActivities as $activity)
                        @php
                            $moodData = $moodMap[$activity->mood] ?? $moodMap['neutral'];
                            
                            // Simple "AI" Psychology Suggestion logic
                            $suggestion = "Keep monitoring. Encourage positive habits.";
                            $suggestionColor = "text-slate-500";
                            
                            // 1. Keyword content analysis (Overrides mood tag)
                            $negativeKeywords = ['nyindir', 'ganggu', 'ejek', 'nangis', 'sedih', 'marah', 'benci', 'sakit', 'bullied', 'takut'];
                            $contentLower = strtolower($activity->content);
                            $foundNegative = false;
                            
                            foreach($negativeKeywords as $keyword) {
                                if(str_contains($contentLower, $keyword)) {
                                    $foundNegative = true;
                                    break;
                                }
                            }

                            if($foundNegative) {
                                $suggestion = "⚠️ Content indicates distress despite mood label. Check in immediately.";
                                $suggestionColor = "text-red-600 font-bold";
                            } 
                            // 2. Fallback to Mood Analysis
                            elseif($activity->mood == 'sad') {
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
    <!-- INLINED CHATBOT WIDGET -->
    <div id="mindcare-chatbot" class="fixed bottom-6 right-6" style="z-index: 9999;">
        <!-- Chat Toggle Button -->
        <button onclick="toggleChat()" class="bg-teal-600 hover:bg-teal-700 text-white rounded-full p-4 shadow-lg transition transform hover:scale-110 flex items-center justify-center w-16 h-16 border-2 border-white ring-2 ring-teal-200">
            <!-- Chat Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
        </button>

        <!-- Chat Window -->
        <div id="chat-window" class="hidden absolute bottom-20 right-0 w-80 md:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col" style="height: 500px;">
            <div class="bg-teal-600 p-4 flex justify-between items-center text-white">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    <h3 class="font-bold text-lg">MindCare AI</h3>
                </div>
                <button onclick="toggleChat()" class="text-teal-100 hover:text-white focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50">
                <div class="flex flex-col space-y-1">
                    <div class="bg-white p-3 rounded-lg rounded-tl-none shadow-sm max-w-[85%] self-start border border-gray-100 text-gray-700 text-sm">
                        Halo! Saya MindCare AI. Saya di sini untuk menjadi teman curhat atau membantu Bapak/Ibu Guru menangani masalah di kelas.
                        <br><br>
                        Ceritakan apa yang sedang terjadi?
                    </div>
                </div>
            </div>

            <div class="p-4 bg-white border-t border-gray-100">
                <div class="flex items-center space-x-2">
                    <input type="text" id="chat-input" 
                        class="flex-1 border border-gray-300 rounded-full px-4 py-2 focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 text-sm"
                        placeholder="Ketik pesan..." onkeypress="handleEnter(event)">
                    <button onclick="sendMessage()" class="bg-teal-600 hover:bg-teal-700 text-white rounded-full p-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleChat() {
            const window = document.getElementById('chat-window');
            window.classList.toggle('hidden');
            if (!window.classList.contains('hidden')) {
                document.getElementById('chat-input').focus();
            }
        }

        function handleEnter(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        }

        async function sendMessage() {
            const input = document.getElementById('chat-input');
            const message = input.value.trim();
            
            if (!message) return;

            appendMessage(message, 'user');
            input.value = '';

            const loadingId = 'loading-' + Date.now();
            appendLoading(loadingId);
            scrollToBottom();

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                document.getElementById(loadingId).remove();

                if (data.success) {
                    appendMessage(data.message, 'ai');
                } else {
                    appendMessage("Maaf, terjadi kesalahan.", 'ai');
                }

            } catch (error) {
                document.getElementById(loadingId).remove();
                appendMessage("Gagal terhubung ke server.", 'ai');
            }
            scrollToBottom();
        }

        function appendMessage(text, sender) {
            const messagesDiv = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = 'flex flex-col space-y-1';
            
            let bubbleClass = sender === 'user' ? 'bg-teal-600 text-white rounded-tr-none' : 'bg-white border border-gray-100 text-gray-700 rounded-tl-none';
            div.className += sender === 'user' ? ' items-end' : ' items-start';

            div.innerHTML = `<div class="p-3 rounded-lg shadow-sm max-w-[85%] text-sm ${bubbleClass}">${text.replace(/\\n/g, '<br>')}</div>`;
            messagesDiv.appendChild(div);
        }

        function appendLoading(id) {
            const messagesDiv = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.id = id;
            div.className = 'flex flex-col space-y-1 items-start';
            div.innerHTML = '<div class="bg-gray-200 p-3 rounded-lg rounded-tl-none shadow-sm text-gray-500 text-xs italic">Sedang mengetik...</div>';
            messagesDiv.appendChild(div);
        }
        
        function scrollToBottom() {
            const messagesDiv = document.getElementById('chat-messages');
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
        function analyzeFeed() {
            const btn = document.getElementById('btn-analyze');
            const resultDiv = document.getElementById('conflict-analysis-result');
            
            // Loading State
            btn.innerHTML = '<div class="animate-spin rounded-full h-3 w-3 border-b-2 border-indigo-700 mr-2"></div> Scanning...';
            btn.disabled = true;
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<div class="flex items-center justify-center text-indigo-400 text-sm py-4">Analyzing recent journal entries for conflicts...</div>';

            fetch("{{ route('teacher.analyze') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = 'AI Analyze Feed';
                btn.disabled = false;
                
                if (data.success) {
                    resultDiv.innerHTML = data.analysis;
                } else {
                    resultDiv.innerHTML = '<p class="text-red-500 text-sm p-4 text-center">Analysis failed. Please try again.</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = 'AI Analyze Feed';
                btn.disabled = false;
                resultDiv.innerHTML = '<p class="text-red-500 text-sm p-4 text-center">System error occurred.</p>';
            });
        }
    </script>
</x-app-layout>
