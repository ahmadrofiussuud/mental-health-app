<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MindCare - Platform Kesehatan Mental Siswa</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>
    </head>
    <body class="antialiased text-gray-800">
        <div class="relative min-h-screen flex flex-col">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/campus-life.png') }}" alt="Campus Life" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
            </div>

            <!-- Header -->
            <div class="relative z-10 w-full max-w-7xl mx-auto px-6">
                <header class="flex items-center justify-between py-8">
                    <div class="flex items-center gap-2">
                        <!-- Logo Placeholder -->
                        <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">M</div>
                        <h1 class="text-2xl font-bold text-white tracking-wide">MindCare</h1>
                    </div>
                    <nav class="flex gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-full hover:bg-teal-700 transition duration-300 shadow-md backdrop-blur-sm bg-opacity-90">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium text-gray-200 hover:text-white transition duration-300">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-teal-600 rounded-full hover:bg-teal-700 transition duration-300 shadow-lg shadow-teal-500/30">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </nav>
                </header>
            </div>

            <!-- Hero Content -->
            <main class="relative z-10 flex-grow flex items-center w-full max-w-7xl mx-auto px-6">
                <div class="max-w-2xl pb-20">
                    <div class="inline-flex items-center px-3 py-1 rounded-full border border-teal-400/30 bg-teal-900/30 backdrop-blur-md mb-6">
                        <span class="flex h-2 w-2 rounded-full bg-teal-400 mr-2 animate-pulse"></span>
                        <span class="text-xs font-medium text-teal-100 uppercase tracking-wider">Kesehatan Mental Siswa Prioritas Kami</span>
                    </div>
                    
                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6 drop-shadow-sm">
                        Ruang Aman untuk <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">Pertumbuhan Mental</span>
                    </h1>
                    
                    <p class="text-lg md:text-xl text-gray-200 mb-10 leading-relaxed max-w-lg">
                        MindCare membantu Anda memahami emosi dan menyediakan saluran aman untuk berkomunikasi dengan guru BK Anda. Anda tidak sendirian dalam perjalanan ini.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('mood.check') }}" class="group relative px-8 py-4 bg-teal-600 text-white font-semibold rounded-xl shadow-lg shadow-teal-500/40 hover:bg-teal-700 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                            <div class="relative z-10 flex items-center justify-center gap-2">
                                <span>Mulai Check-in Harian</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </a>
                        
                        <a href="#" class="px-8 py-4 bg-white/10 text-white font-medium rounded-xl border border-white/20 backdrop-blur-sm hover:bg-white/20 transition-all duration-300 flex items-center justify-center hover:border-white/40">
                            Pelajari Panduan
                        </a>
                    </div>

                    <!-- Stats / Trust Indicators (Optional) -->
                    <div class="mt-12 flex items-center gap-8 border-t border-white/10 pt-8">
                        <div>
                            <p class="text-3xl font-bold text-white">500+</p>
                            <p class="text-sm text-gray-400">Siswa Terbantu</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-white">24/7</p>
                            <p class="text-sm text-gray-400">Akses Konseling</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-white">100%</p>
                            <p class="text-sm text-gray-400">Privasi Terjaga</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>
