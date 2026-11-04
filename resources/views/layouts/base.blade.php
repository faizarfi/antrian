<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Antrean Digital - BPS Kabupaten Karanganyar')</title>
    
    <!-- Favicon Resmi BPS -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/bps-emblem-official.svg') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        [data-lucide] {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
    @stack('styles')
</head>
<body class="h-full text-slate-800 antialiased selection:bg-sky-500 selection:text-white">
    @yield('body')
    
    @stack('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.createIcons) {
                window.createIcons();
            }
        });
    </script>
</body>
</html>

