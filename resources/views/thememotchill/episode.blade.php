@extends('themes::thememotchill.layout')
@php
    $urls = [];
    $current_url = '';
    $next_url = '';
    $prev_url = '';
    foreach ($currentMovie->episodes->where('server', $episode->server) as $data) {
        if ($data->slug == $episode->slug) {
            $current_url = $data->getUrl();
        }
        $urls[] = $data->getUrl();
    }
    foreach ($urls as $key => $url) {
        if ($current_url == $url) {
            $next_url = $key + 1 >= count($urls) ? $current_url : $urls[$key + 1];
            $prev_url = $key - 1 <= 0 ? $current_url : $urls[$key - 1];
        }
    }
@endphp
@section('content')
    <style>
        .video-footer {
            margin-top: 5px;
        }

        .btn-active {
            color: #fff !important;
            background: #d9534f !important;
            border-color: #d9534f !important;
        }

        .btn-sv {
            margin-right: 5px;
        }

        .btn-sv:last-child {
            margin-right: 0;
        }

        #player-loaded>div {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="pt-2">
        <nav class="flex px-2.5 py-2 text-gray-700 border rounded-md bg-[#181818] border-zinc-900 shadow-lg shadow-black/20"
            aria-label="Breadcrumb">
            <ol class="inline-flex flex-wrap items-center space-x-1 md:space-x-3"><!--[-->
                <li class="inline-flex items-center">
                    <a title="Trang Chủ" href="/"
                        class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-white whitespace-nowrap">
                        <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg> Trang Chủ
                    </a>
                </li>
                @foreach ($currentMovie->regions as $region)
                    <li class="inline-flex items-center">
                        <a title="{{ $region->name }}" href="{{ $region->getUrl() }}"
                            class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-white whitespace-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5">
                                </path>
                            </svg> {{ $region->name }}
                        </a>
                    </li>
                @endforeach
                <li class="inline-flex items-center">
                    <a title="{{ $currentMovie->name }}" href="{{ $currentMovie->getUrl() }}"
                        class="inline-flex items-center text-sm font-medium text-gray-300 hover:text-white whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5">
                            </path>
                        </svg> {{ $currentMovie->name }}
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <span
                        class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5">
                            </path>
                        </svg> Tập {{ $episode->name }}
                    </span>
                </li>
            </ol>
        </nav>
        <div class="py-2 px-1 bg-black border border-yellow-800 text-sm my-2">Motchill có địa chỉ là
            {{ request()->getHost() }}, cả nhà chia sẻ để ủng hộ mình nhé !</div>
        <div id="main-player">
            {{-- <div class="loader"></div> --}}
            <div id="player-loaded"></div>
        </div>
        <div>
            <div class="flex flex-row">
                <div class="basis-1/4"></div>
                <div class="basis-1/2 text-center">
                    <span class="text-sm font-bold pb-2 block">Chọn Server</span>
                    <div class="flex flex-row flex-wrap gap-1.5 items-center justify-center min-h-[35px]">
                        <div>
                            <div id="ploption" class="text-center">
                                @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                                    <a onclick="chooseStreamingServer(this)" data-type="{{ $server->type }}"
                                        data-id="{{ $server->id }}" data-link="{{ $server->link }}"
                                        class="streaming-server text-gray-300 px-2 py-2 text-xs font-medium rounded">
                                        Server #{{ $loop->iteration }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="basis-1/4">
                    {{-- <div class="flex justify-end">
                    <a href="{{ $next_url }}" type="button" aria-label="Tập Tiếp Theo"
                        class="flex items-center bg-zinc-700 hover:opacity-80 px-2 py-1 text-xs font-medium leading-6 text-center text-gray-200 transition rounded shadow ripple hover:shadow-lg focus:outline-none waves-effect">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path strokelinecap="round" strokelinejoin="round" strokewidth="{2}"
                                d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                        </svg>
                        <span class="pl-1 hidden md:block">Tập Tiếp</span>
                    </a>
                </div> --}}
                </div>
            </div>
        </div>
        <div class="video-footer">
            <script>
                function detectMobile() {
                    const toMatch = [
                        /Android/i,
                        /webOS/i,
                        /iPhone/i,
                        /iPad/i,
                        /iPod/i,
                        /BlackBerry/i,
                        /Windows Phone/i
                    ];

                    return toMatch.some((toMatchItem) => {
                        return navigator.userAgent.match(toMatchItem);
                    });
                }
            </script>
        </div>


        <div>
            <span class="text-zinc-200 text-md font-bold uppercase py-2 block">Danh Sách Phát</span>
            <div class="flex flex-row flex-wrap">
                @foreach ($currentMovie->episodes->sortBy([['server', 'asc']])->groupBy('server') as $server => $data)
                    @foreach ($data->sortByDesc('name', SORT_NATURAL)->groupBy('name') as $name => $item)
                        <a href="{{ $item->sortByDesc('type')->first()->getUrl() }}"
                            class="text-gray-300 bg-neutral-700 hover:bg-neutral-600 font-medium text-sm min-w-[67px] text-center px-3 py-1.5 mb-1.5 mr-1.5 rounded-sm @if ($item->contains($episode)) bg-orange-900 @endif"
                            title="{{ $name }}">{{ $name }}</a>
                    @endforeach
                @endforeach
            </div>
        </div>
        <div class="border-t border-gray-800 border-opacity-75 pt-3 mt-2">
            <h1 class="text-[#da966e] font-bold text-xl uppercase inline-block">{{ $currentMovie->name }}
                Tập {{ $episode->name }}</h1>
            <h2 class="pt-1 text-xl text-zinc-400 font-medium">{{ $currentMovie->name }} -
                {{ $currentMovie->origin_name }} Tập {{ $episode->name }} {{ $currentMovie->language }}
                ({{ $currentMovie->publish_year }})</h2>
            <h3 class="pt-1 text-md text-zinc-400 font-medium">Tập {{ $episode->name }}</h3>
            @include('themes::thememotchill.inc.rating2')
            <div class="mt-2 bg-[#222222] p-1 text-gray-400 text-justify">
                <div class="pt-2 inline">
                    {!! mb_substr(strip_tags($currentMovie->content), 0, 1000, 'utf-8') !!}...
                    <a class="text-gray-200 text-sm font-medium whitespace-nowrap" style="color: #fff;"
                        href="{{ $currentMovie->getUrl() }}"
                        title="{{ $currentMovie->name }} - {{ $currentMovie->origin_name }} ({{ $currentMovie->publish_year }})">Xem
                        thêm</a>
                </div>
            </div>
            <div class="sharing-buttons flex flex-wrap items-center py-2 mt-2">
                <span class="text-sm text-gray-400 mr-3">Chia Sẻ</span>
                <a class="border-2 duration-200 ease inline-flex items-center mb-1 mr-1.5 transition p-1 rounded text-white border-[#1877F2] bg-[#1877F2] hover:opacity-90"
                    target="_blank" rel="noopener"
                    href="https://facebook.com/sharer/sharer.php?u={{ $currentMovie->getUrl() }}"
                    aria-label="Share on Facebook"><svg aria-hidden="true" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4">
                        <title>Facebook</title>
                        <path
                            d="M379 22v75h-44c-36 0-42 17-42 41v54h84l-12 85h-72v217h-88V277h-72v-85h72v-62c0-72 45-112 109-112 31 0 58 3 65 4z">
                        </path>
                    </svg>
                </a>
                <a class="border-2 duration-200 ease inline-flex items-center mb-1 mr-1.5 transition p-1 rounded text-white border-[#1DA1F2] bg-[#1DA1F2] hover:opacity-90"
                    target="_blank" rel="noopener"
                    href="https://twitter.com/intent/tweet?url={{ $currentMovie->getUrl() }}"
                    aria-label="Share on Twitter"><svg aria-hidden="true" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4">
                        <title>Twitter</title>
                        <path
                            d="m459 152 1 13c0 139-106 299-299 299-59 0-115-17-161-47a217 217 0 0 0 156-44c-47-1-85-31-98-72l19 1c10 0 19-1 28-3-48-10-84-52-84-103v-2c14 8 30 13 47 14A105 105 0 0 1 36 67c51 64 129 106 216 110-2-8-2-16-2-24a105 105 0 0 1 181-72c24-4 47-13 67-25-8 24-25 45-46 58 21-3 41-8 60-17-14 21-32 40-53 55z">
                        </path>
                    </svg></a>
            </div>
            @if ($currentMovie->showtimes && $currentMovie->showtimes != '')
                <div class="p-2 bg-black my-2 border border-yellow-800">
                    <h3 class="text-base inline-block">Lịch Chiếu: </h3>
                    <span class="pt-2 text-gray-300">{!! strip_tags($currentMovie->showtimes) !!}</span>
                </div>
            @endif
            <div class="mt-2 border-t border-gray-800 border-opacity-75">
                <span class="flex text-gray-300 text-md mt-2 items-center text-medium font-bold text-2md uppercase">
                    Để Lại Bình Luận</span>
                <div class="bg-white mt-4">
                    <div data-order-by="reverse_time" id="commit-99011102" class="fb-comments"
                        data-href="{{ $currentMovie->getUrl() }}" data-width="" data-numposts="10">
                    </div>
                    <script>
                        document.getElementById("commit-99011102").dataset.width = $("#commit-99011102").parent().width();
                    </script>
                </div>
            </div>
        </div>
        <div class="mt-3 pb-2 border-t border-gray-800 border-opacity-75"><i class="text-sm block pt-2 text-gray-200">Từ
                Khóa :</i> <span class="text-gray-200 text-sm">xem phim {{ $episode->name }} full vietsub, thuyết minh,
                xem
                phim {{ $episode->name }} full hd, {{ $episode->origin_name }} full vietsub, thuyết minh,
                motchill, motphim, phimmoi, tvhay, hayghe, subnhanh, bichill, vieon, fptplay, netflix,
                youtube, bilutv, phim1080, fullphim, dongphim, tvzing, luotphim, wetv, phimchill, ssphim,
                motphimtv, hitv, netflix</span></div>

        <div class="w-full my-1"></div>
        <div>
            <section class="pt-4 py-4">
                <span class="text-gray-200 font-semibold uppercase pl-2 py-3 block text-md"><span
                        class="inline-block"><svg aria-hidden="true" focusable="false" data-prefix="far"
                            data-icon="star" class="w-4 text-yellow-500 mr-1" role="img"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <path fill="currentColor"
                                d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z">
                            </path>
                        </svg></span>Phim Đề Cử</span>
                <div class="grid grid-cols-2 gap-x-2 gap-y-4 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach ($movie_related as $movie)
                        <div>
                            <article
                                class="max-w-xs bg-[#181818] rounded-md group text-gray-50 relative overflow-hidden pb-2">
                                <a title="Phim {{ $movie->name }} {{ $movie->publish_year }}"
                                    href="{{ $movie->getUrl() }}" class="relative"><img
                                        src="{{ $movie->getThumbUrl() }}"
                                        alt="{{ $movie->name }} {{ $movie->publish_year }}" loading="lazy"
                                        data-nuxt-img="" title="{{ $movie->name }}"
                                        class="object-cover object-center w-full rounded-t-md h-56 bg-zinc-800 scale-105 group-hover:scale-110 ease-in duration-200"></a>
                                <div class="mt-3 px-2"><a title="{{ $movie->name }}"
                                        href="{{ $movie->getUrl() }}"><span
                                            class="text-[15px] font-medium capitalize pt-1 block line-clamp-2 h-12">
                                            {{ $movie->name }} {{ $movie->publish_year }}</span></a></div>
                                <span
                                    class="text-xs py-1 px-2 block rounded-br-md rounded-tr-md bg-[#A3765D] absolute top-2 left-0 shadow-lg shadow-red-900/20">{{ $movie->episode_current }}
                                    {{ $movie->language }}</span><!---->
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    {{-- Load player --}}
    <script>
        $('.btn-sv').on('click', function() {
            var __this = $(this);

            __this.parent().find('a.btn').removeClass('btn-active');
            __this.addClass('btn-active');
        });

        function removeAds(__this) {
            __this.closest('.player_ads').hide();
        }
    </script>
@endsection

@push('scripts')
    <script src="/themes/motchill/static/player/skin/juicycodes.js"></script>
    <link href="/themes/motchill/static/player/skin/juicycodes.css" rel="stylesheet" type="text/css">

    {{--    <script src="/themes/motchill/static/player/js/p2p-media-loader-core.min.js"></script> --}}
    {{--    <script src="/themes/motchill/static/player/js/p2p-media-loader-hlsjs.min.js"></script> --}}

    <script src="/themes/motchill/static/player/jwplayer.js"></script>
    {{--    <script src="/js/jwplayer-8.9.3.js"></script> --}}
    {{--    <script src="/js/hls.min.js"></script> --}}
    {{--    <script src="/js/jwplayer.hlsjs.min.js"></script> --}}


    <script>
        var episode_id = {{ $episode->id }};
        const wrapper = document.getElementById('player-loaded');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;

            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('bg-[#A3765D]');
            })
            el.classList.add('bg-[#A3765D]')

            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/motchill/static/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    file: link,
                    image: "{{ $currentMovie->getPosterUrl() }}",
                    autostart: true,
                    controls: true,
                    primary: "html5",
                    playbackRateControls: true,
                    playbackRates: [0.5, 0.75, 1, 1.5, 2],
                    // sharing: {
                    //     sites: [
                    //         "reddit",
                    //         "facebook",
                    //         "twitter",
                    //         "googleplus",
                    //         "email",
                    //         "linkedin",
                    //     ],
                    // },
                    volume: 100,
                    mute: false,
                    logo: {
                        file: "{{ Setting::get('jwplayer_logo_file') }}",
                        link: "{{ Setting::get('jwplayer_logo_link') }}",
                        position: "{{ Setting::get('jwplayer_logo_position') }}",
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua",
                        admessage: "Quảng cáo còn xx giây."
                    },
                    tracks: [{
                        "file": "/sub.vtt",
                        "kind": "captions",
                        label: "VN",
                        default: "true"
                    }],
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    // if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                    //     var engine = new p2pml.hlsjs.Engine(engine_config);
                    //     player.setup(objSetup);
                    //     jwplayer_hls_provider.attach();
                    //     p2pml.hlsjs.initJwPlayer(player, {
                    //         liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                    //         maxBufferLength: 300,
                    //         loader: engine.createLoaderClass(),
                    //     });
                    // } else {
                    player.setup(objSetup);
                    // }
                } else {
                    player.setup(objSetup);
                }

                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind2" viewBox="0 0 240 240" focusable="false"><path d="m 25.993957,57.778 v 125.3 c 0.03604,2.63589 2.164107,4.76396 4.8,4.8 h 62.7 v -19.3 h -48.2 v -96.4 H 160.99396 v 19.3 c 0,5.3 3.6,7.2 8,4.3 l 41.8,-27.9 c 2.93574,-1.480087 4.13843,-5.04363 2.7,-8 -0.57502,-1.174985 -1.52502,-2.124979 -2.7,-2.7 l -41.8,-27.9 c -4.4,-2.9 -8,-1 -8,4.3 v 19.3 H 30.893957 c -2.689569,0.03972 -4.860275,2.210431 -4.9,4.9 z m 163.422413,73.04577 c -3.72072,-6.30626 -10.38421,-10.29683 -17.7,-10.6 -7.31579,0.30317 -13.97928,4.29374 -17.7,10.6 -8.60009,14.23525 -8.60009,32.06475 0,46.3 3.72072,6.30626 10.38421,10.29683 17.7,10.6 7.31579,-0.30317 13.97928,-4.29374 17.7,-10.6 8.60009,-14.23525 8.60009,-32.06475 0,-46.3 z m -17.7,47.2 c -7.8,0 -14.4,-11 -14.4,-24.1 0,-13.1 6.6,-24.1 14.4,-24.1 7.8,0 14.4,11 14.4,24.1 0,13.1 -6.5,24.1 -14.4,24.1 z m -47.77056,9.72863 v -51 l -4.8,4.8 -6.8,-6.8 13,-12.99999 c 3.02543,-3.03598 8.21053,-0.88605 8.2,3.4 v 62.69999 z"></path></svg>',
                    "Forward 10 Seconds", () => player.seek(player.getPosition() + 10), "Forward 10 Seconds");
                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind" viewBox="0 0 240 240" focusable="false"><path d="M113.2,131.078a21.589,21.589,0,0,0-17.7-10.6,21.589,21.589,0,0,0-17.7,10.6,44.769,44.769,0,0,0,0,46.3,21.589,21.589,0,0,0,17.7,10.6,21.589,21.589,0,0,0,17.7-10.6,44.769,44.769,0,0,0,0-46.3Zm-17.7,47.2c-7.8,0-14.4-11-14.4-24.1s6.6-24.1,14.4-24.1,14.4,11,14.4,24.1S103.4,178.278,95.5,178.278Zm-43.4,9.7v-51l-4.8,4.8-6.8-6.8,13-13a4.8,4.8,0,0,1,8.2,3.4v62.7l-9.6-.1Zm162-130.2v125.3a4.867,4.867,0,0,1-4.8,4.8H146.6v-19.3h48.2v-96.4H79.1v19.3c0,5.3-3.6,7.2-8,4.3l-41.8-27.9a6.013,6.013,0,0,1-2.7-8,5.887,5.887,0,0,1,2.7-2.7l41.8-27.9c4.4-2.9,8-1,8,4.3v19.3H209.2A4.974,4.974,0,0,1,214.1,57.778Z"></path></svg>',
                    "Rewind 10 Seconds", () => player.seek(player.getPosition() - 10), "Rewind 10 Seconds");

                const resumeData = 'OPCMS-PlayerPosition-' + id;

                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{ $episode->id }}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>
@endpush
