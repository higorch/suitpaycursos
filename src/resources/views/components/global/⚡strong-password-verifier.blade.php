<?php

use Livewire\Attributes\Modelable;
use Livewire\Component;

new class extends Component
{
    #[Modelable]
    public $password = '';
};
?>

{{-- SEGURANÇA DA SENHA --}}
<div x-data="{
    get password() { return $wire.password || '' },
    get hasMin() { return this.password.length >= 8 },
    get hasLower() { return /[a-z]/.test(this.password) },
    get hasUpper() { return /[A-Z]/.test(this.password) },
    get hasNumber() { return /[0-9]/.test(this.password) },
    get hasSpecial() { return /[@$!%*#?&]/.test(this.password) },
    get valid() { return this.hasMin && this.hasLower && this.hasUpper && this.hasNumber && this.hasSpecial }
}" class="col-span-12 flex flex-col gap-4 rounded-xl p-5 border border-[#E5E7EB] bg-[#F9FAFB] shadow-sm">
    <div class="flex justify-between items-center gap-2">
        <h4 class="font-semibold text-xs tracking-widest text-gray-600 uppercase">Segurança da senha</h4>
        <div x-show="valid" class="font-bold text-[11px] tracking-widest uppercase text-[#2CAA2C]">Senha forte</div>
    </div>
    <div class="flex flex-col gap-2 text-sm">
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Mínimo de 8 caracteres</span>
            <span class="size-2.5 rounded-full transition" :class="hasMin ? 'bg-[#2CAA2C]' : 'bg-gray-300'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Letra minúscula</span>
            <span class="size-2.5 rounded-full transition" :class="hasLower ? 'bg-[#2CAA2C]' : 'bg-gray-300'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Letra maiúscula</span>
            <span class="size-2.5 rounded-full transition" :class="hasUpper ? 'bg-[#2CAA2C]' : 'bg-gray-300'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">Pelo menos 1 número</span>
            <span class="size-2.5 rounded-full transition" :class="hasNumber ? 'bg-[#2CAA2C]' : 'bg-gray-300'"></span>
        </div>
        <div class="flex items-center justify-between">
            <span class="text-xs text-gray-500">1 caractere especial (@$!%*#?&)</span>
            <span class="size-2.5 rounded-full transition" :class="hasSpecial ? 'bg-[#2CAA2C]' : 'bg-gray-300'"></span>
        </div>
    </div>
</div>