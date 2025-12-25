<x-app-layout>
    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 flex items-center gap-3">
                    <span class="text-4xl">💬</span> Chat with Counselor
                </h1>
                <p class="text-slate-600 mt-2">Connect with available teachers for support and guidance</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-250px)]">
                
                <!-- Left Sidebar: Available Teachers -->
                <div class="lg:col-span-1 bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                    <div class="p-6 border-b border-slate-100 bg-gradient-to-r from-teal-50 to-purple-50">
                        <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                            <span class="text-2xl">{{ Auth::user()->role === 'teacher' ? '👨‍🎓' : '🧑‍🏫' }}</span> 
                            {{ Auth::user()->role === 'teacher' ? 'Students' : 'Available Counselors' }}
                        </h3>
                        <p class="text-sm text-slate-600 mt-1">
                            {{ Auth::user()->role === 'teacher' ? 'Your student conversations' : 'Select a teacher to contact' }}
                        </p>
                    </div>
                    
                    <!-- Teachers List -->
                    <div id="teachers-list" class="flex-1 overflow-y-auto p-4 space-y-2">
                        <!-- Will be populated by JavaScript -->
                        <div class="flex items-center justify-center h-32">
                            <div class="animate-spin rounded-full h-8 w-8 border-4 border-teal-200 border-t-teal-600"></div>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Chat Window -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                    
                    <!-- Chat Header -->
                    <div id="chat-header" class="hidden p-6 border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-600" id="teacher-avatar">
                                I
                            </div>
                            <div>
                                <h3 id="teacher-name" class="font-bold text-lg text-slate-800">Select a teacher</h3>
                                <p id="teacher-status" class="text-sm text-slate-600">
                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                    Online
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div id="empty-state" class="flex-1 flex flex-col items-center justify-center text-center p-8">
                        <div class="w-32 h-32 bg-slate-100 rounded-full flex items-center justify-center text-6xl mb-6">
                            👋
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Welcome to Chat!</h3>
                        <p class="text-slate-600 max-w-md">
                            @if(Auth::user()->role === 'teacher')
                                Select a student from the left to view your conversation. Students who have messaged you will appear in the list.
                            @else
                                Select a counselor from the left to start a conversation. Our teachers are here to help and support you.
                            @endif
                        </p>
                    </div>
                    
                    <!-- Messages Area -->
                    <div id="messages-area" class="hidden flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50">
                        <!-- Messages will be loaded here -->
                    </div>
                    
                    <!-- Input Area -->
                    <div id="input-area" class="hidden p-6 bg-white border-t border-slate-100">
                        <form id="message-form" class="flex gap-3">
                            <input 
                                type="text" 
                                id="message-input" 
                                placeholder="Type your message here..." 
                                class="flex-1 rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 text-sm"
                                required
                            >
                            <button 
                                type="submit" 
                                class="bg-gradient-to-r from-teal-600 to-purple-600 hover:from-teal-700 hover:to-purple-700 text-white px-6 py-2 rounded-xl font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Send
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentTeacherId = null;
            let pollingInterval = null;
            const POLL_INTERVAL = 3000; // 3 seconds

            // Load teachers on page load
            document.addEventListener('DOMContentLoaded', function() {
                loadTeachers();
            });

            // Load available teachers
            async function loadTeachers() {
                try {
                    const response = await fetch('{{ route('chat.teachers') }}');
                    const teachers = await response.json();
                    
                    const teachersList = document.getElementById('teachers-list');
                    teachersList.innerHTML = '';
                    
                    if (teachers.length === 0) {
                        const isTeacher = {{ Auth::user()->role === 'teacher' ? 'true' : 'false' }};
                        teachersList.innerHTML = `
                            <div class="text-center py-8 text-slate-500">
                                <p class="text-sm">${isTeacher ? 'No students have messaged you yet.' : 'No teachers available at the moment.'}</p>
                            </div>
                        `;
                        return;
                    }
                    
                    teachers.forEach(teacher => {
                        const teacherItem = document.createElement('div');
                        teacherItem.className = 'p-4 rounded-xl border-2 border-slate-100 hover:border-teal-200 hover:bg-teal-50 cursor-pointer transition-all';
                        teacherItem.onclick = () => selectTeacher(teacher);
                        
                        const initial = teacher.name.charAt(0).toUpperCase();
                        const statusColor = teacher.online ? 'bg-green-500' : 'bg-slate-400';
                        const statusText = teacher.online ? 'ONLINE' : teacher.last_active;
                        
                        teacherItem.innerHTML = `
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-lg font-bold" style="background: linear-gradient(135deg, #14b8a6 0%, #a855f7 100%); color: white;">
                                    ${initial}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-slate-800 truncate">${teacher.name}</h4>
                                    <p class="text-xs text-slate-500 truncate">${teacher.email}</p>
                                    <p class="text-xs font-semibold mt-1 flex items-center gap-1">
                                        <span class="inline-block w-2 h-2 ${statusColor} rounded-full"></span>
                                        <span class="${teacher.online ? 'text-green-600' : 'text-slate-500'}">${statusText}</span>
                                    </p>
                                </div>
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        `;
                        
                        teachersList.appendChild(teacherItem);
                    });
                } catch (error) {
                    console.error('Error loading teachers:', error);
                }
            }

            // Select a teacher to chat with
            function selectTeacher(teacher) {
                currentTeacherId = teacher.id;
                
                // Update UI
                document.getElementById('empty-state').classList.add('hidden');
                document.getElementById('chat-header').classList.remove('hidden');
                document.getElementById('messages-area').classList.remove('hidden');
                document.getElementById('input-area').classList.remove('hidden');
                
                // Update teacher info
                const initial = teacher.name.charAt(0).toUpperCase();
                document.getElementById('teacher-avatar').textContent = initial;
                document.getElementById('teacher-name').textContent = teacher.name;
                document.getElementById('teacher-status').innerHTML = teacher.online 
                    ? '<span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span>Online'
                    : '<span class="inline-block w-2 h-2 bg-slate-400 rounded-full mr-1"></span>' + teacher.last_active;
                
                // Load messages
                loadMessages();
                
                // Start polling
                if (pollingInterval) clearInterval(pollingInterval);
                pollingInterval = setInterval(loadMessages, POLL_INTERVAL);
            }

            // Load messages with selected teacher
            async function loadMessages() {
                if (!currentTeacherId) return;
                
                try {
                    const response = await fetch(`/chat/messages/${currentTeacherId}`);
                    const messages = await response.json();
                    
                    const messagesArea = document.getElementById('messages-area');
                    messagesArea.innerHTML = '';
                    
                    if (messages.length === 0) {
                        messagesArea.innerHTML = `
                            <div class="text-center py-8 text-slate-500 text-sm">
                                No messages yet. Start the conversation!
                            </div>
                        `;
                        return;
                    }
                    
                    messages.forEach(msg => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = msg.is_mine ? 'flex justify-end' : 'flex justify-start';
                        
                        const bubbleClass = msg.is_mine 
                            ? 'bg-gradient-to-r from-teal-600 to-purple-600 text-white rounded-tr-sm'
                            : 'bg-white text-slate-800 rounded-tl-sm border border-slate-200';
                        
                        messageDiv.innerHTML = `
                            <div class="max-w-[70%]">
                                <div class="px-4 py-3 rounded-2xl ${bubbleClass} shadow-sm">
                                    <p class="text-sm leading-relaxed">${escapeHtml(msg.message)}</p>
                                </div>
                                <p class="text-xs text-slate-400 mt-1 ${msg.is_mine ? 'text-right' : 'text-left'} px-2">
                                    ${msg.created_at}
                                </p>
                            </div>
                        `;
                        
                        messagesArea.appendChild(messageDiv);
                    });
                    
                    // Scroll to bottom
                    messagesArea.scrollTop = messagesArea.scrollHeight;
                } catch (error) {
                    console.error('Error loading messages:', error);
                }
            }

            // Send message
            document.getElementById('message-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!currentTeacherId) return;
                
                const input = document.getElementById('message-input');
                const message = input.value.trim();
                
                if (!message) return;
                
                try {
                    const response = await fetch('{{ route('chat.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            receiver_id: currentTeacherId,
                            message: message
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        input.value = '';
                        loadMessages(); // Reload to show new message
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                }
            });

            // Escape HTML to prevent XSS
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Stop polling when leaving page
            window.addEventListener('beforeunload', function() {
                if (pollingInterval) clearInterval(pollingInterval);
            });
        </script>
    @endpush
</x-app-layout>
