<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Advisor - Konsultasi Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-black text-slate-900">🤖 AI Advisor</h1>
            <p class="text-slate-600 mt-1">Konsultasi Psikologi & Strategi Penanganan Siswa</p>
        </div>

        <!-- Chat Container -->
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
            <!-- Messages Area -->
            <div id="chat-messages" class="p-6 space-y-6 max-h-[600px] overflow-y-auto">
                
                <!-- AI Welcome Message -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        🤖
                    </div>
                    <div class="flex-1">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl rounded-tl-sm p-4 border border-indigo-100">
                            <p class="text-slate-800 font-semibold mb-1">Halo Bapak/Ibu Guru! 👋</p>
                            <p class="text-slate-700 text-sm leading-relaxed">
                                Saya telah menganalisis data <strong>Azid (Siswa)</strong> (Skor Risiko: <span class="text-orange-600 font-bold">45</span>).
                            </p>
                            <p class="text-slate-700 text-sm leading-relaxed mt-2">
                                Saya siap membantu memberikan saran pendekatan, ide percakapan, atau strategi resolusi konflik. 
                                Klik tombol "AI Advisor" pada jurnal untuk saran spesifik.
                            </p>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block">Baru saja</span>
                    </div>
                </div>

                @if(isset($analysis))
                <!-- AI Analysis Response -->
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-emerald-600 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                        AI
                    </div>
                    <div class="flex-1">
                        <div class="bg-white rounded-2xl rounded-tl-sm p-5 border-2 border-indigo-200 shadow-sm">
                            <div class="prose prose-sm max-w-none">
                                {!! $analysis !!}
                            </div>
                        </div>
                        <span class="text-xs text-slate-400 mt-1 block">{{ now()->diffForHumans() }}</span>
                    </div>
                </div>
                @endif

                <!-- Empty State -->
                @if(!isset($analysis))
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-5xl">
                        💭
                    </div>
                    <p class="text-slate-600 font-medium mb-2">Belum ada analisis</p>
                    <p class="text-slate-400 text-sm">Kembali ke dashboard dan klik "AI Advisor" pada siswa yang ingin dianalisis</p>
                </div>
                @endif
            </div>

            <!-- Input Area -->
            <div class="p-6 bg-slate-50 border-t border-slate-200">
                <form action="{{ route('teacher.ai-advisor.analyze') }}" method="POST" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id ?? '' }}">
                    <input 
                        type="text" 
                        name="question"
                        placeholder="Tanya strategi pendekatan..." 
                        class="flex-1 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-3"
                    >
                    <button 
                        type="submit" 
                        class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 grid grid-cols-3 gap-4">
            <button onclick="window.location.href='{{ route('teacher.dashboard') }}'" class="bg-white hover:bg-slate-50 border-2 border-slate-200 rounded-xl p-4 transition-all text-center">
                <div class="text-2xl mb-2">📊</div>
                <div class="text-sm font-bold text-slate-700">Dashboard</div>
            </button>
            <button onclick="window.location.href='{{ route('teacher.risk.overview') }}'" class="bg-white hover:bg-slate-50 border-2 border-slate-200 rounded-xl p-4 transition-all text-center">
                <div class="text-2xl mb-2">⚠️</div>
                <div class="text-sm font-bold text-slate-700">Risk Overview</div>
            </button>
            <button onclick="window.location.href='{{ route('chat.index') }}'" class="bg-white hover:bg-slate-50 border-2 border-slate-200 rounded-xl p-4 transition-all text-center">
                <div class="text-2xl mb-2">💬</div>
                <div class="text-sm font-bold text-slate-700">Chat Student</div>
            </button>
        </div>
    </div>

    <!-- Floating Help Button -->
    <div class="fixed bottom-6 right-6">
        <button class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-2xl transition-transform hover:scale-110 flex items-center justify-center w-14 h-14">
            <span class="text-xl">💡</span>
        </button>
    </div>
</body>
</html>
