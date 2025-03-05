@extends('layouts.main')

@section('title', '')

@section('id', 'Home')
@push('style')
    <link rel="stylesheet" href="{{asset('/css/home.css')}}">
@endpush

@section('content')
    <section class="home-hero-slider-container">
        <div class="home-hero-slider">
            @foreach($hero_slider->slides as $slide)
                <div class="home-hero-slide">
                    <picture class="desktop-img-slider">
                        <x-curator-glider
                                :media="$slide->image_id"
                                format="webp"
                                :force="true"
                                :quality="5"
                        />
                    </picture>
                    <picture class="mobile-img-slider">
                        <x-curator-glider
                                :media="$slide->mobile_image_id"
                                format="webp"
                                :force="true"
                                :quality="5"
                        />
                    </picture>
                    <div class="container home-hero-slide-content">
                        <h1>{{$slide->title}}</h1>
                        <h3>{{$slide->second_title}}</h3>
                        {!! $slide->description !!}
                        @if ($button_url = $slide->button_url)
                            <a href="{{$button_url}}" class="main-btn">
                                <span>{{$slide->button_text}}</span>
                                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                            fill="#DC9937" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="home-hero-slider-arrows">
            <span class="home-hero-slider-arrows-prev"><i class="fa-solid fa-chevron-left"></i></span>
            <span class="home-hero-slider-arrows-next"><i class="fa-solid fa-chevron-right"></i></span>
        </div>
    </section>

    @if ($pop_up)
        <div class="bg-card">
            <div class="fixed-registration-card">
                <div class="position-relative">
                    <picture>
                        <x-curator-glider
                                :media="$pop_up->image_id"
                                format="webp"
                                :force="true"
                                :quality="5"
                        />
                    </picture>
                    <div class="fixed-registration-card-close">
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                <div class="fixed-registration-card-content">
                    <h4>{!! $pop_up->title !!}</h4>
                    {!! $pop_up->description !!}
                    @if ($pop_up->buttons && count($pop_up->buttons) && $button = $pop_up->buttons[0])
                        <a href="{{$button['url'][app()->getLocale()]}}" class="main-btn">
                            <span> {{$button['text'][app()->getLocale()]}} </span>
                            <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                        d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                        fill="#DC9937" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <section class="home-about-iec-container" id="about-iec">
        <div class="home-bg-pattern-about-iec">
            <picture><img src="{{asset('/Assets/Pattern.svg')}}" alt="" /></picture>
        </div>
        <div class="container">
            <h2>{{$history_slider->title}}</h2>
        </div>
        <div class="home-about-iec-slider-wrapper">
            <div class="container">
                <div class="home-about-iec-content-slider--container">
                    <div class="home-about-iec-content-slider">
                        @foreach($history_slider->slides as $slide)
                            <div class="home-about-iec-content-single-slide">
                                <picture>
                                    <x-curator-glider
                                            :media="$slide->image_id"
                                            format="webp"
                                            :force="true"
                                            :quality="5"
                                    />
                                </picture>
                                <div class="home-about-iec-content-single-slide-content">
                                    <h2>{{$slide->title}}</h2>
                                    <h4>
                                        {{$slide->second_title}}
                                    </h4>
                                    {!! $slide->description !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="home-about-iec-content-slider-arrows">
                          <span class="home-about-iec-content-slider-prev">
                                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path
                                          d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                          fill="#fff" />
                                </svg>
                          </span>
                          <span class="home-about-iec-content-slider-next">
                            <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path
                                      d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                      fill="#fff" />
                            </svg>
                          </span>
                    </div>
                </div>
            </div>
            <span class="home-about-iec-timeline-line"></span>
            <div class="container">
                <div class="home-about-iec-timeline-dates-slider">
                    @foreach($history_slider->slides as $slide)
                        <div class="home-about-iec-timeline-dates-slide">
                            <h3>{{$slide->title}}</h3>
                            <span></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @if ($floating_cards)
        <div class="home-floating-cards-section">
            <div class="container">
                <div class="home-floating-cards-grid">
                    @foreach($floating_cards as $floating_card)
                        <div class="home-floating-single-card">
                            <picture>
                                <x-curator-glider
                                        :media="$floating_card->image_id"
                                        format="webp"
                                        :force="true"
                                        :quality="5"
                                />
                            </picture>
                            <h4>{{$floating_card->title}}</h4>
                            <div class="home-floating-single-card-content">
                                {!! $floating_card->description !!}
                                @if ($floating_card->buttons && count($floating_card->buttons) && $button = $floating_card->buttons[0])
                                    <a href="{{$button['url'][app()->getLocale()]}}" class="main-btn">
                                        <span> {{$button['text'][app()->getLocale()]}} </span>
                                        <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                                    fill="#DC9937" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($iec_in_numbers)
                <section class="home-iec-in-numbers-container container">
                    <h2>{{$iec_in_numbers->title}}</h2>
                    <div class="home-iec-in-numbers-grid">
                        @foreach($iec_in_numbers->features as $feature)
                            <div class="home-iec-in-numbers-card">
                                <picture>
                                    <x-curator-glider
                                            :media="$feature->image_id"
                                            format="webp"
                                            :force="true"
                                            :quality="5"
                                    />
                                </picture>
                                <div class="home-iec-in-numbers-card-content">
                                    <h4>{{$feature->title}}</h4>
                                    <h1>{{$feature->second_title}}</h1>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    @endif

    @if ($promoted_programs->count())
        <section class="container">
            <div class="home-educational-Programs-section" id="educational-programs">
                <div class="home-educational-Programs-controllers">
                    <h2>@lang('site.Educational Programs')</h2>
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($promoted_programs as $program)
                            <button class="nav-link @if($loop->index == 0) active @endif"
                                    id="v-pills-{{$loop->index}}" data-bs-toggle="pill" data-bs-target="#pills-{{$loop->index}}"
                                    type="button" role="tab" aria-controls="pills-{{$loop->index}}" aria-selected="true">
                                {{$program->title}}
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="home-educational-Programs-content">
                    <div class="tab-content" id="v-pills-tabContent">
                        @foreach($promoted_programs as $program)
                            <div class="tab-pane fade @if($loop->index == 0) show active @endif" id="pills-{{$loop->index}}"
                                 role="tabpanel" aria-labelledby="pills-{{$loop->index}}"
                                 tabindex="0">
                                <picture>
                                    <x-curator-glider
                                            :media="$program->image_id"
                                            format="webp"
                                            :force="true"
                                            :quality="5"
                                    />
                                </picture>
                                <div class="home-educational-Programs-content-inner">
                                    <h3>{{$program->title}}</h3>
                                    {!! $program->description !!}
                                    <a href="{{route('educational-programs.show', ['educational_program' => $program])}}" class="main-btn">
                                        <span> @lang('site.Discover More') </span>
                                        <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                                    fill="#fff" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif


    @if ($latest_news->count())
        <section class="slider-with-progress-bar-section">
            <div class="container">
                <div class="slider-with-progress-bar--title">
                    <h2>@lang('site.Latest News & Events')</h2>
                </div>
                <div class="slider-with-progress-bar-main-slider">
                    @foreach($latest_news as $news_piece)
                        <div class="slider-with-progress-bar--single-slide">
                            <div class="slider-with-progress-bar--single-slide-img">
                                <picture>
                                    <x-curator-glider
                                            :media="$news_piece->image_id"
                                            format="webp"
                                            :force="true"
                                            :quality="5"
                                    />
                                </picture>
                            </div>
                            <div class="slider-with-progress-bar--single-slide-content">
                                <div class="slider-with-progress-bar--single-slide-date">
                                    <p>{{$news_piece->formatDate('published_at', 'd M Y')}}</p>
                                </div>
                                <div class="slider-with-progress-bar--single-slide-title">
                                    <h4>
                                        {{$news_piece->title}}
                                    </h4>
                                </div>
                                <div class="slider-with-progress-bar--single-slide-content-inner">
                                    {!! $news_piece->description !!}
                                    <a href="{{route('latest-news.show', ['latest_news' => $news_piece])}}" class="main-btn">
                                        <span> @lang('site.Read More') </span>
                                        <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                    d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                                    fill="#DC9937" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="slider-with-progress-bar--controllers">
                    <div class="slider-with-progress-bar--controllers-arrow">
            <span class="slider-with-progress-bar--controllers-arrow-prev">
              <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                        fill="#fff" />
              </svg>
            </span>
                        <span class="slider-with-progress-bar--controllers-arrow-next">
              <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                        fill="#fff" />
              </svg>
            </span>
                    </div>
                    <div class="slider-with-progress-bar--progress">
                        <span class="" id="slider-progress" data-progress="0%"></span>
                    </div>
                    <a class="main-btn" href="{{route('latest-news.index')}}">
                        <span> @lang('site.Discover all') </span>
                        <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                    d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                                    fill="#2c362b" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <section class="testimonials-container-slider">
        @if ($virtual_tour)
            <div class="home-virtual-tour-section">
                <div class="container">
                    <div class="home-virtual-tour-section-inner">
                        <h2>{{$virtual_tour->title}}</h2>
                        <h4>{{$virtual_tour->second_title}}</h4>
                        {!! $virtual_tour->description !!}
                        <div class="home-virtual-tour-section-inner-scan">
                            <h4>@lang('site.Or Scan QR Code Virtual Tour')</h4>
                            <picture>
                                <x-curator-glider
                                        :media="$virtual_tour->image_id"
                                        format="webp"
                                        :force="true"
                                        :quality="5"
                                />
                            </picture>
                        </div>
                    </div>
                    <iframe width="560" height="315px"
                            src="{{app(\App\Settings\Site::class)->virtual_tour_iframe}}"
                    >
                    </iframe>
                </div>
            </div>
        @endif
        @if ($testimonials_slider)
                <div class="container">
                    <h2>{{$testimonials_slider->title}}</h2>
                    <div class="testimonials-container-main-slider">
                        @foreach($testimonials_slider->slides as $slide)
                            <div class="single-testimonials-slide">
                                <div class="single-testimonials-slide--inner">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/quotes.svg')}}" alt="" />
                                    </picture>

                                    {!! $slide->description !!}
                                </div>
                                <div class="home-testimonials-slide--author">
                                    <picture>
                                        <x-curator-glider
                                                :media="$slide->image_id"
                                                format="webp"
                                                :force="true"
                                                :quality="5"
                                        />
                                    </picture>
                                    <div class="">
                                        <h4>{{$slide->title}}</h4>
                                        <p>{{$slide->second_title}}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="slider-with-progress-bar--controllers">
                        <div class="slider-with-progress-bar--controllers-arrow">
            <span class="testimonials--controllers-arrow-prev">
              <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                        fill="#fff" />
              </svg>
            </span>
                            <span class="testimonials--controllers-arrow-next">
              <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                        d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z"
                        fill="#fff" />
              </svg>
            </span>
                        </div>
                        <div class="slider-with-progress-bar--progress">
                            <span class="" id="testimonials-slider-progress" data-progress="0%"></span>
                        </div>
                    </div>
                </div>
        @endif
    </section>

    <section class="home-connects-section container">
        <h2>@lang('site.Connects')</h2>

        <div class="">
            @foreach($connects as $connect)
                <div class="home-connects-card">
                    <picture>
                        <x-curator-glider
                                :media="$connect->image_id"
                                format="webp"
                                :force="true"
                                :quality="5"
                        />
                    </picture>
                    <div class="home-connects-card-content">
                        <div>
                            <h4>{{$connect->title}}</h4>
                            {!! $connect->description !!}
                        </div>
                        @if ($connect->buttons && count($connect->buttons) && $button = $connect->buttons[0])
                            <a href="{{$button['url'][app()->getLocale()]}}" class="main-btn">
                                {{$button['text'][app()->getLocale()]}}
                                <svg width="17" height="13" viewBox="0 0 17 13" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                            d="M1.33917e-06 6.50077C1.31764e-06 6.25456 0.107461 6.01844 0.298738 5.84434C0.490016 5.67024 0.749444 5.57243 1.01995 5.57243L13.5144 5.57243L9.13537 1.58753C8.94376 1.41313 8.83611 1.17659 8.83611 0.929952C8.83611 0.683314 8.94376 0.446777 9.13537 0.272377C9.32698 0.0979774 9.58686 -3.05597e-07 9.85783 -3.29286e-07C10.1288 -3.52976e-07 10.3887 0.0979773 10.5803 0.272377L16.7 5.84242C16.7951 5.92867 16.8705 6.03115 16.922 6.14399C16.9735 6.25684 17 6.37782 17 6.5C17 6.62218 16.9735 6.74316 16.922 6.85601C16.8705 6.96885 16.7951 7.07133 16.7 7.15758L10.5803 12.7276C10.4854 12.814 10.3728 12.8825 10.2488 12.9292C10.1249 12.9759 9.99201 13 9.85783 13C9.72366 13 9.5908 12.9759 9.46684 12.9292C9.34288 12.8825 9.23024 12.814 9.13537 12.7276C9.04049 12.6413 8.96523 12.5388 8.91389 12.4259C8.86254 12.3131 8.83611 12.1922 8.83611 12.07C8.83611 11.9479 8.86254 11.827 8.91389 11.7142C8.96523 11.6013 9.04049 11.4988 9.13537 11.4125L13.5144 7.42911L1.01995 7.42912C0.749444 7.42912 0.490016 7.33131 0.298738 7.15721C0.107461 6.98311 1.36069e-06 6.74699 1.33917e-06 6.50077Z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // Ensure the card and background are visible when the page is reloaded
            $(".fixed-registration-card, .bg-card").show();

            // Disable scrolling when the card is open
            $("html").css("overflow", "hidden");

            $(".fixed-registration-card-close").click(function () {
                // Hide both the card and the background
                $(".fixed-registration-card, .bg-card").hide();

                // Enable scrolling when the card is closed
                $("html").css("overflow", "auto");
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            const $carousel = $(".home-about-iec-timeline-dates-slider");
            const $carousel2 = $(".home-about-iec-content-slider");

            // Clones slides if the count is less than the desired number
            function cloneSlidesIfNeeded($carousel, $carousel2, minSlides = 11) {
                const slideCount = $carousel.children().length;
                const slideCount2 = $carousel2.children().length;

                if (slideCount < minSlides) {
                    const slides = $carousel.children();
                    const slides2 = $carousel2.children();
                    const neededSlides = minSlides - slideCount;
                    const fragment = $(document.createDocumentFragment());
                    const fragment2 = $(document.createDocumentFragment());

                    // Clone slides to meet the minimum slide count
                    for (let i = 0; i < neededSlides; i++) {
                        const clonedSlide = slides
                            .eq(i % slideCount)
                            .clone()
                            .addClass("between-past-and-present-years-cloned-slides");
                        const clonedSlide2 = slides2
                            .eq(i % slideCount2)
                            .clone()
                            .addClass("between-past-and-present-imgs-cloned-slides");
                        fragment.append(clonedSlide);
                        fragment2.append(clonedSlide2);
                    }

                    // Append the cloned slides in a single DOM operation
                    $carousel.append(fragment);
                    $carousel2.append(fragment2);
                }
            }

            // Initializes the Slick sliders
            function initSlickSliders() {
                $carousel2.slick({
                    dots: false,
                    rtl: {{$rtl}},
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    asNavFor: $(".home-about-iec-timeline-dates-slider"),
                    nextArrow: $(".home-about-iec-content-slider-next"),
                    prevArrow: $(".home-about-iec-content-slider-prev"),
                });

                $carousel.slick({
                    focusOnSelect: true,
                    dots: false,
                    infinite: true,
                    slidesToShow: 5,
                    rtl: {{$rtl}},
                    arrows: false,
                    slidesToScroll: 1,
                    asNavFor: $(".home-about-iec-content-slider"),
                    responsive: [
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 3,
                                slidesToScroll: 1,
                            },
                        },
                        {
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                            },
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 1,
                                slidesToScroll: 1,
                            },
                        },
                    ],
                });
            }

            // Updates the content (title and description) based on the center slide
            function updateContent() {
                const $currentSlide = $(
                    ".between-past-and-present-years-slider .slick-center"
                );

                const title = $currentSlide.data("title") || "Default Title";
                const description =
                    $currentSlide.data("description") || "Default description text";

                // Update the text content in the DOM
                $("#content-title").text(title);
                $("#content-description").text(description);
            }

            // Event listener for when the slide changes
            function addSlideChangeListener() {
                $carousel.on("afterChange", function () {
                    updateContent();
                });
            }

            // Initialization
            function initialize() {
                cloneSlidesIfNeeded($carousel, $carousel2); // Ensure enough slides
                initSlickSliders(); // Initialize sliders
                updateContent(); // Initial content update
                addSlideChangeListener(); // Listen for slide changes
            }

            // Call the initialization function on document ready
            initialize();
        });
    </script>
    <script>
        $(document).ready(function () {
            // Hero Slider
            $(".home-hero-slider").slick({
                dots: true,
                infinite: true,
                rtl: {{$rtl}},
                slidesToShow: 1,
                slidesToScroll: 1,
                nextArrow: $(".home-hero-slider-arrows-next"),
                prevArrow: $(".home-hero-slider-arrows-prev"),
            });

            // ===================================================
            // Testimonials Slider with Progress Bar
            // ===================================================
            const $testimonialsSlider = $(".testimonials-container-main-slider");
            const $testimonialsProgress = $("#testimonials-slider-progress");

            $testimonialsSlider.slick({
                dots: false,
                infinite: false,
                slidesToShow: 4,
                slidesToScroll: 4,
                rtl: {{$rtl}},
                nextArrow: $(".testimonials--controllers-arrow-next"),
                prevArrow: $(".testimonials--controllers-arrow-prev"),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                        },
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        },
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });

            let totalTestimonialsSlides =
                $testimonialsSlider.slick("getSlick").slideCount;

            // Calculate total slide groups based on current slidesToShow
            function calculateTotalGroups(slider, totalSlides) {
                const slidesToShow = slider.slick("slickGetOption", "slidesToShow");
                return Math.ceil(totalSlides / slidesToShow);
            }

            // Update progress bar for testimonials
            function updateTestimonialsProgress(currentSlide, nextSlide) {
                const slidesToShow = $testimonialsSlider.slick(
                    "slickGetOption",
                    "slidesToShow"
                );
                const slidesToScroll = $testimonialsSlider.slick(
                    "slickGetOption",
                    "slidesToScroll"
                );
                const totalGroups = calculateTotalGroups(
                    $testimonialsSlider,
                    totalTestimonialsSlides
                );
                const currentGroup = Math.ceil(
                    (nextSlide + slidesToScroll) / slidesToShow
                );
                const completedPercentage = (currentGroup / totalGroups) * 100;

                $testimonialsProgress.css("width", `${completedPercentage}%`);
                $testimonialsProgress.attr(
                    "data-progress",
                    `${Math.round(completedPercentage)}%`
                );
            }

            // Initial progress bar state for testimonials
            updateTestimonialsProgress(0, 0);

            // Update progress during slide change (beforeChange)
            $testimonialsSlider.on(
                "beforeChange",
                function (event, slick, currentSlide, nextSlide) {
                    updateTestimonialsProgress(currentSlide, nextSlide);
                }
            );

            // Recalculate progress after slide change (afterChange)
            $testimonialsSlider.on(
                "afterChange",
                function (event, slick, currentSlide) {
                    updateTestimonialsProgress(currentSlide, currentSlide);
                }
            );

            // Handle window resize to recalculate progress
            $(window).on("resize", function () {
                totalTestimonialsSlides =
                    $testimonialsSlider.slick("getSlick").slideCount;
                updateTestimonialsProgress(
                    $testimonialsSlider.slick("slickCurrentSlide"),
                    $testimonialsSlider.slick("slickCurrentSlide")
                );
            });

            // ===================================================
            // Main Slider with Progress Bar
            // ===================================================
            const $mainSlider = $(".slider-with-progress-bar-main-slider");
            const $mainProgress = $("#slider-progress");

            $mainSlider.slick({
                dots: false,
                infinite: false,
                rtl: {{$rtl}},
                slidesToShow: 4,
                slidesToScroll: 4,
                nextArrow: $(".slider-with-progress-bar--controllers-arrow-next"),
                prevArrow: $(".slider-with-progress-bar--controllers-arrow-prev"),
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                        },
                    },
                    {
                        breakpoint: 991,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        },
                    },
                    {
                        breakpoint: 500,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });

            let totalMainSlides = $mainSlider.slick("getSlick").slideCount;

            // Update progress bar for main slider
            function updateMainProgress(currentSlide, nextSlide) {
                const slidesToShow = $mainSlider.slick(
                    "slickGetOption",
                    "slidesToShow"
                );
                const slidesToScroll = $mainSlider.slick(
                    "slickGetOption",
                    "slidesToScroll"
                );
                const totalGroups = calculateTotalGroups(
                    $mainSlider,
                    totalMainSlides
                );
                const currentGroup = Math.ceil(
                    (nextSlide + slidesToScroll) / slidesToShow
                );
                const completedPercentage = (currentGroup / totalGroups) * 100;

                $mainProgress.css("width", `${completedPercentage}%`);
                $mainProgress.attr(
                    "data-progress",
                    `${Math.round(completedPercentage)}%`
                );
            }

            // Initial progress bar state for main slider
            updateMainProgress(0, 0);

            // Update progress during slide change (beforeChange)
            $mainSlider.on(
                "beforeChange",
                function (event, slick, currentSlide, nextSlide) {
                    updateMainProgress(currentSlide, nextSlide);
                }
            );

            // Recalculate progress after slide change (afterChange)
            $mainSlider.on("afterChange", function (event, slick, currentSlide) {
                updateMainProgress(currentSlide, currentSlide);
            });

            // Handle window resize to recalculate progress
            $(window).on("resize", function () {
                totalMainSlides = $mainSlider.slick("getSlick").slideCount;
                updateMainProgress(
                    $mainSlider.slick("slickCurrentSlide"),
                    $mainSlider.slick("slickCurrentSlide")
                );
            });
        });
    </script>
@endpush