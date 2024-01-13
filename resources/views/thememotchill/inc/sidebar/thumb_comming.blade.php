
<div class="pt-4">
    <div>
        <section class="text-gray-600 body-font py-4">
            <div class="flex flex-col w-full mb-2"><span
                    class="uppercase font-normal text-[#da966e] text-xl border-dashed border-b border-zinc-600 w-full block pb-2 mb-2">{{ $top['label'] }}</span>
            </div>
            <div class="-m-2">
                @foreach ($top['data'] as $key => $movie)
                    <div class="p-1 w-full">
                        <div class="h-full flex border-zinc-800 border p-2 rounded-sm gap-2"><a class="w-20 h-20"
                                href="{{ $movie->getUrl() }}" title="{{ $movie->name }}">
                                <img src="{{ $movie->getThumbUrl() }}" alt="{{ $movie->name }}" data-nuxt-img=""
                                    title="{{ $movie->name }}"
                                    class="w-full h-full object-cover object-center rounded-md"></a>
                            <div class="w-3/5 truncate">
                                <a href="{{ $movie->getUrl() }}" class="text-gray-300 text-[14px] truncate font-medium"
                                    title="{{ $movie->name }}">{{ $movie->name }}</a>
                                <span class="text-zinc-400 text-sm block">{{ $movie->publish_year }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>
