<?php

use App\Livewire\Forms\ProfileForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public ProfileForm $form;

    public function render()
    {
        return $this->view([
            'pageTitle' => $this->pageTitle,
        ])->title($this->pageTitle);
    }

    public function mount()
    {
        $this->form->edit(Auth::user()->ulid);
    }

    public function rendered()
    {
        $this->dispatch('errors-save-profile', errors: $this->getErrorBag());

        $this->errorToastErrorBag();
        $this->resetErrorBag();
    }

    #[Computed]
    public function pageTitle()
    {
        if (request()->routeIs('student.*')) {
            return 'Meu Perfil (Aluno)';
        }

        if (request()->routeIs('panel.*')) {
            return 'Meu Perfil (Criador)';
        }

        return 'Meu Perfil';
    }

    public function submit()
    {
        $this->validate();

        try {
            session()->flash('success', 'Informações salvas com sucesso.');

            return $this->redirect(url()->previous(), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('notify', msg: 'Não foi possível salvar.', type: 'error');
        }
    }

    private function errorToastErrorBag()
    {
        $errors = $this->getErrorBag();
        $count = count($errors->getMessages());

        if ($count > 0) {
            $this->dispatch('notify', msg: $count === 1 ? __('app.one_filling_problem') : $count . ' ' . __('app.filling_problems'), type: 'error');
        }
    }
};
?>

<div class="flex flex-col gap-10 pt-14 pb-16 px-6">

    @if (session('success'))
    <div class="alert alert-success">
        <div class="alert-content">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <section>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <!-- Title + Back -->
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">{{ $pageTitle }}</h1>
            </div>

            <!-- Top Save -->
            <div class="w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-3 md:justify-end">
                    <a href="#" wire:click.prevent="submit" class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                        <i class="la la-save text-lg"></i>Salvar
                    </a>
                </div>
            </div>

        </div>

    </section>

    <!-- Page Content -->
    <section>

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
                    <label class="label-input-basic">Nome completo</label>
                    <input type="text" wire:model="form.name" class="input-basic">
                    @error('form.name') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- USERNAME -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="label-input-basic">@Usuário</label>
                    <input type="text" class="input-basic" wire:model="form.at" x-data="{
                            isEditing: $wire.entangle('form.ulid'),
                            name: $wire.entangle('form.name'),
                            at: $wire.entangle('form.at'),
                            sanitize(v) {
                                return (v || '')
                                    .toLowerCase()
                                    .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
                                    .replace(/\s+/g,'') // remove espaços
                                    .replace(/[^a-z0-9]/g,''); // só letras e números
                            },
                            init() {
                                this.$watch('name', value => {
                                    if(!this.isEditing){
                                        this.at = this.sanitize(value);
                                    }
                                });
                                this.$watch('at', value => {
                                    let clean = this.sanitize(value);
                                    if (clean !== value) this.at = clean;
                                });
                            }
                        }">
                    @error('form.at') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- EMAIL -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="label-input-basic">E-mail</label>
                    <input type="email" wire:model="form.email" class="input-basic">
                    @error('form.email') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- DATA NASCIMENTO -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="label-input-basic">Data de nascimento</label>
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
                    <label class="label-input-basic">CPF / CNPJ</label>
                    <input type="text" wire:model="form.cpf_cnpj" class="input-basic" x-data="mask" data-inputmask="'mask': ['999.999.999-99', '99.999.999/9999-99'], 'keepStatic': true">
                    @error('form.cpf_cnpj') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- ===== SEGURANÇA ===== -->
                <div class="col-span-12 mt-6">
                    <h2 class="text-xs tracking-widest uppercase text-gray-400 border-b border-[#E5E7EB] pb-2">
                        Acesso à conta
                    </h2>
                </div>

                <!-- SENHA -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2" x-data="{ show:false }">
                    <label class="label-input-basic">Senha</label>
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
                    <label class="label-input-basic">Confirmar senha</label>
                    <div class="relative">
                        <input :type="show ? 'text':'password'" wire:model="form.password_confirmation" class="input-basic pr-10">
                        <a href="#" @click.prevent="show=!show"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#2CAA2C] transition">
                            <i :class="show ? 'la la-eye-slash' : 'la la-eye'"></i>
                        </a>
                    </div>
                    @error('form.password_confirmation') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- SEGURANÇA DA SENHA -->
                <livewire:global.strong-password-verifier wire:model="form.password" />

            </div>

        </div>

    </section>

</div>