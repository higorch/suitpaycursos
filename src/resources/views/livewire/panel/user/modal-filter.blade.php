<div wire:ignore.self class="fixed inset-0 overflow-y-auto bg-black/60 invisible" x-data="modal('modal-filter')" :class="{'visible': open, 'invisible': !open}" x-bind="events">
    <div class="min-h-screen">
        <div x-data="modalFilter" x-bind="events" class="flex flex-col fixed transition-all duration-200 w-full md:w-4/12 h-screen shadow-lg bg-white" :class="{'right-0 opacity-100': open, '-right-full opacity-0': !open}">

            <span class="absolute top-6 right-6 text-lg cursor-pointer text-gray-400 hover:text-red-500" @click.prevent="open = false">
                <i class="las la-times"></i>
            </span>

            <!-- HEADER -->
            <div class="flex items-center w-full px-6 py-5 border-b border-[#E5E7EB]">
                <p class="font-medium text-base md:text-xl text-[#111827]">Filtrar usuários</p>
            </div>

            <!-- BODY -->
            <div class="flex flex-col grow p-6 overflow-y-auto bg-[#F9FAFB]">
                <div class="grid grid-cols-12 gap-6">

                    <!-- STATUS -->
                    <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                        <label class="label-input-basic">Status</label>
                        <select x-data="choices($wire.entangle('fields.status'), 'Todos', '', 'auto', true)">
                            <option value="">Todos</option>
                            <option value="activated">Ativo</option>
                            <option value="disabled">Inativo</option>
                        </select>
                    </div>

                    <!-- TIPO -->
                    <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                        <label class="label-input-basic">Tipo de usuário</label>
                        <select x-data="choices($wire.entangle('fields.role'), 'Todos', '', 'auto', true)">
                            <option value="">Todos</option>
                            <option value="admin">Administrador</option>
                            <option value="creator">Criador</option>
                            <option value="student">Aluno</option>
                        </select>
                    </div>

                    <!-- NOME -->
                    <div class="relative col-span-12 flex flex-col gap-2">
                        <label class="label-input-basic">Nome</label>
                        <input type="text" wire:model.defer="fields.name" class="input-basic">
                    </div>

                    <!-- EMAIL -->
                    <div class="relative col-span-12 flex flex-col gap-2">
                        <label class="label-input-basic">E-mail</label>
                        <input type="text" wire:model.defer="fields.email" class="input-basic">
                    </div>

                    <!-- CPF / CNPJ -->
                    <div class="relative col-span-12 flex flex-col gap-2">
                        <label class="label-input-basic">CPF / CNPJ</label>
                        <input type="text" wire:model="fields.cpf_cnpj" class="input-basic" x-data="mask" data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99'], 'keepStatic': true">
                    </div>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="flex justify-around items-center gap-6 w-full px-6 py-5 border-t border-[#E5E7EB] bg-white">
                <a href="#" @click.prevent="() => {
                    $wire.submit();
                    open = false;    
                }" class="inline-flex flex-1 items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition">
                    <i class="las la-filter text-lg"></i>
                    <span class="hidden md:block text-sm">Filtrar</span>
                </a>
                <a href="#" @click.prevent="open = false" class="inline-flex flex-1 items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition">
                    <i class="las la-times text-lg"></i>
                    <span class="hidden md:block text-sm">Fechar</span>
                </a>
            </div>

        </div>
    </div>
</div>

@script
<script>
    Alpine.data('modalFilter', () => ({
        fields: $wire.entangle('fields'),
        init() {}
    }));
</script>
@endscript