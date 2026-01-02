<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Usage Statistics</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100 min-h-screen">
    <div class="max-w-6xl mx-auto p-6">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('teacher.dashboard') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Dashboard
            </a>
            <h1 class="text-3xl font-black text-slate-900">📊 API Usage Statistics</h1>
            <p class="text-slate-600 mt-1">Monitor penggunaan Gemini API real-time</p>
        </div>

        <!-- Main Stats Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-2xl text-slate-800">Usage Today</h3>
                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                    {{ now()->format('d M Y') }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Requests -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-xl border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-blue-600 text-sm font-semibold uppercase">Requests</p>
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </div>
                    <p class="text-4xl font-black text-blue-900">{{ $stats['total_requests'] }}</p>
                    <p class="text-blue-600 text-xs mt-1">API Calls</p>
                </div>

                <!-- Total Tokens -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border-2 border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-purple-600 text-sm font-semibold uppercase">Total Tokens</p>
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <p class="text-4xl font-black {{ $stats['total_tokens'] > 100000 ? 'text-red-600' : 'text-purple-900' }}">
                        {{ number_format($stats['total_tokens']) }}
                    </p>
                    <p class="text-purple-600 text-xs mt-1">Prompt + Completion</p>
                </div>

                <!-- Prompt Tokens -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-xl border-2 border-green-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-green-600 text-sm font-semibold uppercase">Input</p>
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path></svg>
                    </div>
                    <p class="text-4xl font-black text-green-900">{{ number_format($stats['total_prompt_tokens']) }}</p>
                    <p class="text-green-600 text-xs mt-1">Prompt Tokens</p>
                </div>

                <!-- Completion Tokens -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-6 rounded-xl border-2 border-orange-200">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-orange-600 text-sm font-semibold uppercase">Output</p>
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </div>
                    <p class="text-4xl font-black text-orange-900">{{ number_format($stats['total_completion_tokens']) }}</p>
                    <p class="text-orange-600 text-xs mt-1">Completion Tokens</p>
                </div>
            </div>

            <!-- Warning if approaching limits -->
            @if($stats['total_tokens'] > 100000)
                <div class="mt-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <div>
                            <p class="font-bold text-yellow-800 mb-1">⚠️ Mendekati batas harian</p>
                            <p class="text-yellow-700 text-sm">Penggunaan token sudah tinggi. Pertimbangkan untuk mengurangi request atau implementasikan caching lebih agresif.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($stats['total_requests'] == 0)
                <div class="mt-6 bg-slate-50 border-2 border-slate-200 rounded-xl p-5 text-center">
                    <p class="text-slate-500">Belum ada penggunaan API hari ini</p>
                </div>
            @endif
        </div>

        <!-- Rate Limits Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold text-lg text-slate-800 mb-4">🚦 Rate Limits</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Per User</span>
                        <span class="font-bold text-slate-900">10 req/min</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Retry Strategy</span>
                        <span class="font-bold text-green-600">Exponential Backoff</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Max Retries</span>
                        <span class="font-bold text-slate-900">3 attempts</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold text-lg text-slate-800 mb-4">💡 Optimizations</h4>
                <ul class="space-y-2 text-sm text-slate-700">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Journal summarization (60% token savings)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Risk cache (1 calc/day per student)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Conflict keyword filtering</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Request throttling middleware</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
