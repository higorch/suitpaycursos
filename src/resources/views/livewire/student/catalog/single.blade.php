<div class="py-16">

    <div class="flex flex-col gap-16 px-6">

        @if (session('success'))
        <div class="alert alert-success">
            <div class="alert-content">
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- HERO DO CURSO -->
        <section class="relative overflow-hidden rounded-3xl bg-[#232627] p-10 md:p-14 text-white shadow-sm">

            <div class="flex flex-col gap-10 relative z-10">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-10">

                    <!-- TÍTULO -->
                    <div class="max-w-2xl flex flex-col gap-4">
                        <span class="text-xs uppercase tracking-widest text-white/40 font-semibold">
                            Curso {{ $deliveryMode }}
                        </span>
                        <h1 class="text-xl md:text-2xl font-semibold leading-tight">
                            {{ $course->name }}
                        </h1>
                    </div>

                    <!-- CRIADOR -->
                    <div class="flex items-center gap-3 bg-white/5 px-4 py-3 rounded-xl border border-white/10">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center font-semibold text-base text-white/80">
                            {{ strtoupper(substr($course->creator->name ?? 'P', 0, 1)) }}
                        </div>
                        <div class="leading-tight">
                            <p class="font-medium text-white/80">{{ $course->creator->name ?? 'Criador do curso' }}</p>
                            <p class="text-xs text-white/40 uppercase tracking-wide">Criador</p>
                        </div>
                    </div>

                </div>

                <!-- CTA PRINCIPAL -->
                @if($course->enrolled_by_me_count)
                <div class="alert alert-warning text-center mt-2">
                    <div class="alert-content">
                        Você já está matriculado neste curso.
                    </div>
                </div>
                @else
                <a href="#"
                    @click.prevent="$dispatch('run-modal-confirm', { action: 'confirm', context: 'enroll-course', id: '{{ $course->id }}', name: 'Quer matricular no curso: {{ $course->name }}' })"
                    class="w-full inline-flex items-center justify-center gap-3 bg-[#16a34a] text-white font-semibold px-10 py-5 rounded-2xl shadow-lg hover:bg-[#15803d] hover:shadow-xl hover:scale-[1.01] transition text-base">
                    <i class="la la-graduation-cap text-xl"></i>
                    Matricule-se agora
                </a>
                @endif

            </div>

            <div class="absolute -right-24 -top-24 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>
        </section>

        <!-- VÍDEO / CAPA -->
        @php
        function embedUrl($url){
        if(str_contains($url,'youtube.com/watch')){parse_str(parse_url($url,PHP_URL_QUERY),$params);return 'https://www.youtube.com/embed/'.($params['v']??'');}
        if(str_contains($url,'youtu.be/')){return 'https://www.youtube.com/embed/'.basename(parse_url($url,PHP_URL_PATH));}
        if(str_contains($url,'vimeo.com/')){return 'https://player.vimeo.com/video/'.basename(parse_url($url,PHP_URL_PATH));}
        return $url;
        }
        @endphp

        @if($course->presentation_video_url)
        <div class="w-full aspect-video rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
            <iframe src="{{ embedUrl($course->presentation_video_url) }}" class="w-full h-full" allowfullscreen></iframe>
        </div>
        @elseif($course->thumbnail)
        <div class="w-full rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
            <img src="{{ $course->thumbnail->url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
        </div>
        @endif

        <!-- DESCRIÇÃO -->
        <div class="max-w-3xl text-gray-600 text-lg leading-relaxed">
            {{ $course->description ?? 'Aprenda de forma prática, objetiva e com acompanhamento especializado.' }}
        </div>

        <!-- CTA SECUNDÁRIO FULL -->
        @if(!$course->enrolled_by_me_count)
        <a href="#"
            @click.prevent="$dispatch('run-modal-confirm', { action: 'confirm', context: 'enroll-course', id: '{{ $course->id }}', name: 'Quer matricular no curso: {{ $course->name }}' })"
            class="w-full inline-flex items-center justify-center gap-3 bg-[#16a34a]/90 text-white font-semibold px-10 py-5 rounded-2xl shadow-md hover:bg-[#15803d] hover:shadow-lg transition text-base">
            <i class="la la-graduation-cap text-lg"></i>
            Matricule-se agora
        </a>
        @endif

        <!-- INFORMAÇÕES DO CURSO -->
        <div class="bg-white border border-gray-100 rounded-2xl p-10 grid sm:grid-cols-3 gap-10 text-center shadow-sm">

            @if($course->max_enrollments)
            <div class="flex flex-col items-center gap-2">
                <i class="la la-users text-2xl text-[#16a34a]"></i>
                <p class="font-semibold text-gray-900 text-lg">{{ $course->max_enrollments }} vagas</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Disponíveis</p>
            </div>
            @endif

            @if($course->enrollment_deadline)
            <div class="flex flex-col items-center gap-2">
                <i class="la la-calendar text-2xl text-[#16a34a]"></i>
                <p class="font-semibold text-gray-900 text-lg">{{ \Carbon\Carbon::parse($course->enrollment_deadline)->format('d/m/Y') }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Prazo Matrículas</p>
            </div>
            @endif

            @if($deliveryMode)
            <div class="flex flex-col items-center gap-2">
                <i class="la la-signal text-2xl text-[#16a34a]"></i>
                <p class="font-semibold text-gray-900 text-lg">{{ ucfirst($deliveryMode) }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Modalidade</p>
            </div>
            @endif

        </div>

    </div>

</div>