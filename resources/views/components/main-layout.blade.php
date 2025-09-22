<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Clean n Ideas' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>
    <body>
        <x-navigation></x-navigation>
        <x-sidebar></x-sidebar>
        @if(Auth::check())
            <x-navbar-extension></x-navbar-extension>
        @endif
        <main>
            {{ $slot }}
        </main>
        <footer>Footer</footer>
        <x-popup-overlay>
            @stack('popup')
        </x-popup-overlay>
    </body>
</html>