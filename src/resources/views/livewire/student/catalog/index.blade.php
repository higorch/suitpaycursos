<div class="flex flex-col gap-14 pt-16 pb-24 px-6 max-w-7xl mx-auto">

    <!-- Header -->
    <section class="relative overflow-hidden rounded-2xl bg-[#2CAA2C] px-6 py-7 md:px-8 md:py-8 text-white">
        <div class="flex flex-col gap-3 max-w-2xl">
            <h1 class="text-xl md:text-2xl font-semibold leading-tight">Cursos para impulsionar sua evolução profissional</h1>
            <p class="text-sm text-white/90 leading-relaxed">Aprenda com especialistas e desenvolva habilidades no seu ritmo.</p>
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <span class="flex items-center gap-2 text-[11px] bg-white/10 px-3 py-1.5 rounded-full">
                    <i class="la la-check-circle text-xs"></i> Acesso imediato
                </span>
                <span class="flex items-center gap-2 text-[11px] bg-white/10 px-3 py-1.5 rounded-full">
                    <i class="la la-check-circle text-xs"></i> Instrutores especialistas
                </span>
                <span class="flex items-center gap-2 text-[11px] bg-white/10 px-3 py-1.5 rounded-full">
                    <i class="la la-check-circle text-xs"></i> Aprenda no seu ritmo
                </span>
            </div>
        </div>
        <!-- detalhe de profundidade bem sutil -->
        <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
    </section>

    <section>
        @if ($courses->isEmpty())
        <div class="rounded-2xl border border-[#E5E7EB] bg-[#F9FAFB] px-6 py-16 text-center">
            <p class="text-sm text-gray-500">Nenhum curso disponível no momento.</p>
        </div>
        @else

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach ($courses as $course)
            <a href="{{ route('student.catalogs.single', ['at' => $course->teacher->at ?? 'instrutor', 'slug' => $course->slug]) }}"
                class="group relative flex flex-col rounded-3xl border border-[#E5E7EB] bg-white shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                <!-- THUMB -->
                <div class="relative h-44 overflow-hidden bg-gradient-to-br from-[#22c55e]/30 via-[#22c55e]/10 to-white">

                    @if($course->thumbnail)
                    <img src="{{ $course->thumbnail->url ?? '' }}"
                        alt="{{ $course->name }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif

                    @if($course->delivery_mode)
                    <span class="absolute top-3 left-3 text-[10px] font-semibold uppercase tracking-wide bg-white/90 backdrop-blur px-3 py-1 rounded-full text-gray-700 shadow-sm">
                        {{ $course->delivery_mode }}
                    </span>
                    @endif
                </div>

                <!-- CONTENT -->
                <div class="flex flex-col gap-4 p-6 flex-1">

                    <!-- PROFESSOR -->
                    <span class="text-[11px] text-gray-400 uppercase tracking-widest">
                        {{ $course->teacher->name ?? 'Instrutor' }}
                    </span>

                    <!-- TÍTULO -->
                    <h2 class="font-semibold text-lg leading-snug text-[#111827] line-clamp-2 group-hover:text-[#16a34a] transition">
                        {{ $course->name }}
                    </h2>

                    <!-- DESCRIÇÃO -->
                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                        {{ $course->description ?? 'Aprenda de forma prática e objetiva com conteúdo direto ao ponto.' }}
                    </p>

                    <!-- INFO BAR -->
                    <div class="mt-auto pt-4 border-t border-[#F3F4F6] flex flex-col gap-2 text-xs text-gray-500">

                        @if($course->max_enrollments)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#22c55e]/15 text-[#16a34a]">
                                <i class="la la-users text-sm"></i>
                            </div>
                            <span><strong class="text-gray-700">{{ $course->max_enrollments }}</strong> vagas disponíveis</span>
                        </div>
                        @endif

                        @if($course->enrollment_deadline)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 flex items-center justify-center rounded-md bg-[#22c55e]/15 text-[#16a34a]">
                                <i class="la la-calendar text-sm"></i>
                            </div>
                            <span>Matrículas até <strong class="text-gray-700">{{ \Carbon\Carbon::parse($course->enrollment_deadline)->format('d/m/Y') }}</strong></span>
                        </div>
                        @endif

                    </div>

                    <!-- CTA BUTTON (visual only, card inteiro já é link) -->
                    <div class="pt-4">
                        <div class="w-full inline-flex items-center justify-center gap-2 bg-[#22c55e] group-hover:bg-[#16a34a] text-white text-sm px-4 py-3 rounded-xl font-semibold shadow-sm transition">
                            <i class="la la-play text-base"></i>
                            Acessar curso
                        </div>
                    </div>

                </div>

                <!-- HOVER RING -->
                <div class="pointer-events-none absolute inset-0 rounded-3xl ring-1 ring-transparent group-hover:ring-[#22c55e]/30 transition"></div>

            </a>
            @endforeach

        </div>

        @if ($courses->hasPages())
        <div class="pt-14">
            {{ $courses->links('layouts.pagination') }}
        </div>
        @endif

        @endif
    </section>


</div>