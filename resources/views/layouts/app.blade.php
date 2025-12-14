<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FlexSport - E-Commerce Olahraga')</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @if(auth()->check() && auth()->user()->isAdmin())
        @include('components.header-admin')
    @else
        @include('components.header-buyer')
    @endif
    
    <main>
        @yield('content')
    </main>
    
    @include('components.footer')
    
    <script src="{{ asset('js/chatbot.js') }}"></script>
    @stack('scripts')
</body>
</html>