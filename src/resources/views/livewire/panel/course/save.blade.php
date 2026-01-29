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
                <a href="{{ route('panel.courses.index') }}" wire:navigate class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-[#E5E7EB] text-gray-500 hover:bg-gray-50 hover:text-[#2CAA2C] transition">
                    <i class="la la-arrow-left text-lg"></i>
                </a>
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
                        Dados Basicos
                    </h2>
                </div>

                <!-- NOME DO CURSO -->
                <div class="relative col-span-12 md:col-span-12 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome do curso</label>
                    <input type="text" wire:model="form.name" class="input-basic">
                    @error('form.name') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- DESCRIÇÃO -->
                <div class="relative col-span-12 md:col-span-12 flex flex-col gap-2">
                    <label class="label-input-basic">Descrição</label>
                    <textarea wire:model.live="form.description" class="input-basic resize-none h-35" placeholder="Conte aos alunos o que eles vão aprender neste curso..."></textarea>
                    @error('form.description') <span @mouseover="$el.remove()" class="input-error full label h-35">{{ $message }}</span> @enderror
                </div>

                <!-- MODALIDADE -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Modalidade</label>
                    <select x-data="choices($wire.entangle('form.delivery_mode'), '---', '', 'auto', false)">
                        <option value="online">On-Line</option>
                        <option value="in-person">Presencial</option>
                        <option value="hybrid">Híbrido</option>
                    </select>
                    @error('form.delivery_mode') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- STATUS -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</label>
                    <select x-data="choices($wire.entangle('form.status'), '---', '', 'auto', false)">
                        <option value="activated">Ativo</option>
                        <option value="disabled">Desativado</option>
                    </select>
                    @error('form.status') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- DATA LIMITE MATRICULA -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Data de limite matrícula</label>
                    <input type="text" wire:model="form.enrollment_deadline" class="input-basic" placeholder="__/__/____" x-data="{
                            init() {
                                const today = new Date();
                                const maxBirthDate = new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1);

                                flatpickr(this.$el, {
                                    locale: 'pt',
                                    dateFormat: 'd/m/Y',
                                    maxDate: maxBirthDate,
                                    allowInput: true,
                                    defaultDate: $wire.form.enrollment_deadline ?? null,
                                    onChange: (dates) => {
                                        if (dates.length) {
                                            const d = dates[0];
                                            $wire.form.enrollment_deadline = d.toLocaleDateString('pt-BR');
                                        }
                                    }
                                });
                            }
                       }">
                    @error('form.enrollment_deadline') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- NUMERO MAXIMO DE MATRICULAS ABERTAS -->
                <div class="relative col-span-12 md:col-span-3 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nº máx. de matrículas abertas</label>
                    <input type="text" wire:model="form.max_enrollments" class="input-basic" x-data="mask" data-inputmask="'alias': 'numeric', 'digits': 0, 'rightAlign': false, 'allowMinus': false">
                    @error('form.max_enrollments') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- ===== SEGURANÇA ===== -->
                <div class="col-span-12 mt-6">
                    <h2 class="text-xs tracking-widest uppercase text-gray-400 border-b border-[#E5E7EB] pb-2">
                        INFORMAÇÕES DE APRESENTAÇÃO
                    </h2>
                </div>

                <!-- SLUG DO CURSO -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="label-input-basic">Endereço URI (Slug)</label>
                    <input type="text" class="input-basic" wire:model="form.slug" x-data="{
                            isEditing: $wire.entangle('form.id'),
                            name: $wire.entangle('form.name'),
                            slug: $wire.entangle('form.slug'),
                            sanitize(v) {
                                return (v || '')
                                    .toLowerCase()
                                    .normalize('NFD').replace(/[\u0300-\u036f]/g,'')
                                    .replace(/[^a-z0-9\s-]/g,'')
                                    .replace(/\s+/g,'-')
                                    .replace(/-+/g,'-')
                                    .replace(/^-+/,'');
                            },
                            init() {
                                this.$watch('name', value => {
                                    if (!this.isEditing) {
                                        this.slug = this.sanitize(value);
                                    }
                                });
                                this.$watch('slug', value => {
                                    let clean = this.sanitize(value);
                                    if (clean !== value) this.slug = clean;
                                });
                            }
                        }"
                    >
                    @error('form.slug') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

                <!-- URL VIDEO APRESENTAÇÃO -->
                <div class="relative col-span-12 md:col-span-6 flex flex-col gap-2">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wider">URL Video apresentação (youtube, vimeo)</label>
                    <input type="text" wire:model="form.presentation_video_url" class="input-basic" placeholder="ex: https://www.youtube.com/watch?v=abcd1234">
                    @error('form.presentation_video_url') <span @mouseover="$el.remove()" class="input-error full label">{{ $message }}</span> @enderror
                </div>

            </div>

        </div>

    </section>

    <!-- Bottom Actions -->
    <section>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="{{ route('panel.courses.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 border border-[#E5E7EB] text-gray-600 hover:bg-gray-50 text-sm px-6 py-2.5 rounded-lg font-semibold w-full sm:w-auto hover:text-[#2CAA2C] transition">
                <i class="la la-arrow-left text-lg"></i> Voltar
            </a>
            <a href="#" wire:click.prevent="submit" class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                <i class="la la-save text-lg"></i>Salvar
            </a>
        </div>

    </section>

</div>