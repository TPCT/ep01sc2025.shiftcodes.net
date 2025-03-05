@if ($slider)
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @foreach($slider->slides as $slide)
                <div class="swiper-slide">
                    @if ($slide->link)
                        <a href="{{$slide->link}}">
                    @endif
                            <picture class="slider-desktop-image">
                                <x-curator-glider
                                        :media="$slide->image_id"
                                />
                            </picture>

                            <picture class="slider-mobile-image">
                                <x-curator-glider
                                        :media="$slide->mobile_image_id ?? $slide->image_id"
                                />
                            </picture>
                    @if($slider->link)
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
@endif


@push('script')
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            spaceBetween: 30,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            centerSlide: "true",
            fade: "true",
            autoplay: {
                delay: 5000, // set the delay in milliseconds
            },
        });
    </script>
@endpush
