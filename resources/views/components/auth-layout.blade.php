<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>
        <link rel="icon" href="{{ asset('images/clean n ideas logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">

        <!-- Emoji Picker library -->
        <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

        <!-- Scripts -->
        @vite('resources/css/pages/auth.css')
        @vite('resources/js/modules/pageRefreshErrorToastAuth.js')
    </head>
    <body>
        <main>
            {{ $slot }}
        </main>
        <x-footer></x-footer>
        <x-popup-overlay>
            @stack('popup')
        </x-popup-overlay>
    </body>
</html>