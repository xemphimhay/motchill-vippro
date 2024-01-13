
<div>
    <article class="max-w-xs bg-[#181818] rounded-md group text-gray-50 relative overflow-hidden pb-2"><a
            title="Phim Echo 2024" href="{{$movie->getUrl()}}" class="relative"><img
                src="{{$movie->getThumbUrl()}}"
                onerror="this.setAttribute('data-error', 1)" alt="{{$movie->name}} {{$movie->publish_year}}" loading="lazy" data-nuxt-img=""
                title="Echo"
                class="object-cover object-center w-full rounded-t-md h-56 bg-zinc-800 scale-105 group-hover:scale-110 ease-in duration-200"></a>
        <div class="mt-3 px-2"><a title="Echo" href="{{$movie->getUrl()}}">
                <h3 class="text-[15px] font-medium capitalize pt-1 block line-clamp-2 h-12">{{$movie->name}} ({{$movie->publish_year}})</h3>
            </a></div><span
            class="text-xs py-1 px-2 block rounded-br-md rounded-tr-md bg-[#A3765D] absolute top-2 left-0 shadow-lg shadow-red-900/20">{{$movie->episode_current}} {{$movie->language}}</span><!---->
    </article>
</div>
