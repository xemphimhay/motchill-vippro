<div class="relative">
    <div class="flex gap-x-3 pb-2">
        <h2
            class="text-md bg-[#A3765D] text-zinc-300 font-medium cursor-pointer transition-all ease-linear duration-100 rounded-md px-3 py-1 block">
            {{ $item['label'] }}</h2>
    </div>
    <div class="grid grid-cols-2 gap-x-3 gap-y-4 md:grid-cols-4 pt-3">
        @foreach ($item['data'] as $key => $movie)
            @php
                $xClass = 'item';
                if ($key === 0 || $key % 4 === 0) {
                    $xClass .= ' no-margin-left';
                }
            @endphp
            @include('themes::thememotchill.inc.sections_movies_item')
        @endforeach
    </div>
</div>
