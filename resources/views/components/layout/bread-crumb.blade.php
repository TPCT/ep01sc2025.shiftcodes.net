<div class="glossary">
    @foreach($bread_crumbs as $segment_url => $segment_text)
            @if ($loop->last)
                <span> / {{__('site.') . $segment_text}}</span>
            @else
                <a href="{{$segment_url}}">
                    @if(!$loop->first) /@endif {{__('site.' . $segment_text)}}
                </a>
            @endif
    @endforeach
</div>