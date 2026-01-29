<div class="bg-[#f8fafc] py-24">
    <div class="max-w-5xl mx-auto px-6 flex flex-col gap-14">

        <!-- 🟢 BOX TÍTULO + CRIADOR -->
        <div class="bg-white border border-gray-100 rounded-3xl p-10 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-8">

                <!-- CRIADOR (mobile em cima, desktop à direita) -->
                <div class="flex items-center gap-4 order-1 md:order-2">
                    <div class="w-14 h-14 rounded-full bg-[#16a34a]/10 text-[#16a34a] flex items-center justify-center font-semibold text-lg">
                        {{ strtoupper(substr($course->teacher->name ?? 'C', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 leading-tight">{{ $course->teacher->name ?? 'Criador do curso' }}</p>
                        <p class="text-sm text-gray-500">Criador</p>
                    </div>
                </div>

                <!-- TÍTULO (mobile abaixo, desktop à esquerda) -->
                <div class="text-left max-w-2xl order-2 md:order-1">
                    <span class="text-xs uppercase tracking-widest text-[#16a34a] font-semibold block mb-2">Curso Online</span>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight">{{ $course->name }}</h1>
                </div>

            </div>
        </div>

        <!-- 🎯 CTA PRINCIPAL -->
        <a href="#matricula" class="w-full text-center inline-flex justify-center items-center gap-2 bg-[#16a34a] text-white font-semibold px-10 py-4 rounded-xl shadow-md hover:bg-[#15803d] transition">
            <i class="la la-graduation-cap"></i>
            Matricular-se agora
        </a>

        <!-- 🎬 VÍDEO -->
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

        <!-- 📝 DESCRIÇÃO -->
        <p class="text-gray-600 text-lg leading-relaxed max-w-3xl">{{ $course->description ?? 'Aprenda de forma prática, objetiva e com acompanhamento especializado.' }}</p>

        <!-- 🎯 CTA SECUNDÁRIO -->
        <a href="#matricula" class="w-full text-center inline-flex justify-center items-center gap-2 bg-[#16a34a] text-white font-semibold px-10 py-4 rounded-xl shadow-md hover:bg-[#15803d] transition">
            <i class="la la-graduation-cap"></i>
            Matricular agora
        </a>

        <!-- 📊 INFORMAÇÕES DO CURSO -->
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
            @if($course->delivery_mode)
            <div class="flex flex-col items-center gap-2">
                <i class="la la-signal text-2xl text-[#16a34a]"></i>
                <p class="font-semibold text-gray-900 text-lg">{{ ucfirst($course->delivery_mode) }}</p>
                <p class="text-xs text-gray-500 uppercase tracking-wide">Modalidade</p>
            </div>
            @endif
        </div>

    </div>
</div>