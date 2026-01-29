<div class="py-18 flex flex-col gap-14">

    @if (session('success'))
    <div class="alert alert-success">
        <div class="alert-content">
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- BOX TÍTULO + CRIADOR -->
    <div class="relative overflow-hidden rounded-3xl bg-[#232727] p-10 shadow-sm text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

            <!-- CRIADOR (mobile em cima, desktop à direita) -->
            <div class="flex items-center gap-4 order-1 md:order-2">
                <div class="w-14 h-14 rounded-full bg-white/10 text-white flex items-center justify-center font-semibold text-lg">
                    {{ strtoupper(substr($course->teacher->name ?? 'C', 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold leading-tight">{{ $course->teacher->name ?? 'Criador do curso' }}</p>
                    <p class="text-sm text-white/70">Criador</p>
                </div>
            </div>

            <!-- TÍTULO (mobile abaixo, desktop à esquerda) -->
            <div class="text-left max-w-2xl order-2 md:order-1">
                <span class="text-xs uppercase tracking-widest text-white/60 font-semibold block mb-2">Curso {{ $deliveryMode }}</span>
                <h1 class="text-2xl md:text-3xl font-bold leading-tight">{{ $course->name }}</h1>
            </div>

        </div>

        <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
    </div>

    @if($course->enrolled_by_me_count)
    <div class="alert alert-warning text-center">
        <div class="alert-content">
            Você já está matriculado neste curso.
        </div>
    </div>
    @else
    <!-- CTA PRINCIPAL -->
    <a href="#" @click.prevent="$dispatch('run-modal-confirm', { action: 'confirm', context: 'enroll-course', id: '{{ $course->id }}', name: 'Quer matricular no curso: {{ $course->name }}' })" class="w-full text-center inline-flex justify-center items-center gap-2 bg-[#16a34a] text-white font-semibold px-10 py-4 rounded-xl shadow-md hover:bg-[#15803d] transition">
        <i class="la la-graduation-cap"></i>
        Matricular-se agora
    </a>
    @endif

    <!-- VÍDEO -->
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
        <iframe src="{{ embedUrl($course->presentation_video_url) }}" class="w-full h-full" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    @elseif($course->thumbnail)
    <div class="w-full rounded-2xl overflow-hidden border border-gray-200 shadow-sm">
        <img src="{{ $course->thumbnail->url }}" alt="{{ $course->name }}" class="w-full h-full object-cover">
    </div>
    @endif

    <!-- DESCRIÇÃO -->
    <p class="text-gray-600 text-lg leading-relaxed max-w-3xl">{{ $course->description ?? 'Aprenda de forma prática, objetiva e com acompanhamento especializado.' }}</p>

    @if($course->enrolled_by_me_count)
    <div class="alert alert-warning text-center">
        <div class="alert-content">
            Você já está matriculado neste curso.
        </div>
    </div>
    @else
    <!-- CTA PRINCIPAL -->
    <a href="#" @click.prevent="$dispatch('run-modal-confirm', { action: 'confirm', context: 'enroll-course', id: '{{ $course->id }}', name: 'Quer matricular no curso: {{ $course->name }}' })" class="w-full text-center inline-flex justify-center items-center gap-2 bg-[#16a34a] text-white font-semibold px-10 py-4 rounded-xl shadow-md hover:bg-[#15803d] transition">
        <i class="la la-graduation-cap"></i>
        Matricular-se agora
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