<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            
            <!-- Hero Banner: Card Overlay Style -->
            <div class="relative w-full h-[380px] rounded-[3rem] overflow-hidden shadow-sm group">
                <!-- Background Image -->
                <div class="absolute inset-0 bg-gradient-to-br from-orange-100 via-pink-50 to-purple-50">
                     <img src="{{ asset('images/journal-hero.png') }}" class="w-full h-full object-cover object-center opacity-90" alt="Journal">
                </div>
                
                <!-- Floating Card: Aligned Left -->
                <div class="absolute inset-y-0 left-0 md:left-12 flex items-center z-10 w-full md:w-auto">
                    <div class="bg-white/95 backdrop-blur-sm p-8 md:p-12 rounded-[2.5rem] shadow-2xl border border-white/50 max-w-xl mx-4 md:mx-0 transform transition duration-500 hover:scale-[1.01]">
                        
                        <!-- Badge -->
                        <div class="inline-flex items-center gap-2 mb-5 bg-orange-50 px-4 py-1.5 rounded-full border border-orange-100">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
                            </span>
                            <span class="text-xs font-bold text-orange-600 uppercase tracking-widest">My Journal</span>
                        </div>
                        
                        <!-- Title -->
                        <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-4 leading-tight tracking-tight">
                            Express yourself, <span class="text-purple-600">{{ Auth::user()->name }}</span>! 📝
                        </h1>
                        
                        <!-- Quote -->
                        <p class="text-slate-600 font-medium text-base mb-6 leading-relaxed border-l-4 border-purple-100 pl-4 py-1">
                            Writing helps you process emotions and track your mental health journey. Share what's on your mind!
                        </p>
                        
                        <!-- Stats Badge -->
                        <div class="inline-flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-xl text-sm font-medium text-slate-600 border border-slate-100">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            {{ count($journals) }} {{ str('entry')->plural(count($journals)) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-teal-100 border border-teal-200 text-teal-800 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm" role="alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Write New Entry -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-6 border border-slate-100 sticky top-24">
                        <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                            <span>✏️</span> New Entry
                        </h3>
                        
                        <form action="{{ route('journal.store') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-600 mb-1">Title / Topic</label>
                                <input type="text" name="title" id="title" required placeholder="Daily Reflection..." class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition-colors text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">How are you feeling?</label>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $moods = [
                                            ['value' => 'happy', 'emoji' => '😄', 'label' => 'Happy', 'active' => 'peer-checked:bg-yellow-400 peer-checked:text-white peer-checked:border-yellow-500 peer-checked:shadow-lg peer-checked:shadow-yellow-200'],
                                            ['value' => 'calm', 'emoji' => '😌', 'label' => 'Calm', 'active' => 'peer-checked:bg-blue-400 peer-checked:text-white peer-checked:border-blue-500 peer-checked:shadow-lg peer-checked:shadow-blue-200'],
                                            ['value' => 'neutral', 'emoji' => '😐', 'label' => 'Neutral', 'active' => 'peer-checked:bg-slate-400 peer-checked:text-white peer-checked:border-slate-500 peer-checked:shadow-lg peer-checked:shadow-slate-200'],
                                            ['value' => 'sad', 'emoji' => '😢', 'label' => 'Sad', 'active' => 'peer-checked:bg-purple-400 peer-checked:text-white peer-checked:border-purple-500 peer-checked:shadow-lg peer-checked:shadow-purple-200'],
                                            ['value' => 'angry', 'emoji' => '😠', 'label' => 'Angry', 'active' => 'peer-checked:bg-red-400 peer-checked:text-white peer-checked:border-red-500 peer-checked:shadow-lg peer-checked:shadow-red-200'],
                                        ];
                                    @endphp
                                    @foreach($moods as $mood)
                                        <label class="cursor-pointer group relative flex-1 min-w-[3rem]">
                                            <input type="radio" name="mood" value="{{ $mood['value'] }}" class="peer sr-only" 
                                                onclick="this.getAttribute('data-checked') === 'true' ? (this.checked = false, this.setAttribute('data-checked', 'false')) : (document.querySelectorAll('input[name=\'mood\']').forEach(el => el.setAttribute('data-checked', 'false')), this.setAttribute('data-checked', 'true'))"
                                            required>
                                            
                                            <!-- Button Container -->
                                            <div class="h-14 w-full rounded-2xl border-2 border-slate-100 flex items-center justify-center text-3xl bg-white hover:bg-slate-50 transition-all duration-200 transform hover:-translate-y-1 hover:shadow-md {{ $mood['active'] }}">
                                                <span class="filter drop-shadow-sm">{{ $mood['emoji'] }}</span>
                                            </div>

                                            <span class="block text-center text-[10px] text-slate-400 mt-1 font-medium group-hover:text-teal-600 transition-colors capitalize">{{ $mood['value'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div>
                                <label for="content" class="block text-sm font-medium text-slate-600 mb-1">Your Thoughts</label>
                                <textarea name="content" id="content" rows="6" required placeholder="Write whatever you want here..." class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition-colors text-sm resize-none"></textarea>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" class="rounded border-slate-300 text-teal-600 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50">
                                <label for="is_anonymous" class="text-sm text-slate-600 flex items-center gap-1 cursor-pointer">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                    Post Anonymously
                                    <span class="text-xs text-slate-400 ml-1">(Hide my name from teachers)</span>
                                </label>
                            </div>

                            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-md shadow-teal-200 hover:shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Save Entry
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Timeline/Grid -->
                <div class="lg:col-span-2 space-y-6">
                    <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                        <span>📚</span> Your History
                        <span class="ml-auto text-xs font-normal text-slate-500 bg-white px-2 py-1 rounded-full border">Total: {{ count($journals) }}</span>
                    </h3>

                    @if($journals->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($journals as $journal)
                                @php
                                    $moodColors = [
                                        'happy' => 'bg-yellow-50 border-yellow-200 text-yellow-700',
                                        'calm' => 'bg-blue-50 border-blue-200 text-blue-700',
                                        'neutral' => 'bg-slate-50 border-slate-200 text-slate-700',
                                        'sad' => 'bg-purple-50 border-purple-200 text-purple-700',
                                        'angry' => 'bg-red-50 border-red-200 text-red-700',
                                    ];
                                    $moodEmojis = [
                                        'happy' => '😄', 'calm' => '😌', 'neutral' => '😐', 'sad' => '😢', 'angry' => '😠',
                                    ];
                                    $theme = $moodColors[$journal->mood] ?? 'bg-white border-slate-200 text-slate-700';
                                    $emoji = $moodEmojis[$journal->mood] ?? '📝';
                                @endphp

                                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-md transition group relative overflow-hidden">
                                     <!-- Decor header based on mood -->
                                     <div class="absolute top-0 left-0 w-full h-1 {{ explode(' ', $theme)[0] }} {{ explode(' ', $theme)[1] }} border-b"></div>

                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">{{ $emoji }}</span>
                                            <div>
                                                <h4 class="font-bold text-slate-800 line-clamp-1">
                                                    {{ $journal->title }}
                                                    @if($journal->is_anonymous)
                                                        <span class="ml-2 bg-slate-800 text-white text-[10px] px-1.5 py-0.5 rounded uppercase tracking-wider">Anon</span>
                                                    @endif
                                                </h4>
                                                <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">{{ $journal->created_at->format('M d, Y • H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-4">
                                        {{ $journal->content }}
                                    </p>

                                    <div class="flex justify-end items-center pt-3 border-t border-slate-50">
                                         <!-- Delete Form -->
                                         <form action="{{ route('journal.destroy', $journal->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this memory?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-500 text-xs font-semibold px-2 py-1 rounded hover:bg-red-50 transition flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                            <div class="text-4xl mb-3 opacity-30">📭</div>
                            <p class="text-slate-500 font-medium">Your journal is empty.</p>
                            <p class="text-slate-400 text-sm mt-1">Start writing your first entry today!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
