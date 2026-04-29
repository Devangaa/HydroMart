<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HydroMart - @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-hydro.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        input::-ms-reveal,
        input::-ms-clear {
            display: none;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <x-navbar />

    <main class="flex-grow flex {{ Route::is('login') || Route::is('register') ? 'items-center justify-center' : '' }} py-10 ">
        @yield('content')
    </main>

    <x-footer />

</body>
</html>