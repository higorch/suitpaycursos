<div class="py-16">

    <div class="flex flex-col gap-16 px-6">

        <!-- HEADER -->
        <section class="relative overflow-hidden rounded-3xl bg-[#232627] p-10 md:p-14 text-white shadow-sm flex flex-col gap-8">

            <div class="max-w-2xl flex flex-col gap-4 relative z-10">
                <h1 class="text-xl md:text-2xl font-semibold leading-tight">
                    Meus Cursos
                </h1>
                <p class="text-white/80 leading-relaxed text-sm">
                    Aqui estão os cursos em que você já está matriculado.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-sm relative z-10">
                <span class="flex items-center gap-2 bg-[#2CAA2C]/15 border border-[#2CAA2C]/30 px-4 py-2 rounded-full text-[#bbf7d0]">
                    <i class="la la-play-circle text-[#4ade80]"></i>
                    Seus cursos ativos
                </span>
                <span class="flex items-center gap-2 bg-[#2CAA2C]/15 border border-[#2CAA2C]/30 px-4 py-2 rounded-full text-[#bbf7d0]">
                    <i class="la la-clock text-[#4ade80]"></i>
                    Evolua no seu tempo
                </span>
            </div>

            <!-- efeitos decorativos sutis -->
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute right-10 bottom-0 w-40 h-40 bg-black/20 rounded-full blur-2xl"></div>
        </section>

        <!-- LISTA -->
        <section>
            @if ($courses->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white px-6 py-20 text-center shadow-sm">
                <p class="text-sm text-gray-500">Você ainda não está matriculado em nenhum curso.</p>
            </div>
            @else

            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

                @foreach ($courses as $course)
                @php
                $deliveryModeLabels = [
                'online' => 'Online',
                'in-person' => 'Presencial',
                'hybrid' => 'Híbrido',
                ];
                @endphp

                <a href="{{ route('student.catalogs.single', ['at' => $course->creator->at ?? 'instrutor', 'slug' => $course->slug]) }}" class="group flex flex-col rounded-3xl border border-gray-100 bg-white shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    <!-- THUMB -->
                    <div class="relative h-44 overflow-hidden bg-linear-to-br from-[#1f5f05] via-[#277306] to-[#4ade80]">
                        @if($course->thumbnail)
                        <img src="{{ $course->thumbnail->url }}"
                            alt="{{ $course->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
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
                            {{ $course->creator->name ?? 'Instrutor' }}
                        </span>

                        <h2 class="font-semibold text-lg leading-snug text-gray-900 line-clamp-2 group-hover:text-[#15803d] transition">
                            {{ $course->name }}
                        </h2>

                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                            {{ $course->description ?? 'Conteúdo disponível para você continuar aprendendo.' }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <div class="w-full inline-flex items-center justify-center gap-2 bg-[#16a34a] text-white text-sm px-4 py-3 rounded-xl font-semibold shadow-sm group-hover:bg-[#15803d] transition">
                                <i class="la la-play text-base"></i>
                                Acessar curso
                            </div>
                        </div>

                    </div>
                </a>
                @endforeach

            </div>
            @endif
        </section>

    </div>

</div>