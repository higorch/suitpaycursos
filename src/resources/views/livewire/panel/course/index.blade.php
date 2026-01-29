<div class="flex flex-col gap-10 pt-14 pb-16 px-6">

    <!-- Page Header -->
    <section>

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <!-- Title + Back -->
            <div class="flex items-center gap-4">
                <h1 class="text-3xl font-semibold tracking-tight text-[#111827]">Cursos</h1>
            </div>

            <!-- Top Save -->
            <div class="w-full md:w-auto">
                <div class="flex flex-col sm:flex-row gap-3 md:justify-end">
                    <a href="{{ route('panel.courses.save') }}" wire:navigate class="inline-flex items-center justify-center gap-2 bg-[#2CAA2C] hover:bg-[#259C25] text-white text-sm px-6 py-2.5 rounded-lg font-semibold shadow-sm transition w-full sm:w-auto">
                        <i class="la la-plus text-lg"></i> Novo
                    </a>
                </div>
            </div>

        </div>

    </section>

    <!-- Page Content -->
    <section>

        <div class="bg-white rounded-2xl shadow-sm border border-[#E5E7EB] p-8"></div>

    </section>

</div>