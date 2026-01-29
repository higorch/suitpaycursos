<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div wire:ignore.self class="fixed inset-0 overflow-y-auto bg-black/60 invisible" x-data="modal('modal-confirm')" :class="{'visible': open, 'invisible': !open}" x-bind="events">
    <div class="flex items-center justify-center min-h-screen p-6" @click.self="open = false">
        <div x-data="modalConfirm" x-bind="events" class="relative w-full max-w-lg rounded-lg shadow-lg bg-white" x-show="open" x-transition>
            <span class="absolute top-6 right-6 text-lg cursor-pointer text-[#6B7280] hover:text-red-500" @click.prevent="open = false"><i class="las la-times"></i></span>
            <div class="flex items-center w-full px-6 py-5 border-b border-[#E5E7EB]">
                <template x-if="action === 'delete'">
                    <p class="leading-relaxed font-normal text-base md:text-xl text-[#111827]">Confirmar exclusão!</p>
                </template>
                <template x-if="action === 'confirm'">
                    <p class="leading-relaxed font-normal text-base md:text-xl text-[#111827]">Confirmar ação!</p>
                </template>
            </div>
            <div class="flex flex-col grow p-6 gap-2">
                <template x-if="action === 'delete'">
                    <div class="flex flex-col gap-2">
                        <p class="text-[#111827] text-base" x-text="msg ?? 'Tem certeza que deseja excluir permanentemente?'"></p>
                        <div class="alert alert-warning">
                            <i class="las la-exclamation-circle text-xl mt-0.5"></i>
                            <p x-text="name"></p>
                        </div>
                    </div>
                </template>
                <template x-if="action === 'confirm'">
                    <div class="flex flex-col gap-2">
                        <p class="text-[#111827] text-base" x-text="msg ?? 'Tem certeza que deseja confirmar a ação?'"></p>
                        <div class="alert alert-info">
                            <i class="las la-info-circle text-xl mt-0.5"></i>
                            <p x-text="name"></p>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex justify-around items-center gap-6 w-full p-6 border-t border-[#E5E7EB]">
                <a href="#" @click.prevent="confirm" class="inline-flex w-full items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition">
                    <i class="las la-check text-lg"></i>
                    <span>Confirmar</span>
                </a>
                <a href="#" @click.prevent="open = false" class="inline-flex w-full items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition">
                    <i class="las la-times text-lg"></i>
                    <span>Cancelar</span>
                </a>
            </div>
        </div>
    </div>
</div>

@script
<script>
    Alpine.data('modalConfirm', () => ({
        action: '',
        context: '',
        id: '',
        name: '',
        msg: '',
        events: {
            ['@run-modal-confirm.window']() {
                this.action = this.$event.detail.action;
                this.context = this.$event.detail.context;
                this.id = this.$event.detail.id;
                this.name = this.$event.detail.name;
                this.msg = this.$event?.detail?.msg ?? null;

                this.$dispatch('open-modal', {
                    ref: "modal-confirm"
                });
            }
        },
        confirm() {
            if (this.action === 'delete') {
                this.$dispatch('action-delete', {
                    context: this.context,
                    id: this.id
                });
            }

            if (this.action === 'confirm') {
                this.$dispatch('action-confirm', {
                    context: this.context,
                    id: this.id
                });
            }

            this.$dispatch('close-modal', {
                ref: "modal-confirm"
            });
        }
    }));
</script>
@endscript