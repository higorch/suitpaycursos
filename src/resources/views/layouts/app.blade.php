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

<body class="bg-[#F5F7F9] text-[#111827] antialiased flex flex-col min-h-screen">

    <header x-data="{ open:false }" class="bg-white/80 backdrop-blur border-b border-[#E5E7EB] sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">

            <div class="flex items-center gap-10">
                <a href="#" @click.prevent="open=!open" class="md:hidden text-2xl text-gray-600 hover:text-[#33CC33] transition"><i :class="open ? 'la la-times' : 'la la-bars'"></i></a>
                <img src="{{ asset('assets/images/suitpaycursos.png') }}" class="h-9 w-auto">

                <nav class="hidden md:flex items-center gap-10 text-sm font-medium">

                    <a href="#" class="flex items-center gap-2 text-gray-500 hover:text-[#33CC33] transition">
                        <i class="las la-tachometer-alt text-lg"></i>Dashboard
                    </a>

                    <a href="#" class="flex items-center gap-2 text-gray-500 hover:text-[#33CC33] transition">
                        <i class="la la-book text-lg"></i>Meus Cursos
                    </a>

                    <div x-data="dropdown('bottom-start', 'absolute', 10)" @click.outside="open=false" class="relative">
                        <a href="#" @click.prevent="open=!open" x-ref="referenceDropdown" class="flex items-center gap-1 text-gray-500 hover:text-[#33CC33] transition">
                            <i class="la la-database text-lg"></i>Cadastros
                            <i class="la la-angle-down text-sm opacity-70"></i>
                        </a>

                        <div x-ref="floatingDropdown" class="flex-col gap-1 w-52 p-2 absolute rounded-xl shadow-lg border border-[#E5E7EB] bg-white hidden" :class="{'flex': open, 'hidden': !open}">
                            <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg">
                                <i class="la la-user-graduate"></i>Alunos
                            </a>
                            <a href="#" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg">
                                <i class="la la-book-open"></i>Cursos
                            </a>
                        </div>
                    </div>

                </nav>
            </div>

            <div class="flex items-center gap-5">
                <a href="#" class="text-sm text-gray-400 hover:text-red-500 transition font-medium pr-5 border-r border-gray-200 flex items-center gap-2">
                    <i class="la la-sign-out-alt text-lg"></i>Sair
                </a>

                <a href="#" class="flex items-center gap-3 pl-2 hover:opacity-90 transition">
                    <span class="hidden sm:block text-sm text-gray-600 font-medium">{{ auth()->user()->name ?? 'Usuário' }}</span>
                    <div class="w-9 h-9 rounded-full bg-linear-to-br from-[#33CC33] to-[#2CAA2C] flex items-center justify-center text-white font-bold shadow">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </a>
            </div>

        </div>

        <div x-show="open" x-transition class="md:hidden fixed top-16 left-0 w-full bg-white border-t border-[#E5E7EB] shadow-xl z-40 px-6 py-4 space-y-3" style="display:none;">
            <a href="#" class="flex items-center gap-2 py-2 text-gray-600 hover:text-[#33CC33]"><i class="la la-chart-line"></i>Dashboard</a>
            <a href="#" class="flex items-center gap-2 py-2 text-gray-600 hover:text-[#33CC33]"><i class="la la-book"></i>Meus Cursos</a>
            <div class="border-t border-gray-200 pt-3">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Cadastros</p>
                <a href="#" class="flex items-center gap-2 py-2 text-gray-600 hover:text-[#33CC33]"><i class="la la-user-graduate"></i>Alunos</a>
                <a href="#" class="flex items-center gap-2 py-2 text-gray-600 hover:text-[#33CC33]"><i class="la la-book-open"></i>Cursos</a>
            </div>
        </div>
    </header>

    <main class="flex-1 w-full max-w-6xl mx-auto">{{ $slot }}</main>

    <footer class="bg-white border-t border-[#E5E7EB]">
        <div class="max-w-6xl mx-auto px-6 py-8 text-center text-xs text-gray-400">
            © {{ date('Y') }} SuitPay Cursos. Todos os direitos reservados.
        </div>
    </footer>

    <script src="{{ mix('assets/js/scripts.js') }}"></script>
    @livewireScripts
</body>

</html>