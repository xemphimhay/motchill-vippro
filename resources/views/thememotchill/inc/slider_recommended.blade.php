@if ($recommendations)
<style>
    @media (max-width: 767px){
.widthphull{
    width: 50%
}
    }
</style>
    <div class="myui-top-movies">
        <h2 class="ml-[10px] myui-block-title mr-sm-10 color-orange">{{ $recommendations['label'] }}</h2>
        <div class="flickity clearfix">

            @foreach ($recommendations['data'] as $movie)
                <div class="col-lg-5 col-md-5 col-sm-4 col-xs-3 widthphull">

                    <article class="mr-4 max-w-xs rounded-md group text-gray-50 relative overflow-hidden pb-2">
                        <a title="Phim {{ $movie->name }} {{ $movie->publish_year }}" href="{{ $movie->getUrl() }}" class="relative">
                            <img src="{{ $movie->getThumbUrl() }}"
                                alt="{{ $movie->name }} {{ $movie->publish_year }}" data-nuxt-img="" title="{{ $movie->name }}"
                                class="object-cover object-center w-full rounded-t-md h-60 bg-zinc-800 scale-105 group-hover:scale-110 ease-in duration-200">
                        </a>
                        <div class="mt-3 px-2">
                            <a title="{{ $movie->name }}" href="{{ $movie->getUrl() }}">
                                <h3 class="text-[15px] font-medium capitalize pt-1 block line-clamp-2 h-12">{{ $movie->name }}</h3>
                            </a>
                        </div>
                        <span class="text-xs py-1 px-2 block rounded-br-md rounded-tr-md bg-[#A3765D] absolute top-2 left-0 shadow-lg shadow-red-900/20">{{ $movie->episode_current }} {{ $movie->language }}</span>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
@endif
