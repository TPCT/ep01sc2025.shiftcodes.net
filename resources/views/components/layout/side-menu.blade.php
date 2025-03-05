<div class="side-slider-nav">
    @foreach($menu->links as $link)
        @continue(isset($link['inactive']) && $link['inactive'])
        <a href="{{$link['link'][$language]}}">
            <img src="{{asset('/storage/' . $link['image'])}}" alt="" srcset="">
        </a>
    @endforeach
</div>

{{--<div class="live-chat">--}}
{{--    <img src="{{asset('/images/icons/messages.png')}}" alt="" srcset="">--}}
{{--    <p>@lang('site.Live chat')</p>--}}
{{--</div>--}}
