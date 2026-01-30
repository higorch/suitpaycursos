<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '' }} - {{ config('app.name') }}</title>

    @livewireStyles
    <link rel="stylesheet" href="{{ mix('assets/css/styles.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/icons/line-awesome/css/line-awesome.min.css') }}">
</head>

<body class="min-h-screen flex items-center justify-center bg-[#F5F7F9] px-6">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ asset('assets/images/suitpaycursos.png') }}" class="h-12 w-auto" alt="SuitPay Cursos">
        </div>

       <div class="p-3">{{ $slot }}</div>

        <!-- Rodapé -->
        <p class="text-center text-xs text-gray-400 mt-8">©{{ date('Y') }} SuitPay Cursos. Todos os direitos reservados.</p>

    </div>

    <script src="{{ mix('assets/js/scripts.js') }}"></script>
    @livewireScripts
</body>
</html>
