<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/jpg" sizes="32x32" href="{{ asset('assets/images/favicon.jpg') }}">
    <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ mix('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/icons/line-awesome/css/line-awesome.min.css') }}">
</head>

<body class="bg-[#F5F7F9] text-[#111827] antialiased flex flex-col min-h-screen">

    <livewire:global.header />

    <main class="flex-1 w-full max-w-6xl mx-auto">{{ $slot }}</main>

    <livewire:global.footer />

    <livewire:global.modal-confirm />

    <script src="{{ mix('assets/js/scripts.js') }}"></script>
    @livewireScripts
</body>

</html>