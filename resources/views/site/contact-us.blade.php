@extends('layouts.main')

@section('title', __('site.Contact Us'))
@section('id', 'ContactUs')

@push('style')
    <link rel="stylesheet" href="{{asset('/css/ContactUs.css')}}"/>
    <link rel="stylesheet" href="{{asset('/css/RegistrationForm.css')}}" />
@endpush

@section('content')
    <x-layout.header-image></x-layout.header-image>
    <section>
        <div class="container main-topic">
            <h3>{{$top_section->title}}</h3>
        </div>
    </section>
    <div class="contact-us-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="content-contact-us">
                        {!! $top_section->description !!}
                    </div>
                    <div class="social-contact mt-4">
                        <ul>
                            <h3>{{ $top_section->second_title }}</h3>
                            @if ($phone = app(\App\Settings\Site::class)->phone)
                                <li class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-social-contact">
                                        <picture>
                                            <img src="{{asset('/Assets/Icons/General/call 1.svg')}}" alt="" class="mw-100 w-100" />
                                        </picture>
                                    </div>

                                    <div class="info-contact-us d-flex align-items-center gap-1">
                                        <p>@lang('site.Contact us on'):</p>
                                        <p> {{$phone}}</p>
                                    </div>
                                </li>
                            @endif

                            @if ($email = app(\App\Settings\Site::class)->email)
                                <li class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-social-contact">
                                        <picture>
                                            <img src="{{asset('/Assets/Icons/General/email 1.svg')}}" alt="" class="mw-100 w-100" />
                                        </picture>
                                    </div>
                                    <div class="info-contact-us d-flex align-items-center gap-1">
                                        <p>@lang('site.E-mail'):</p>
                                        <p> {{$email}}</p>
                                    </div>
                                </li>
                            @endif

                            @if ($p_o_box = app(\App\Settings\Site::class)->p_o_box[app()->getLocale()] ?? null)
                                <li class="d-flex align-items-center gap-3 mb-3">
                                    <div class="icon-social-contact">
                                        <picture>
                                            <img src="{{asset('/Assets/Icons/General/mailbox 1.svg')}}" alt="" class="mw-100 w-100" />
                                        </picture>
                                    </div>
                                    <div class="info-contact-us d-flex align-items-center gap-1">
                                        <p>@lang('site.P_O_Box'):</p>
                                        <p> {{$p_o_box}}</p>
                                    </div>
                                </li>
                            @endif

                            @if ($branches->count())
                                <li class="d-flex align-items-center gap-3">
                                <div class="icon-social-contact">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/maps-and-flags 1.svg')}}" alt="" class="mw-100 w-100" />
                                    </picture>
                                </div>

                                <div class="content-add d-flex align-items-start gap-2">
                                    <p>@lang('site.Location'):</p>

                                    <div class="content-social d-flex align-items-center gap-3 flex-column ">
                                        @foreach($branches as $branch)
                                            <div class="info-contact-us d-flex align-items-center gap-1">
                                                <a href="{{$branch->location}}" class="text-decoration-none">
                                                    <div class="address d-flex align-items-center gap-1">
                                                        <p>{{$branch->title}}</p>
                                                        <p> ,@lang('site.Get Direction')</p>
                                                    </div>
                                                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="url(#clip0_482_19217)">
                                                            <path
                                                                    d="M33.49 15.81L18.19 0.51C17.5101 -0.17 16.49 -0.17 15.81 0.51L0.51 15.81C-0.17 16.49 -0.17 17.5101 0.51 18.19L15.81 33.49C16.49 34.17 17.5101 34.17 18.19 33.49L33.49 18.19C34.17 17.5101 34.17 16.4901 33.49 15.81ZM20.4 21.2501V17.0001H13.6V22.1001H10.2V15.3001C10.2 14.2801 10.88 13.6001 11.9 13.6001H20.4V9.35007L26.35 15.3001L20.4 21.2501Z"
                                                                    fill="#2C362B" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_482_19217">
                                                                <rect width="34" height="34" fill="white" />
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <div class="social-media">
                        @if ($twitter = app(\App\Settings\Site::class)->twitter_link)
                            <a href="{{$twitter}}">
                                <picture>
                                    <img src="{{asset('/Assets/Icons/General/twitter 1.svg')}}" alt="" class="m-100-w-100" />
                                </picture>
                            </a>
                        @endif

                        @if ($facebook = app(\App\Settings\Site::class)->facebook_link)
                            <a href="{{$facebook}}">
                                <picture>
                                    <img src="{{asset('/Assets/Icons/General/facebook-app-symbol 1.svg')}}" alt="" class="m-100-w-100" />
                                </picture>
                            </a>
                        @endif

                        @if ($linkedin = app(\App\Settings\Site::class)->linkedin_link)
                            <a href="{{$linkedin}}">
                                <picture>
                                    <img src="{{asset('/Assets/Icons/General/linkedin 1.svg')}}" alt="" class="m-100-w-100" />
                                </picture>
                            </a>
                        @endif

                        @if ($youtube = app(\App\Settings\Site::class)->youtube_link)
                            <a href="{{$youtube}}">
                                <picture>
                                    <img src="{{asset('/Assets/Icons/General/youtube 1.svg')}}" alt="" class="m-100-w-100" />
                                </picture>
                            </a>
                        @endif

                        @if ($instagram = app(\App\Settings\Site::class)->instagram_link)
                            <a href="{{$instagram}}">
                                <picture>
                                    <img src="{{asset('/Assets/Icons/General/instagram 1.svg')}}" alt="" class="m-100-w-100" />
                                </picture>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6">
                    @if($success_message = session('success'))
                        <div class="d-flex justify-content-center w-100">
                            <div class="my-3 alert alert-success w-100">
                                <span class="">{{$success_message}}</span>
                            </div>
                        </div>
                    @endif

                    <form action="" class="d-flex flex-column gap-3 p-4 m-auto" method="post">
                        @method('POST')
                        @csrf
                        <div class="head-form mb-4">
                            <h3>@lang("site.Let's Get in touch")</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-12 d-flex flex-column custom-gap form-group">
                                <label for="full-name">@lang('site.Your Name')*</label>
                                <div class="input-group has-validation">
                                    <input
                                            class="form-control m-0 @error('name') is-invalid @enderror"
                                            type="text"
                                            name="name"
                                            id="full-name"
                                            value="{{old('name')}}"
                                            placeholder="@lang('site.Your Name')"
                                    >
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-6 col-sm-6 col-12 d-flex flex-column custom-gap form-group">
                                    <label for="email">@lang('site.Your Email')*</label>
                                    <div class="input-group has-validation">
                                        <input
                                                name="email"
                                                id="email"
                                                type="email"
                                                placeholder="@lang('site.Your Email')"
                                                class="form-control m-0 @error('email') is-invalid @enderror"
                                                value="{{old('email')}}"
                                        >
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{$message}}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12 d-flex flex-column custom-gap form-group">
                                    <label for="phone">@lang('site.Mobile Number')*</label>
                                    <div class="input-group has-validation">
                                        <input
                                                name="phone"
                                                id="phone"
                                                type="text"
                                                placeholder="@lang('site.Mobile Number')"
                                                class="form-control m-0 @error('phone') is-invalid @enderror"
                                                value="{{old('phone')}}"
                                        >
                                        @error('phone')
                                            <div class="invalid-feedback">
                                                {{$message}}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-sm-6 col-12 d-flex flex-column custom-gap form-group">
                                    <label for="area-of-interest">@lang('site.Area Of Interest')*</label>
                                    <div class="input-group has-validation">
                                        <select id="area-of-interest" name="area_of_interest" class="form-select form-control m-0 @error('area_of_interest') is-invalid @enderror" aria-label="@lang('site.Area Of Interest')">
                                            <option selected>@lang('site.Open this select menu')</option>
                                            @foreach($dropdowns as $dropdown)
                                                <option value="{{$dropdown->id}}" @selected(old('area_of_interest') == $dropdown->id)>{{$dropdown->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 form-group mb-3">
                                <div class="col-12 d-flex flex-column custom-gap form-group">
                                    <label for="message">@lang('site.Message')</label>
                                    <div class="input-group has-validation">
                                    <textarea
                                            placeholder="@lang('site.Your Message')"
                                            name="message"
                                            id="message"
                                            class="form-control m-0 @error('message') is-invalid @enderror"
                                    >{{old('message')}}</textarea>
                                    @error('message')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    </div>
                                </div>
                            </div>

                            @if (app(\App\Settings\Site::class)->enable_captcha)
                                <div class="col-12 form-group mb-3">
                                    <div class="form-group">
                                        {!! \Anhskohbo\NoCaptcha\Facades\NoCaptcha::display() !!}
                                        @if ($errors->has('g-recaptcha-response'))
                                            <span class="text-danger">
                                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12 form-group mb-3">
                                <div class="main-btn" tabindex="0">
                                    <input type="submit" value="@lang('site.Submit')" class="submit-btn form-control" />
                                    <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            initialCountry: "JO",
            utilsScript:
                "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });
    </script>
@endpush