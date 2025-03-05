@push('style')
    <style>
        .Header-section::before{
            background: {{$color}} !important
        }
    </style>
@endpush

<section class="Header-section">
    <div class="container">
        <picture class="pattern">
            <img src="{{asset('/Assets/Mask group.png')}}" alt="" class="mw-100 w-100">
        </picture>
        <div class="Header-section-content">
            <x-layout.bread-crumb></x-layout.bread-crumb>
            <div>
                <h1>{{$title}}</h1>
                {!! $description !!}
                {!! $html !!}
            </div>
        </div>
        @if ($image)
            <picture>
                <img src="{{$image->thumbnail_url}}" alt="" srcset="">
            </picture>
        @endif
    </div>
</section>