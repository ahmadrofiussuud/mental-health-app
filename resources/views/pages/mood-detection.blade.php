<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
                    <span class="text-3xl">✨</span> {{ __('Mood Check-In') }}
                </h2>
                <p class="text-slate-500 text-sm mt-1">Take a moment to understand how you feel today.</p>
            </div>
            <div class="hidden md:block">
                 <span class="px-4 py-2 bg-white rounded-full text-sm font-medium text-teal-600 shadow-sm border border-teal-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ date('l, d F Y') }}
                 </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <!-- Welcome Alert -->
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-6 shadow-lg text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
                <div class="relative z-10 flex items-start gap-4">
                    <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Daily Check-In</h3>
                        <p class="text-teal-100 text-sm mt-1 max-w-2xl">
                            Hi <span class="font-bold text-white">{{ Auth::user()->name }}</span>! checking in with your emotions helps you stay balanced. Try our AI-powered Mood Scanner or just vent out your feelings below. We are here for you!
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Section 1: Face Analysis Card -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 flex flex-col h-full">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                         <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">📸</span> 
                            Face Mood Scanner
                        </h3>
                        <div class="text-xs font-semibold px-2 py-1 bg-blue-50 text-blue-600 rounded border border-blue-100">AI Powered</div>
                    </div>
                    
                    <div class="p-8 flex-1 flex flex-col items-center justify-center gap-6">
                        <!-- Camera Wrapper -->
                        <div class="relative w-full max-w-sm mx-auto">
                            <div id="camera-wrapper" class="relative aspect-[4/3] bg-slate-900 rounded-2xl overflow-hidden shadow-2xl ring-4 ring-slate-50">
                                <!-- Video flipped visually -->
                                <video id="camera-feed" autoplay playsinline muted class="w-full h-full object-cover transform scale-x-[-1] transition-opacity duration-500"></video>
                                <canvas id="camera-canvas" class="w-full h-full absolute top-0 left-0"></canvas>
                                
                                <!-- Inactive State -->
                                <div id="camera-placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 bg-slate-800 z-10 transition-opacity duration-300">
                                    <div class="bg-slate-700/50 p-4 rounded-full mb-3">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <span class="text-sm font-medium">Camera is currently off</span>
                                </div>
                                
                                <!-- Scanning Overlay -->
                                <div id="scan-overlay" class="absolute inset-0 bg-teal-900/80 flex items-center justify-center z-20 hidden backdrop-blur-sm">
                                    <div class="text-center text-white">
                                        <div class="animate-spin rounded-full h-12 w-12 border-4 border-teal-200 border-t-white mx-auto mb-4"></div>
                                        <p class="font-bold text-xl tracking-wide">Analyzing...</p>
                                        <p class="text-teal-200 text-sm mt-1">Keep still for <span id="scan-timer" class="font-mono font-bold text-white text-lg">5</span>s</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Decor elements -->
                            <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-200 rounded-full blur-3xl opacity-30 -z-10"></div>
                            <div class="absolute -top-4 -left-4 w-24 h-24 bg-teal-200 rounded-full blur-3xl opacity-30 -z-10"></div>
                        </div>

                         <!-- Controls -->
                         <div class="flex gap-3 w-full max-w-sm justify-center">
                            <button id="start-camera-btn" class="flex-1 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-semibold py-2.5 px-4 rounded-xl transition-all shadow-sm hover:shadow active:scale-95 text-sm">
                                Enable Camera
                            </button>
                            <button id="start-scan-btn" disabled class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl transition-all shadow-md shadow-blue-200 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none active:scale-95 flex items-center justify-center gap-2 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Analyze Face
                            </button>
                        </div>
                    </div>

                    <!-- Scan Result Panel -->
                    <div id="result-card" class="bg-slate-50 border-t border-slate-100 p-6 opacity-50 transition-all duration-500">
                        <div id="mood-result-display" class="hidden">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">DETECTED MOOD</h4>
                                    <div class="text-3xl font-black text-slate-800" id="mood-title">--</div>
                                </div>
                                <div class="bg-white p-2 rounded-lg shadow-sm border border-slate-100">
                                    <span id="mood-confidence" class="font-mono text-lg font-bold text-blue-600">0%</span>
                                    <span class="text-xs text-slate-400 block text-right">Confidence</span>
                                </div>
                            </div>
                            <p class="text-slate-600 text-sm bg-white p-3 rounded-lg border border-slate-100 italic" id="mood-desc">No scan data yet.</p>
                            <div class="w-full bg-slate-200 rounded-full h-3 mt-4 overflow-hidden">
                                <div id="mood-bar" class="bg-gradient-to-r from-blue-400 to-indigo-500 h-3 rounded-full transition-all duration-1000" style="width: 0%"></div>
                            </div>
                        </div>
                        <div id="mood-result-placeholder" class="text-slate-400 text-sm text-center py-4">
                            Waiting for scan...
                        </div>
                    </div>
                </div>

                <!-- Section 2: Voice Analysis Card -->
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 flex flex-col h-full">
                     <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                         <h3 class="font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-8 h-8 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-lg">🎙️</span> 
                            Daily Vent
                        </h3>
                         <div class="text-xs font-semibold px-2 py-1 bg-purple-50 text-purple-600 rounded border border-purple-100">Speech-to-Text</div>
                    </div>

                    <div class="p-8 flex-1 flex flex-col gap-6">
                        <!-- Instructions -->
                        <div class="bg-purple-50 rounded-xl p-4 border border-purple-100">
                            <p class="text-purple-800 text-sm leading-relaxed">
                                <span class="font-bold">How are you feeling right now?</span> Speak freely. Your voice helps us understand your emotional state better than just words.
                            </p>
                        </div>

                        <!-- Visualizer & Button -->
                        <div class="bg-slate-900 rounded-2xl overflow-hidden p-8 relative h-48 flex flex-col items-center justify-center group">
                            <canvas id="audio-visualizer" class="absolute inset-0 w-full h-full opacity-30"></canvas>
                            
                            <div class="relative z-10 flex flex-col items-center gap-4">
                                <button id="record-btn" class="w-16 h-16 rounded-full bg-red-500 hover:bg-red-600 text-white shadow-lg shadow-red-500/30 flex items-center justify-center transition-all transform hover:scale-110 active:scale-95 group-hover:shadow-red-500/50 border-4 border-slate-800">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                </button>
                                <p class="text-slate-400 text-xs font-medium tracking-wide" id="record-status">TAP TO RECORD</p>
                            </div>
                        </div>

                        <!-- Transcript Area -->
                         <div class="relative flex-1">
                            <textarea id="transcript-area" readonly placeholder="Your words will appear here as you speak..." class="w-full h-full min-h-[120px] p-4 bg-slate-50 border-2 border-slate-100 rounded-xl text-slate-600 text-sm focus:outline-none focus:border-purple-200 resize-none transition-colors"></textarea>
                            <div class="absolute top-3 right-3 px-2 py-0.5 bg-slate-200 rounded text-[10px] font-bold text-slate-500 uppercase tracking-wide">Transcript</div>
                        </div>
                        
                        <!-- Analysis Result -->
                        <div id="voice-conclusion" class="bg-white border-l-4 border-purple-500 p-4 shadow-sm rounded-r-lg hidden animate-fade-in-up">
                            <h5 class="font-bold text-gray-400 text-xs uppercase mb-1">Sentiment Analysis</h5>
                            <p id="sentiment-text" class="text-slate-800 font-medium text-lg">--</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                 <p class="text-slate-400 text-xs">
                    Privacy Note: Your facial data is analyzed locally in your browser and is not stored on our servers.
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
        @vite('resources/js/mood-detection.js')
    @endpush

    <style>
        /* Overlay canvas on top of video */
        #camera-wrapper {
            position: relative;
        }
        #camera-feed, #camera-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
    </style>
</x-app-layout>
