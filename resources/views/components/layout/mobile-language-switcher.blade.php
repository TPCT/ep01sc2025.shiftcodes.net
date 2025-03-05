<li class="mobile-lang">
    <a href="#">
        <img src="{{asset(config('app.locales_images.' . $language))}}" alt="{{config('app.locales.' . $language)}}" srcset="" />
        {{config('app.locales.' . $language)}}
    </a>
    <ul>
        @foreach(config('app.locales') as $locale => $locale_language)
            @continue($language == $locale)
            <li>
                <a
                        href="{{route($route, array_merge(request()->route()->parameters(), ['locale' => $locale], request()->query()))}}"
                >
                    <img src="{{asset(config('app.locales_images.' . $locale))}}" alt="{{config('app.locales.' . $language)}}" srcset=""
                    />
                    {{$locale_language}}
                </a>
            </li>
        @endforeach
    </ul>
</li>