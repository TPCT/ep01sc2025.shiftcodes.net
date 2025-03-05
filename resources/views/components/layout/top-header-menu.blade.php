<div class="main-navigation--container">
    <div class="container upper-nav">
        <x-layout.language-switcher></x-layout.language-switcher>
        <div class="">
            @foreach($menu->links as $link)
                @continue(!$link->status)
                <a href="{{$link->link}}">
                    <span> {{$link->title}} </span>
                    <picture>
                        <x-curator-glider
                                :media="$link->image_id"
                                format="webp"
                                :force="true"
                                quality="5"
                        />
                    </picture>
                </a>
            @endforeach
        </div>
    </div>
</div>