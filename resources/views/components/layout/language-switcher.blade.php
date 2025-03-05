@foreach($languages as $locale => $language)
    @break($loop->index > 0)
    <a href="{{route($route, array_merge(request()->route()->parameters(), ['locale' => $locale], request()->query()))}}" class="">
        <picture>
            <img src="{{asset('/Assets/Icons/General/globe.svg')}}" alt="" />
        </picture>
        <span> {{$language}} </span>
    </a>
@endforeach