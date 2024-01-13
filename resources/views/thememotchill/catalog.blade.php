@extends('themes::thememotchill.layout')

@php
    $years = Cache::remember('all_years', \Backpack\Settings\app\Models\Setting::get('site_cache_ttl', 5 * 60), function () {
        return \Ophim\Core\Models\Movie::select('publish_year')
            ->distinct()
            ->pluck('publish_year')
            ->sortDesc();
    });
@endphp



@section('content')
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
                <li class="inline-flex items-center">
                    <span
                        class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"></path>
                        </svg> {{ $section_name ?? 'Danh Sách Phim' }}
                    </span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-normal text-[#da966e] uppercase text-center py-2">{{ $section_name ?? 'Danh Sách Phim' }}
        </h1>
        @include('themes::thememotchill.inc.catalog_filter')
        <div class="w-full h-px my-2 bg-zinc-700"></div>
        <section class="pt-4">
            <div class="grid grid-cols-2 gap-x-2 gap-y-4 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5">
                @foreach ($data as $key => $movie)
                    @php
                        $xClass = 'item';
                        if ($key === 0 || $key % 4 === 0) {
                            $xClass .= ' no-margin-left';
                        }
                    @endphp

                    @include('themes::thememotchill.inc.catalog_sections_movies_item')
                @endforeach
            </div>
            <div class="text-center pt-3 pb-4">
                {{ $data->appends(request()->all())->links('themes::thememotchill.inc.pagination') }}
            </div>
        </section>
    </div>
@endsection
