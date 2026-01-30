<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component
{
    public function logout()
    {
        Auth::logout(); // desloga o usuário

        request()->session()->invalidate(); // invalida a sessão
        request()->session()->regenerateToken(); // previne CSRF antigo

        return redirect()->route('auth.login');
    }
};
?>

<header x-data="{ open:false }" class="bg-white/80 backdrop-blur border-b border-[#E5E7EB] sticky top-0 z-9999">
    
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        
        <div class="flex items-center gap-10">
            <a href="#" @click.prevent="open=!open" class="md:hidden text-2xl text-gray-600 hover:text-[#33CC33] transition">
                <i :class="open ? 'la la-times' : 'la la-bars'"></i>
            </a>

            <img src="{{ asset('assets/images/suitpaycursos.png') }}" class="h-9 w-auto">

            <nav class="hidden md:flex items-center gap-10 text-sm font-medium">
                @if(request()->routeIs('student.*'))
                    <a href="{{ route('student.catalogs.index') }}" wire:navigate class="flex items-center gap-2 text-gray-500 hover:text-[#33CC33] transition">
                        <i class="las la-boxes text-lg"></i> Catálogo
                    </a>
                @else
                    <a href="{{ route('panel.dashboard.index') }}" wire:navigate class="flex items-center gap-2 text-gray-500 hover:text-[#33CC33] transition">
                        <i class="las la-tachometer-alt text-lg"></i> Dashboard
                    </a>
                @endif

                @if (request()->routeIs('student.*'))
                    <a href="{{ route('student.courses.index') }}" wire:navigate class="flex items-center gap-2 text-gray-500 hover:text-[#33CC33] transition">
                        <i class="la la-book text-lg"></i> Meus Cursos
                    </a>
                @endif

                @if (request()->routeIs('panel.*'))
                    @canany(['admin', 'teacher'])
                        <div x-data="dropdown('bottom-start', 'absolute', 10)" @click.outside="open=false" class="relative">
                            <a href="#" @click.prevent="open = !open" x-ref="referenceDropdown" class="flex items-center gap-1 text-gray-500 hover:text-[#33CC33] transition">
                                <i class="la la-database text-lg"></i> Cadastros
                                <i class="la la-angle-down text-sm opacity-70"></i>
                            </a>
                            <div x-ref="floatingDropdown" class="flex-col gap-1 w-52 p-2 absolute rounded-xl shadow-lg border border-[#E5E7EB] bg-white hidden" :class="{'flex': open, 'hidden': !open}">
                                @can('admin')
                                    <a href="{{ route('panel.users.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg">
                                        <i class="la la-user"></i> Usuários
                                    </a>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                @endcan
                                <a href="{{ route('panel.students.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg">
                                    <i class="la la-user-graduate"></i> Alunos
                                </a>
                                <div class="h-px bg-gray-100 my-1"></div>
                                <a href="{{ route('panel.courses.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg">
                                    <i class="la la-book-open"></i> Cursos
                                </a>
                            </div>
                        </div>
                    @endcanany
                @endif
            </nav>
        </div>

        <div class="flex items-center gap-4">
            @php
                $user = auth()->user();
                $showCreator = request()->routeIs('student.*') && in_array($user->role, ['admin','teacher']);
                $showStudent = request()->routeIs('panel.*');
            @endphp

            @if($showCreator || $showStudent)
                <div class="flex items-center bg-gray-100 border border-gray-200 rounded-lg overflow-hidden text-sm">

                    @if($showCreator)
                        <a href="{{ route('panel.profile.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-1.5 text-gray-600 hover:bg-white hover:text-green-600 transition">
                            <i class="la la-book-open text-base"></i>
                            <span class="hidden sm:inline">Modo Criador</span>
                        </a>
                    @endif

                    @if($showStudent)
                        <a href="{{ route('student.profile.index') }}" wire:navigate class="flex items-center gap-2 px-3 py-1.5 text-gray-600 hover:bg-white hover:text-green-600 transition border-l border-gray-200">
                            <i class="la la-user-graduate text-base"></i>
                            <span class="hidden sm:inline">Modo Aluno</span>
                        </a>
                    @endif

                    <a href="#" wire:click.prevent="logout" class="flex items-center gap-2 px-3 py-1.5 text-gray-600 hover:bg-white hover:text-red-500 transition border-l border-gray-200">
                        <i class="la la-sign-out-alt text-base"></i>
                        <span class="hidden sm:inline">Sair</span>
                    </a>
                </div>
            @else
                <a href="#" wire:click.prevent="logout" class="text-sm text-gray-400 hover:text-red-500 transition font-medium flex items-center gap-2">
                    <i class="la la-sign-out-alt text-lg"></i>
                    <span class="hidden sm:inline">Sair</span>
                </a>
            @endif

            <a href="{{ request()->routeIs('student.*') ? route('student.profile.index') : route('panel.profile.index') }}" wire:navigate class="flex items-center gap-3 hover:opacity-90 transition">
                <span class="hidden sm:block text-sm text-gray-600 font-medium">{{ auth()->user()->name ?? 'Usuário' }}</span>
                <div class="w-9 h-9 rounded-full bg-linear-to-br from-[#33CC33] to-[#2CAA2C] flex items-center justify-center text-white font-bold shadow">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
            </a>
        </div>
    </div>

    {{-- MOBILE MENU --}}
    <div x-show="open" x-transition class="md:hidden fixed top-16 left-0 w-full bg-white border-t border-[#E5E7EB] shadow-xl z-40 px-6 py-4 space-y-4" style="display:none;">

        <div class="space-y-2">
            @if(request()->routeIs('student.*'))
                <a href="{{ route('student.catalogs.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                    <i class="las la-boxes text-lg"></i> Catálogo
                </a>
                <a href="{{ route('student.courses.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                    <i class="la la-book text-lg"></i> Meus Cursos
                </a>
            @else
                <a href="{{ route('panel.dashboard.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                    <i class="las la-tachometer-alt text-lg"></i> Dashboard
                </a>
            @endif
        </div>

        @canany(['admin','teacher'])
            @if(request()->routeIs('panel.*'))
                <div class="border-t border-gray-200 pt-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Cadastros</p>
                    <div class="space-y-2">
                        <a href="{{ route('panel.users.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                            <i class="la la-user text-lg"></i> Usuários
                        </a>
                        <a href="{{ route('panel.students.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                            <i class="la la-user-graduate text-lg"></i> Alunos
                        </a>
                        <a href="{{ route('panel.courses.index') }}" wire:navigate class="flex items-center gap-3 py-2 text-gray-600 hover:text-[#33CC33]">
                            <i class="la la-book-open text-lg"></i> Cursos
                        </a>
                    </div>
                </div>
            @endif
        @endcanany

        @if($showCreator || $showStudent)
            <div class="border-t border-gray-200 pt-4">
                <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">Conta</p>
                <div class="flex flex-col rounded-lg border border-gray-200 overflow-hidden divide-y divide-gray-200 bg-gray-50">
                    @if($showCreator)
                        <a href="{{ route('panel.profile.index') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-white hover:text-green-600 transition">
                            <i class="la la-book-open text-lg"></i> Modo Criador
                        </a>
                    @endif
                    @if($showStudent)
                        <a href="{{ route('student.profile.index') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-white hover:text-green-600 transition">
                            <i class="la la-user-graduate text-lg"></i> Modo Aluno
                        </a>
                    @endif
                    <a href="#" wire:click.prevent="logout" class="flex items-center gap-3 px-3 py-2 text-gray-700 hover:bg-white hover:text-red-500 transition">
                        <i class="la la-sign-out-alt text-lg"></i> Sair
                    </a>
                </div>
            </div>
        @else
            <div class="border-t border-gray-200 pt-4">
                <a href="#" wire:click.prevent="logout" class="flex items-center gap-3 py-2 text-gray-600 hover:text-red-500">
                    <i class="la la-sign-out-alt text-lg"></i> Sair
                </a>
            </div>
        @endif

    </div>

</header>
