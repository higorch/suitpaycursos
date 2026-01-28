<div class="flex flex-col gap-10">

    <!-- Page Header -->
    <section class="px-6 pt-14">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <div class="space-y-2">
                <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">Cursos</h1>
                <p class="text-sm text-gray-500">Gerenciamento de cursos</p>
            </div>

            <div class="w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-3 md:justify-end">
                    <a href="{{ route('panel.courses.save') }}" wire:navigate class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                        <i class="la la-plus"></i>Novo
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Page Content -->
    <section class="px-6 pb-16">
        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8">

            <p>...</p>

        </div>
    </section>

</div>
