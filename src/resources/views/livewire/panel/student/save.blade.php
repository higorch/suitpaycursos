<div class="flex flex-col gap-10 pt-14 pb-16 px-6">

    <!-- Page Header -->
    <section>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <!-- Title + Back -->
            <div class="flex items-center gap-4">
                <a href="{{ route('panel.students.index') }}" wire:navigate class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-[#E5E7EB] text-gray-500 hover:bg-gray-50 hover:text-[#2CAA2C] transition">
                    <i class="la la-arrow-left text-lg"></i>
                </a>
                <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">Cadastrar Aluno</h1>
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

        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8"></div>

    </section>

    <!-- Bottom Actions -->
    <section>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="{{ route('panel.students.index') }}" wire:navigate class="inline-flex items-center justify-center gap-2 border border-[#E5E7EB] text-gray-600 hover:bg-gray-50 text-sm px-6 py-2.5 rounded-lg font-semibold w-full sm:w-auto hover:text-[#2CAA2C] transition">
                <i class="la la-arrow-left text-lg"></i> Voltar
            </a>
            <a href="#" wire:click.prevent="submit" class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                <i class="la la-save text-lg"></i>Salvar
            </a>
        </div>

    </section>

</div>