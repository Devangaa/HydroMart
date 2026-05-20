<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HydroMart - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-hydro2.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css'])

    <!--
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media (max-width: 768px) {
            html { font-size: 14px; }
        }
        input::-ms-reveal,
        input::-ms-clear { display: none; }
        [x-cloak] { display: none !important; }
    </style>
    -->

    @stack('styles')
</head>
<body class="bg-gray-50 flex flex-col min-h-screen overflow-x-hidden">

    <div id="app-config" class="hidden" data-base-path="{{ rtrim((string) parse_url(url('/'), PHP_URL_PATH), '/') }}"></div>

    <x-navbar />

    <main class="flex-grow py-10">
        @yield('content')

        @stack('scripts')
    </main>

    <x-footer />

    <!--
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 100,
                disable: 'mobile'
            });
        });
    </script>
    -->

    @vite(['resources/js/app.js'])

</body>
</html>
