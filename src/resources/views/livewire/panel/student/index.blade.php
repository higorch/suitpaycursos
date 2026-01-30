<div x-data="indexStudents" x-bind="events" class="flex flex-col gap-10 pt-14 pb-16 px-6">

    <!-- Page Header -->
    <section wire:key="header" class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">Alunos</h1>

        <div class="w-full lg:w-auto flex flex-col gap-3 lg:flex-row lg:items-center lg:gap-3">

            <!-- Select -->
            <div class="w-full lg:w-56">
                <select class="h-11 w-full" x-data="choices($wire.entangle('perPage').live, 'Por página', '', 'auto', false)">
                    <option value="5">5 por página</option>
                    <option value="10">10 por página</option>
                    <option value="15">15 por página</option>
                    <option value="25">25 por página</option>
                    <option value="50">50 por página</option>
                    <option value="100">100 por página</option>
                </select>
            </div>

            <!-- Ações -->
            <div class="flex w-full lg:w-auto gap-3">

                <!-- GRUPO FILTRO -->
                <div class="flex w-full lg:w-auto">
                    @if(collect($search)->filter()->isNotEmpty())
                    <a href="#" wire:click.prevent="$set('search', [])" class="h-11 w-1/2 lg:w-11 inline-flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-l-lg shadow-sm transition">
                        <i class="la la-times text-lg"></i>
                    </a>
                    @endif
                    <a href="#" @click.prevent="$dispatch('open-modal-filter', {fields: @js($search)})" class="h-11 {{ collect($search)->filter()->isNotEmpty() ? 'w-1/2 lg:w-11 rounded-r-lg' : 'w-full lg:w-11 rounded-lg' }} inline-flex items-center justify-center bg-[#272b2c] hover:bg-[#1f2324] text-white shadow-sm transition">
                        <i class="la la-search text-lg"></i>
                    </a>
                </div>

                <!-- NOVO -->
                <a href="{{ route('panel.students.save') }}" wire:navigate class="h-11 w-full lg:w-11 inline-flex items-center justify-center bg-[#2CAA2C] hover:bg-[#259C25] text-white rounded-lg shadow-sm transition">
                    <i class="la la-plus text-lg"></i>
                </a>

            </div>
        </div>

    </section>

    <!-- Page Content -->
    <section wire:key="content">
        @if ($students->isEmpty())
        <div class="rounded-xl border border-[#E5E7EB] bg-[#F9FAFB] px-6 py-8 text-center">
            <p class="text-sm text-gray-500">Nenhum aluno cadastrado.</p>
        </div>
        @else

        <div wire:key="table" class="overflow-x-hidden rounded-xl border border-[#E5E7EB] bg-white" x-data="scrollbar" wire:ignore.self>
            <table class="table-primary table-fixed">
                <thead>
                    <tr>
                        <th class="sticky left-0">Nome</th>
                        <th>Status</th>
                        <th>E-mail</th>
                        <th>CPF/CNPJ</th>
                        <th class="sticky right-0 w-20"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                    @php
                    $user = auth()->user();
                    $isOwner = $user->role === 'admin' || $student->creator_id === $user->id;
                    @endphp
                    <tr wire:key="student-{{ $student->ulid }}">
                        <td class="sticky left-0 font-medium text-[#111827]">{{ $student->name }}</td>
                        <td>
                            @if($student->status === 'activated')
                            <span class="badge badge-green w-full flex items-center justify-center">Ativo</span>
                            @else
                            <span class="badge badge-red w-full flex items-center justify-center">Inativo</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap">{{ $student->email }}</td>
                        <td class="whitespace-nowrap">{{ $student->cpf_cnpj ? maskFormat('cpf_cnpj', $student->cpf_cnpj) : 'N/A' }}</td>
                        <td class="sticky right-0 w-20 text-center">
                            @if($isOwner)
                            <div x-data="dropdown('left-start','absolute',10)" @click.outside="open=false" class="relative z-20">
                                <a x-ref="referenceDropdown" href="#" class="flex items-center justify-center w-9 h-9 text-gray-500 hover:text-[#33CC33] transition" @click.prevent="open=!open">
                                    <i class="las la-ellipsis-v text-lg"></i>
                                </a>
                                <div x-ref="floatingDropdown" :class="{'flex': open, 'hidden': !open}" class="flex-col gap-1 w-40 p-2 absolute rounded-xl shadow-lg border border-[#E5E7EB] bg-white hidden">
                                    <a wire:navigate href="{{ route('panel.students.edit', $student->ulid) }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-[#F3F4F6] hover:text-[#33CC33] rounded-lg transition">
                                        <i class="las la-pen"></i>Editar
                                    </a>
                                    <div class="h-px bg-gray-100 my-1"></div>
                                    <a href="#"
                                        @click.prevent="$dispatch('run-modal-confirm', { action: 'delete', context: 'student', id: @js($student->ulid), name: @js($student->name.' - '.$student->email), msg: @js('Isso excluirá todos os vínculos. A ação é irreversível.') })"
                                        class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg transition">
                                        <i class="las la-trash"></i>Excluir
                                    </a>
                                </div>
                            </div>
                            @else
                            <a href="#" class="flex items-center justify-center w-9 h-9 cursor-no-drop text-gray-400">
                                <i class="las la-ellipsis-v text-lg"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($students->hasPages())
        <div class="w-full mt-3 py-3 border-t border-dashed border-[#E5E7EB]">
            {{ $students->links('layouts.pagination') }}
        </div>
        @endif

        @endif
    </section>

    @teleport('body')
    <div>
        <livewire:panel.student.modal-filter />
    </div>
    @endteleport
</div>

@script
<script>
    Alpine.data('indexStudents', () => ({
        search: $wire.entangle('search'),
        events: {
            ['@open-modal-filter.window']() {
                this.$dispatch('open-modal', {
                    ref: 'modal-filter'
                });
                this.$dispatch('run-modal-filter', {
                    fields: this.search
                });
            }
        },
        init() {}
    }));
</script>
@endscript