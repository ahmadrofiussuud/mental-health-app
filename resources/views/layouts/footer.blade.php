<footer class="bg-gradient-to-br from-slate-50 to-white border-t border-slate-200 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/serenity-hub-logo.png') }}" alt="Serenity Hub Logo" class="w-12 h-12 rounded-xl shadow-sm">
                    <span class="font-black text-xl bg-clip-text text-transparent bg-gradient-to-r from-teal-600 to-purple-600">SerenityHub</span>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed">
                    Supporting student mental health through AI-powered tools and compassionate guidance.
                </p>
                <div class="flex gap-2">
                    <span class="px-2 py-1 bg-teal-50 text-teal-600 text-xs font-semibold rounded-lg">Mental Health</span>
                    <span class="px-2 py-1 bg-purple-50 text-purple-600 text-xs font-semibold rounded-lg">AI-Powered</span>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="md:pl-8">
                <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-teal-600 transition flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mood.check') }}" class="text-slate-600 hover:text-teal-600 transition flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            Mood Check
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('journal.index') }}" class="text-slate-600 hover:text-teal-600 transition flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            Journal
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h3 class="font-bold text-slate-800 mb-4 text-sm uppercase tracking-wider">Need Help?</h3>
                <div class="space-y-3">
                    <p class="text-sm text-slate-600">
                        Our counselors are here to listen and support you.
                    </p>
                    <a href="#" class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-purple-600 hover:from-teal-700 hover:to-purple-700 text-white px-4 py-2 rounded-xl font-semibold text-sm shadow-md hover:shadow-lg transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        Chat with Counselor
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-slate-500">
                © {{ date('Y') }} SerenityHub - Mental Health App for Schools
            </div>
            
            <div class="flex gap-6 text-sm">
                <a href="#" class="text-slate-600 hover:text-indigo-600 transition font-medium">Privacy Policy</a>
                <a href="#" class="text-slate-600 hover:text-indigo-600 transition font-medium">Support Center</a>
                <a href="#" class="text-slate-600 hover:text-indigo-600 transition font-medium">About Us</a>
            </div>
        </div>
    </div>
</footer>
