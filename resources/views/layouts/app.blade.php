<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        {{-- Global cinematic event background (realistis + aman/performance) --}}
        <div id="event-ambient" aria-hidden="true" class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-950 via-slate-950 to-purple-950 opacity-90"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(99,102,241,0.35),transparent_55%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom,rgba(168,85,247,0.25),transparent_60%)]"></div>
            <div class="absolute inset-0 event-noise"></div>
            <div class="absolute inset-0 event-shimmer"></div>
            <canvas id="event-particles" class="absolute inset-0 w-full h-full opacity-80"></canvas>
        </div>

        <div class="relative z-10 min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/90 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="event-content">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
