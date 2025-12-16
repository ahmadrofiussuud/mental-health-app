<x-app-layout>
    <x-slot name="header">
         <h2 class="font-bold text-2xl text-slate-800 leading-tight flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-slate-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <span class="text-3xl">⚖️</span> {{ __('AI Conflict Mediator') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
            
            <!-- Left Column: Input Form -->
            <div class="lg:w-1/2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                    <h3 class="font-bold text-lg text-slate-800 mb-4">Conflict Context</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Student A (Perspective)</label>
                            <input type="text" id="student_a_name" class="w-full mb-2 rounded-lg border-slate-300 text-sm" placeholder="Student Name (e.g. Azid)">
                            <textarea id="story_a" rows="4" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="What did Student A say? (e.g. 'I was just joking...')"></textarea>
                        </div>
                        
                        <hr class="border-slate-100">

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Student B (Perspective)</label>
                            <input type="text" id="student_b_name" class="w-full mb-2 rounded-lg border-slate-300 text-sm" placeholder="Student Name (e.g. Ammar)">
                            <textarea id="story_b" rows="4" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="What did Student B say? (e.g. 'He keeps making fun of me...')"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Mediator Notes (Optional)</label>
                            <textarea id="context_notes" rows="2" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Any background info? (e.g. They have a history of conflict)"></textarea>
                        </div>

                        <button onclick="generateResolution()" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 rounded-xl shadow-lg transform transition active:scale-95 flex items-center justify-center gap-2">
                             <span id="btn-text">Generate Mediation Strategy</span>
                             <svg id="btn-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: AI Output -->
            <div class="lg:w-1/2">
                <div class="bg-white p-6 rounded-2xl shadow-lg border border-indigo-100 h-full flex flex-col">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <span class="text-2xl">🤖</span> AI Analysis
                        </h3>
                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full font-bold">GEMINI 1.5 FLASH</span>
                    </div>

                    <div id="ai-output" class="flex-1 bg-slate-50 rounded-xl p-6 border border-slate-100 text-slate-600 text-sm leading-relaxed overflow-y-auto min-h-[400px]">
                        <div class="flex flex-col items-center justify-center h-full opacity-50 space-y-4">
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                            <p>Enter the conflict details and press Generate to see the AI analysis.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        async function generateResolution() {
            const nameA = document.getElementById('student_a_name').value || 'Student A';
            const storyA = document.getElementById('story_a').value;
            const nameB = document.getElementById('student_b_name').value || 'Student B';
            const storyB = document.getElementById('story_b').value;
            const context = document.getElementById('context_notes').value;

            if (!storyA || !storyB) {
                alert('Please enter perspectives for both students.');
                return;
            }

            const outputDiv = document.getElementById('ai-output');
            const btnText = document.getElementById('btn-text');
            const btnIcon = document.getElementById('btn-icon');

            // Loading State
            outputDiv.innerHTML = '<div class="flex items-center justify-center h-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div></div>';
            btnText.innerText = 'Analyzing Conflict...';
            
            // Construct Prompt
            const prompt = `
                ACT AS A SCHOOL COUNSELOR AND CONFLICT MEDIATOR.
                
                Conflict Scenario:
                1. ${nameA} says: "${storyA}"
                2. ${nameB} says: "${storyB}"
                3. Additional Context: "${context}"

                TASK:
                Provide a Restorative Justice mediation plan.
                
                OUTPUT FORMAT (Use HTML for formatting):
                <h4>🔍 Analysis</h4>
                <p>[Analyze the underlying feelings of both students]</p>
                
                <h4>🤝 Mediation Strategy</h4>
                <ul>
                    <li><strong>Step 1:</strong> [What to say to ${nameA}]</li>
                    <li><strong>Step 2:</strong> [What to say to ${nameB}]</li>
                    <li><strong>Step 3:</strong> [How to bring them together]</li>
                </ul>

                <h4>✨ Predicted Outcome</h4>
                <p>[Expected resolution]</p>
            `;

            try {
                const response = await fetch("{{ route('chatbot.send') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ message: prompt })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Gemini returns Markdown, we might need basic parsing or rely on the simple HTML instruction
                    // Note: The prompt asks for HTML, but Gemini might wrap in markdown.
                    // For now, let's just display what we get, maybe wrapping in pre-line.
                    outputDiv.innerHTML = data.message.replace(/\n/g, '<br>'); 
                } else {
                    outputDiv.innerHTML = '<p class="text-red-500">Error generating resolution.</p>';
                }

            } catch (error) {
                console.error(error);
                outputDiv.innerHTML = '<p class="text-red-500">System Error.</p>';
            } finally {
                btnText.innerText = 'Generate Mediation Strategy';
            }
        }
    </script>
</x-app-layout>
