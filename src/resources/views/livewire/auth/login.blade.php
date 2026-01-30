<div class="bg-white border border-[#E5E7EB] rounded-2xl shadow-sm p-8 space-y-6">

    <div class="text-center flex flex-col gap-1">
        <h1 class="text-2xl font-semibold text-[#111827]">Acesso à Plataforma</h1>
        <p class="text-sm text-gray-500">Entre com suas credenciais para continuar</p>
    </div>

    <form wire:submit.prevent="login" class="space-y-5">

        <!-- Email -->
        <div class="relative flex flex-col gap-1">
            <label class="label-input-basic">E-mail</label>
            <input type="email" wire:model.defer="email" required class="input-basic" placeholder="seu@email.com">
            @error('email') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
        </div>

        <!-- Senha -->
        <div class="relative flex flex-col gap-1" x-data="{ show:false }">
            <label class="label-input-basic">Senha</label>
            <div class="relative">
                <input :type="show ? 'text':'password'" wire:model="password" class="input-basic pr-10">
                <a href="#" @click.prevent="show=!show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2CAA2C] transition">
                    <i :class="show ? 'la la-eye-slash' : 'la la-eye'"></i>
                </a>
            </div>
            @error('password') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
        </div>

        <!-- Lembrar + Esqueci -->
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
                <input type="checkbox" wire:model="remember" class="w-4 h-4 border-gray-300 rounded text-[#2CAA2C] focus:ring-[#2CAA2C]">
                Lembrar de mim
            </label>

            <a href="#" class="text-[#2CAA2C] hover:text-[#259C25] font-medium">
                Esqueci a senha
            </a>
        </div>

        <!-- Botão -->
        <a href="#" wire:click.prevent="submit" class="block text-center w-full bg-[#2CAA2C] hover:bg-[#259C25] text-white py-3 rounded-lg font-semibold text-sm shadow-sm transition">
            Entrar
        </a>

    </form>

</div>