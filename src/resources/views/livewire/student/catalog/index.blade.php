<div class="py-18 flex flex-col gap-14">

    <!-- HEADER -->
    <section class="relative overflow-hidden rounded-3xl bg-black p-10 text-white shadow-sm flex flex-col gap-6">
        <div class="max-w-2xl flex flex-col gap-3">
            <h1 class="text-2xl md:text-3xl font-semibold leading-tight">Cursos para impulsionar sua evolução profissional</h1>
            <p class="text-white/80 leading-relaxed">Aprenda com especialistas e desenvolva habilidades no seu ritmo.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3 pt-2 text-sm">
            <span class="flex items-center gap-2 bg-white/5 border border-white/15 px-4 py-2 rounded-full">
                <i class="la la-check-circle text-[#4ade80]"></i>
                Acesso imediato
            </span>

            <span class="flex items-center gap-2 bg-white/5 border border-white/15 px-4 py-2 rounded-full">
                <i class="la la-check-circle text-[#4ade80]"></i>
                Instrutores especialistas
            </span>

            <span class="flex items-center gap-2 bg-white/5 border border-white/15 px-4 py-2 rounded-full">
                <i class="la la-check-circle text-[#4ade80]"></i>
                Aprenda no seu ritmo
            </span>
        </div>

        <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
    </section>

    <!-- COURSE LIST -->
    <section>
        @if ($courses->isEmpty())
        <div class="rounded-2xl border border-gray-200 bg-white px-6 py-16 text-center shadow-sm">
            <p class="text-sm text-gray-500">Nenhum curso disponível no momento.</p>
        </div>
        @else

        <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            @foreach ($courses as $course)
            @php
                $deliveryModeLabels = [
                    'online' => 'Online',
                    'in-person' => 'Presencial',
                    'hybrid' => 'Híbrido',
                ];
            @endphp
            <a href="{{ route('student.catalogs.single', ['at' => $course->teacher->at ?? 'instrutor', 'slug' => $course->slug]) }}" class="group flex flex-col rounded-3xl border border-gray-100 bg-white shadow-sm hover:shadow-lg transition overflow-hidden">

                <!-- THUMB -->
                <div class="relative h-44 overflow-hidden bg-linear-to-br from-[#1f5f05] via-[#277306] to-[#4ade80]">
                    @if($course->thumbnail)
                    <img src="{{ $course->thumbnail->url ?? '' }}" alt="{{ $course->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @endif

                    @if($course->delivery_mode)
                    <span class="absolute top-3 left-3 text-[10px] font-semibold uppercase tracking-wide bg-white/90 px-3 py-1 rounded-full text-gray-700 shadow-sm">
                        {{ $deliveryModeLabels[$course->delivery_mode] ?? '—' }}
                    </span>
                    @endif
                </div>

                <!-- CONTENT -->
                <div class="flex flex-col gap-4 p-6 flex-1">

                    <span class="text-[11px] text-gray-400 uppercase tracking-widest">
                        {{ $course->teacher->name ?? 'Criador' }}
                    </span>

                    <h2 class="font-semibold text-lg leading-snug text-gray-900 line-clamp-2 group-hover:text-[#16a34a] transition">
                        {{ $course->name }}
                    </h2>

                    <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                        {{ $course->description ?? 'Aprenda de forma prática e objetiva com conteúdo direto ao ponto.' }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex flex-col gap-2 text-xs text-gray-500">
                        @if($course->max_enrollments)
                        <div class="flex items-center gap-2">
                            <i class="la la-users text-[#16a34a]"></i>
                            <span><strong class="text-gray-700">{{ $course->max_enrollments }}</strong> vagas disponíveis</span>
                        </div>
                        @endif

                        @if($course->enrollment_deadline)
                        <div class="flex items-center gap-2">
                            <i class="la la-calendar text-[#16a34a]"></i>
                            <span>Matrículas até <strong class="text-gray-700">{{ \Carbon\Carbon::parse($course->enrollment_deadline)->format('d/m/Y') }}</strong></span>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4">
                        <div class="w-full inline-flex items-center justify-center gap-2 bg-[#16a34a] text-white text-sm px-4 py-3 rounded-xl font-semibold shadow-sm group-hover:bg-[#15803d] transition">
                            <i class="la la-play text-base"></i>
                            Ver curso
                        </div>
                    </div>

                </div>
            </a>
            @endforeach

        </div>

        @if ($courses->hasPages())
        <div class="pt-16">
            {{ $courses->links('layouts.pagination') }}
        </div>
        @endif

        @endif
    </section>

</div>