<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <span class="text-3xl">🤖</span> {{ __('AI & Privacy Tools') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Introduction -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-2">Welcome to the AI Integration Hub</h3>
                <p class="text-slate-600">
                    This page provides direct access to the experimental AI and Privacy features implemented in MindCare.
                    Use this area to test the functionality if it is not appearing correctly on your dashboard.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- 1. AI Teacher Copilot (Teachers & Admins Only) -->
                @if(Auth::user()->role !== 'student')
                <div class="bg-white rounded-2xl shadow-lg shadow-teal-100 border border-teal-100 overflow-hidden relative">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-teal-100 p-3 rounded-full text-teal-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">AI Teacher Copilot</h3>
                        </div>
                        
                        <p class="text-slate-600 mb-6">
                            The AI Copilot is designed to assist teachers with pedagogical advice using Restorative Justice principles.
                            <br><br>
                            <strong>Status:</strong> <span class="text-teal-600 font-bold">Active</span><br>
                            <strong>Model:</strong> Google Gemini 1.5 Flash
                        </p>

                        <div class="bg-slate-50 p-4 rounded-xl border border-dashed border-slate-300 mb-6 text-sm text-slate-500">
                            <em>"The chat widget should be visible in the bottom right corner of this page. If you don't see it, try the forced reload button below."</em>
                        </div>

                        <button onclick="window.location.reload(true)" class="w-full bg-slate-800 hover:bg-slate-900 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Force Reload Page
                        </button>
                    </div>
                </div>
                @endif

                <!-- 2. Anonymous Venting -->
                <div class="bg-white rounded-2xl shadow-lg shadow-purple-100 border border-purple-100 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-purple-100 p-3 rounded-full text-purple-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">Anonymous Venting</h3>
                        </div>

                        <p class="text-slate-600 mb-6">
                            Students can express themselves freely without fear of judgment. The system masks their identity from teachers in the dashboard view.
                            <br><br>
                            <strong>Status:</strong> <span class="text-purple-600 font-bold">Active</span>
                        </p>

                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 mb-6 text-sm text-purple-800">
                            <strong>To Test:</strong> Go to the Journal page and check "Post Anonymously" when creating an entry.
                        </div>

                        <a href="{{ route('journal.index') }}" class="block text-center w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-xl transition shadow-md shadow-purple-200">
                            Go to Journal & Vent
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Explicitly Include Chatbot Here as well for safety (Teachers Only) -->
    @if(Auth::user()->role !== 'student')
        @include('components.chatbot')
    @endif

</x-app-layout>
