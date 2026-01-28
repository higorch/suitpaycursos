<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
        @livewireStyles
        <link rel="stylesheet" href="{{ mix('assets/css/styles.css') }}">
    </head>
    <body>
        {{ $slot }}

        <script src="{{ mix('assets/js/scripts.js') }}"></script>
        @livewireScripts
    </body>
</html>
