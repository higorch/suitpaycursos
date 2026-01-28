<div class="flex flex-col gap-10">

    <!-- Page Header -->
    <section class="px-6 pt-14">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <!-- Title + Back -->
            <div class="flex items-center gap-4">
                <a href="{{ route('panel.users.index') }}" wire:navigate class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-[#E5E7EB] text-gray-500 hover:bg-gray-50 hover:text-[#2CAA2C] transition">
                    <i class="la la-arrow-left text-lg"></i>
                </a>

                <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">
                    Cadastrar Usuário
                </h1>
            </div>

            <!-- Top Save -->
            <div class="w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-3 md:justify-end">
                    <a href="#" class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                        <i class="la la-save"></i>Salvar
                    </a>
                </div>
            </div>

        </div>

    </section>

    <!-- Page Content -->
    <section class="px-6">

        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8">

            <div class="grid grid-cols-12 gap-6">

                <!-- ===== DADOS PESSOAIS ===== -->
                <div class="col-span-12">
                    <h2 class="text-xs tracking-widest uppercase text-gray-400 border-b border-[#E5E7EB] pb-2">
                        Dados pessoais
                    </h2>
                </div>

                <!-- NOME -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome completo</label>
                    <input type="text" wire:model="form.name" class="input-basic">
                    @error('form.name') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- USERNAME -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">@ Usuário</label>
                    <input type="text" wire:model.live.debounce.300ms="form.at" x-on:input="() => { 
                        let v = $event.target.value
                            .toLowerCase()
                            .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
                            .replace(/[^a-z0-9]/g,'');
                        $event.target.value = v;
                        $wire.set('form.at', v);
                    }" class="input-basic">
                    @error('form.at') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- EMAIL -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">E-mail</label>
                    <input type="email" wire:model="form.email" class="input-basic">
                    @error('form.email') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- DATA NASCIMENTO -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Data de nascimento</label>
                    <input type="text" wire:model="form.date_birth" class="input-basic" placeholder="__/__/____" x-data="{
                            init() {
                                const today = new Date();
                                const maxBirthDate = new Date(today.getFullYear()-18, today.getMonth(), today.getDate());

                                flatpickr(this.$el, {
                                    locale: 'pt',
                                    dateFormat: 'd/m/Y',
                                    maxDate: maxBirthDate,
                                    allowInput: true,
                                    defaultDate: $wire.form.date_birth ?? null,
                                    onChange: (dates) => {
                                        if(dates.length) {
                                            const d = dates[0];
                                            $wire.form.date_birth = d.toLocaleDateString('pt-BR');
                                        }
                                    }
                                });
                            }
                       }">
                    @error('form.date_birth') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- CPF / CNPJ -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">CPF / CNPJ</label>
                    <input type="text" wire:model="form.cpf_cnpj" class="input-basic" x-data="mask" data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99'], 'keepStatic': true">
                    @error('form.cpf_cnpj') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- STATUS -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                    <select x-data="choices($wire.entangle('form.status'), '---', '', 'auto', true)">
                        <option value="activated">Ativo</option>
                        <option value="disabled">Desativado</option>
                    </select>
                    @error('form.status') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- ROLE -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipo de usuário</label>
                    <select x-data="choices($wire.entangle('form.role'), '---', '', 'auto', true)">
                        <option value="admin">Adminstrador</option>
                        <option value="teacher">Professor</option>
                        <option value="student">Aluno</option>
                    </select>
                    @error('form.role') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- ===== SEGURANÇA ===== -->
                <div class="col-span-12 mt-6">
                    <h2 class="text-xs tracking-widest uppercase text-gray-400 border-b border-[#E5E7EB] pb-2">
                        Acesso à conta
                    </h2>
                </div>

                <!-- SENHA -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2" x-data="{ show:false }">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Senha</label>
                    <div class="relative">
                        <input :type="show ? 'text':'password'" wire:model="form.password" class="input-basic pr-10">
                        <a href="#" @click.prevent="show=!show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2CAA2C] transition">
                            <i :class="show ? 'la la-eye-slash' : 'la la-eye'"></i>
                        </a>
                    </div>
                    @error('form.password') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- CONFIRMAR SENHA -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2" x-data="{ show:false }">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Confirmar senha</label>
                    <div class="relative">
                        <input :type="show ? 'text':'password'" wire:model="form.password_confirmation" class="input-basic pr-10">
                        <a href="#" @click.prevent="show=!show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2CAA2C] transition">
                            <i :class="show ? 'la la-eye-slash' : 'la la-eye'"></i>
                        </a>
                    </div>
                    @error('form.password_confirmation') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

            </div>

        </div>

    </section>

    <!-- Bottom Actions -->
    <section class="px-6 pb-16">

        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="{{ route('panel.users.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 border border-[#E5E7EB] text-gray-600 hover:bg-gray-50 text-sm px-6 py-2.5 rounded-lg font-semibold transition w-full sm:w-auto">
                <i class="la la-arrow-left"></i>Voltar
            </a>
            <a href="#" class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                <i class="la la-save"></i>Salvar
            </a>
        </div>

    </section>

</div>