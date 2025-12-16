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
        <!-- ... (Rest of HTML structure is fine, no changes needed inside window for now) ... -->
        <!-- Header -->
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

        <!-- Chat Messages Area -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto space-y-4 bg-gray-50">
            <!-- Intro Message -->
            <div class="flex flex-col space-y-1">
                <div class="bg-white p-3 rounded-lg rounded-tl-none shadow-sm max-w-[85%] self-start border border-gray-100 text-gray-700 text-sm">
                    Halo! Saya MindCare AI. Saya di sini untuk menjadi teman curhat atau membantu Bapak/Ibu Guru menangani masalah di kelas.
                    <br><br>
                    Ceritakan apa yang sedang terjadi?
                </div>
            </div>
        </div>

        <!-- Input Area -->
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
    console.log("MindCare Chatbot Widget Loaded!"); // Debug log
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
        const messagesDiv = document.getElementById('chat-messages');

        if (!message) return;

        // Add User Message
        appendMessage(message, 'user');
        input.value = '';

        // Add Loading Bubble
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
            
            // Remove Loading
            document.getElementById(loadingId).remove();

            if (data.success) {
                appendMessage(data.message, 'ai');
            } else {
                appendMessage("Maaf, terjadi kesalahan.", 'ai');
            }

        } catch (error) {
            document.getElementById(loadingId).remove();
            appendMessage("Gagal terhubung ke server.", 'ai');
            console.error(error);
        }
        
        scrollToBottom();
    }

    function appendMessage(text, sender) {
        const messagesDiv = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.className = 'flex flex-col space-y-1';
        
        let bubbleClass = '';
        if (sender === 'user') {
            div.className += ' items-end'; // Align right
            bubbleClass = 'bg-teal-600 text-white rounded-tr-none';
        } else {
            div.className += ' items-start'; // Align left
            bubbleClass = 'bg-white border border-gray-100 text-gray-700 rounded-tl-none';
        }

        div.innerHTML = `
            <div class="p-3 rounded-lg shadow-sm max-w-[85%] text-sm ${bubbleClass}">
                ${formatText(text)}
            </div>
        `;
        messagesDiv.appendChild(div);
    }

    function appendLoading(id) {
        const messagesDiv = document.getElementById('chat-messages');
        const div = document.createElement('div');
        div.id = id;
        div.className = 'flex flex-col space-y-1 items-start';
        div.innerHTML = `
            <div class="bg-gray-200 p-3 rounded-lg rounded-tl-none shadow-sm text-gray-500 text-xs italic">
                Sedang mengetik...
            </div>
        `;
        messagesDiv.appendChild(div);
    }
    
    function scrollToBottom() {
        const messagesDiv = document.getElementById('chat-messages');
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function formatText(text) {
        // Basic formatting: Convert newlines to <br> and bold asterisks
        return text.replace(/\n/g, '<br>').replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
    }
</script>
