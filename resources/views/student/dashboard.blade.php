<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <span class="text-3xl">🚀</span> {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Welcome Hero -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-3xl mb-2">Hello, {{ Auth::user()->name }}! 👋</h3>
                    <p class="text-blue-100 text-lg max-w-2xl">
                        Welcome to your safe space. How are you feeling today? Remember, your mental health matters just as much as your grades.
                    </p>
                    <div class="mt-8 flex gap-4">
                        <a href="{{ route('mood.check') }}" class="bg-white text-blue-600 font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl hover:bg-blue-50 transition transform hover:-translate-y-1 flex items-center gap-2">
                            <span>🎭</span> Check My Mood
                        </a>
                        <a href="{{ route('journal.index') }}" class="bg-blue-500/30 text-white font-bold py-3 px-6 rounded-xl border border-white/20 hover:bg-blue-500/50 transition flex items-center gap-2 backdrop-blur-sm">
                            <span>📔</span> Write Journal
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Stat Card 1 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-16 h-16 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-2xl">
                        🔥
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Daily Streak</p>
                        <h4 class="font-bold text-2xl text-slate-800">1 Day</h4>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition">
                    <div class="w-16 h-16 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-2xl">
                        📝
                    </div>
                    <div>
                        <p class="text-slate-500 text-sm font-medium uppercase tracking-wide">Journal Entries</p>
                        <h4 class="font-bold text-2xl text-slate-800">0</h4>
                    </div>
                </div>

                <!-- Quick Tip -->
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 border border-orange-100 text-orange-800">
                    <h4 class="font-bold flex items-center gap-2 mb-2">
                        <span>💡</span> Daily Tip
                    </h4>
                    <p class="text-sm italic">"Breathing deeply for just 2 minutes can reduce stress significantly. Try it now!"</p>
                </div>
            </div>

             <!-- Recent Activity Section (Placeholder) -->
             <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg mb-4">Recent Activity</h3>
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <div class="text-4xl mb-2">🍃</div>
                    <p>No recent activity yet. Start by checking your mood!</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
