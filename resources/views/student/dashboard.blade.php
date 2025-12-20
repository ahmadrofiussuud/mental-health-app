<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Hero Banner: Card Overlay Style -->
            <div class="relative w-full h-[500px] rounded-[3rem] overflow-hidden shadow-sm group">
                <!-- Background Image: Aligned to Right to show the bus -->
                <div class="absolute inset-0 bg-slate-100">
                     <img src="{{ asset('images/school-bus.png') }}" class="w-full h-full object-cover object-right md:object-[right_center]" alt="School Bus">
                </div>
                
                <!-- Floating Card: Aligned Left -->
                <div class="absolute inset-y-0 left-0 md:left-12 flex items-center z-10 w-full md:w-auto">
                    <div class="bg-white/95 backdrop-blur-sm p-10 md:p-14 rounded-[2.5rem] shadow-2xl border border-white/50 max-w-xl mx-4 md:mx-0 transform transition duration-500 hover:scale-[1.01]">
                        
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 mb-6 bg-teal-50 px-4 py-1.5 rounded-full border border-teal-100">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                            </span>
                            <span class="text-xs font-bold text-teal-600 uppercase tracking-widest">Student Portal</span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="text-4xl md:text-5xl font-black text-slate-900 mb-6 leading-tight tracking-tight">
                            Hello, <span class="text-indigo-600">{{ Auth::user()->name }}</span>! 🎒
                        </h1>
                        
                        <!-- Quote -->
                        <p class="text-slate-600 font-medium text-lg mb-10 leading-relaxed border-l-4 border-indigo-100 pl-4 py-1">
                            "Every day is a new beginning. Keep learning, keep growing, and always believe in yourself!"
                        </p>
                        
                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('mood.check') }}" class="inline-flex justify-center items-center gap-2 bg-[#4F46E5] hover:bg-[#4338ca] text-white px-8 py-4 rounded-2xl font-bold text-base transition shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform active:scale-95">
                                🙂 Check My Mood
                            </a>
                            
                            <a href="{{ route('journal.index') }}" class="inline-flex justify-center items-center gap-2 bg-white border-2 border-slate-100 text-slate-700 hover:bg-slate-50 hover:border-slate-200 px-8 py-4 rounded-2xl font-bold text-base transition transform active:scale-95 shadow-sm">
                                📝 Write Journal
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Stats Section -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center gap-3 pl-2">
                        <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">Your Journey</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Streak Card -->
                        <div class="relative bg-gradient-to-br from-[#F97316] to-[#EA580C] rounded-[2rem] p-8 text-white h-64 shadow-xl shadow-orange-200/50 hover:shadow-2xl transition duration-300 overflow-hidden group">
                           <!-- <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition transform group-hover:scale-110"></div> -->
                            
                            <div class="flex justify-between items-start mb-8 relative z-10">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/10">🔥</div>
                                <span class="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-white/10">Streak</span>
                            </div>
                            
                            <div class="absolute bottom-8 left-8 relative z-10">
                                <h3 class="text-7xl font-black mb-2 tracking-tighter leading-none">1</h3>
                                <p class="text-orange-50 font-bold text-sm opacity-90">Day in a row. You're on fire!</p>
                            </div>
                        </div>

                        <!-- Entries Card -->
                        <div class="relative bg-gradient-to-br from-[#8B5CF6] to-[#7C3AED] rounded-[2rem] p-8 text-white h-64 shadow-xl shadow-purple-200/50 hover:shadow-2xl transition duration-300 overflow-hidden group">
                             <!-- <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition transform group-hover:scale-110"></div> -->
                            
                             <div class="flex justify-between items-start mb-8 relative z-10">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl shadow-inner border border-white/10">✍️</div>
                                <span class="bg-white/20 backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border border-white/10">Entries</span>
                            </div>
                            
                            <div class="absolute bottom-8 left-8 relative z-10">
                                <h3 class="text-7xl font-black mb-2 tracking-tighter leading-none">0</h3>
                                <p class="text-purple-50 font-bold text-sm opacity-90">Capture your best moments.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Wisdom Section -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3 pl-2">
                        <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">Daily Wisdom</h2>
                    </div>

                    <div class="bg-[#FEFCE8] rounded-[2rem] p-8 h-64 flex flex-col justify-center items-center text-center shadow-lg shadow-yellow-100/50 border border-yellow-100 relative overflow-hidden group hover:shadow-xl transition duration-300">
                        <!-- <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-yellow-300/20 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition duration-700"></div> -->
                        
                        <div class="relative z-10 w-16 h-16 bg-white rounded-2xl shadow-md flex items-center justify-center text-4xl mb-6 transform group-hover:-rotate-12 transition duration-300 ease-out">😄</div>
                        
                        <div class="relative z-10 px-2">
                            <h3 class="text-xl font-black text-slate-800 mb-2">Smile More!</h3>
                            <p class="text-slate-600 font-medium text-sm leading-relaxed">
                                "Did you know? Smiling releases endorphins which helps you feel better instantly. Try it now!"
                            </p>
                        </div>
                        
                        <!-- Sunflower decor (optional) -->
                         <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-yellow-200/50 rounded-full blur-2xl"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
