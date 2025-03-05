@if ($media)
    @if (str($media->type)->contains('image'))
        <img
            src="{{ $source }}"
            alt="{{ $media->alt }}"
            @if ($sourceSet)
                srcset="{{ $sourceSet }}"
                sizes="{{ $sizes }}"
            @endif
            {{ $attributes->filter(fn ($attr) => $attr !== '') }}
        />
    @elseif(str($media->type)->contains('video'))
        <video autoplay muted loop>
            <source src="{{ $source }}" type="{{$media->type}}">
            @lang('site.Your browser does not support the video tag.')
        </video>
    @else
        <x-curator::document-image
            label="{{ $media->name }}"
            icon-size="xl"
            {{ $attributes->merge(['class' => 'p-4']) }}
        />
    @endif
@endif
