<footer class="main-footer-container">
    <div class="container footer-section">
        @foreach($menu->links as $child)
            @continue(!$child->status)
            <div class="footer-item">
                <div class="footer-item-header">
                    <h5>{{$child->title}}</h5>

                    @if ($child->has_children())
                        <button class="toggleButton mobile-responsive" data-target="about">
                            <i class="fa-solid fa-plus"></i>
                            <i class="fa-solid fa-minus" style="display: none"></i>
                        </button>
                    @endif
                </div>

                @if ($child->has_children())
                    <ul id="about">
                        @foreach($child->children as $grandson)
                            @continue(!$grandson->status)
                            <li>
                                <a href="{{$grandson->link}}">{{$grandson->title}}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach

        @if (app(\App\Settings\Site::class)->app_store_link || app(\App\Settings\Site::class)->play_store_link || app(\App\Settings\Site::class)->bar_code_image)
                <div class="footer-item">
                    <div class="footer-item-header">
                        <h5>@lang('site.Download Application')</h5>
                        <button class="toggleButton mobile-responsive" data-target="about">
                            <i class="fa-solid fa-plus"></i>
                            <i class="fa-solid fa-minus" style="display: none"></i>
                        </button>
                    </div>
                    <ul id="about">
                        @if($app_store_link = app(\App\Settings\Site::class)->app_store_link )
                            <li>
                                <a href="{{$app_store_link}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Apps Icons/Badge-1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            </li>
                        @endif

                        @if ($play_store_link = app(\App\Settings\Site::class)->play_store_link)
                            <li>
                                <a href="{{$play_store_link}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Apps Icons/Badge.svg')}}" alt="" />
                                    </picture>
                                </a>
                            </li>
                        @endif

                        @if ( $qr_code_image = app(\App\Settings\Site::class)->bar_code_image)
                            <li>
                                <div class="qr-scan mt-4">
                                    <h5>@lang('site.Or Scan The Barcode')</h5>
                                    <a href="#">
                                        <picture>
                                            <x-curator-glider
                                                    :media="(int)app(\App\Settings\Site::class)->bar_code_image"
                                                    format="webp"
                                                    :force="true"
                                                    quality="5"
                                            />
                                        </picture>
                                    </a>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            @endif
            @if(app(\App\Settings\Site::class)->phone || app(\App\Settings\Site::class)->email || app(\App\Settings\Site::class)->p_o_box[app()->getLocale()])
                <div class="footer-item">
                    <div class="footer-item-header">
                        <h5>Stay In Touch</h5>
                        <button class="toggleButton mobile-responsive" data-target="about">
                            <i class="fa-solid fa-plus"></i>
                            <i class="fa-solid fa-minus" style="display: none"></i>
                        </button>
                    </div>
                    <ul id="about">
                        @if ($phone = app(\App\Settings\Site::class)->phone)
                            <li>
                                <a href="tel:{{$phone}}" class="d-flex align-items-center gap-1 flex-wrap">
                                    <p>@lang('site.Phone'):</p>
                                    <span class="text-decoration-underline" dir="ltr">{{$phone}}</span>
                                </a>
                            </li>
                        @endif

                        @if ($email = app(\App\Settings\Site::class)->email)
                            <li>
                                <a href="mailto:{{$email}}" class="d-flex align-items-center gap-1 flex-wrap">
                                    <p>@lang('site.Email'):</p>
                                    <span>{{$email}}</span>
                                </a>
                            </li>
                        @endif

                        @if ($po_box = @app(\App\Settings\Site::class)->p_o_box[app()->getLocale()])
                            <li>
                                <a href="#" class="d-flex align-items-center gap-1 flex-wrap">
                                    <p>@lang('site.PO Box'): {{$po_box}}</p>
                                </a>
                            </li>
                        @endif

                        @if ($fax = app(\App\Settings\Site::class)->fax)
                            <li>
                                <a href="fax:{{$fax}}" class="d-flex align-items-center gap-1 flex-wrap">
                                    <p>@lang('site.Fax'):</p>
                                    <span class="text-decoration-underline" dir="ltr">{{$fax}}</span>
                                </a>
                            </li>
                        @endif

                        <li class="d-flex justify-content-between gap-2">
                            <p>@lang('site.Locations'):</p>
                            <div class="location-links d-flex flex-column gap-2">
                                @foreach($branches as $branch)
                                    <a href="{{$branch->location}}" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center gap-1 justify-content-between ">
                                        <p>
                                            {{$branch->title}},@lang('site.Get directions')
                                            <picture>
                                                <img src="{{asset('/Assets/Icons/General/get-directions-button 3.svg')}}" alt="" />
                                            </picture>
                                        </p>

                                    </a>
                                @endforeach
                            </div>
                        </li>
                        <li class="footer-stay-in-touch">
                            @if ($twitter = app(\App\Settings\Site::class)->twitter_link)
                                <a href="{{$twitter}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/twitter 1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            @endif
                            @if($facebook = app(\App\Settings\Site::class)->facebook_link)
                                <a href="{{$facebook}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/facebook-app-symbol 1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            @endif
                            @if ($linkedin = app(\App\Settings\Site::class)->linkedin_link)
                                <a href="{{$linkedin}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/linkedin 1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            @endif
                            @if ($youtube = app(\App\Settings\Site::class)->youtube_link)
                                <a href="{{$youtube}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/youtube 1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            @endif
                            @if ($instagram = app(\App\Settings\Site::class)->instagram_link)
                                <a href="{{$instagram}}" target="_blank" rel="noopener noreferrer">
                                    <picture>
                                        <img src="{{asset('/Assets/Icons/General/instagram 1.svg')}}" alt="" />
                                    </picture>
                                </a>
                            @endif

                        </li>
                    </ul>


                </div>
            @endif
    </div>
    <div class="school-accreditations container">
        {!! app(\App\Settings\Site::class)->footer_description[app()->getLocale()] !!}
        <div class="school-logos d-flex justify-content-between flex-wrap">
            @foreach(app(\App\Settings\Site::class)->footer_logo as $logo)
                <picture>
                    <x-curator-glider
                        :media="$logo"
                        format="webp"
                        :force="true"
                        quality="5"
                    />
                </picture>
            @endforeach
        </div>
    </div>
    <div class="footer-copyright-container">
        <div class="footer-copyright-container-centered">
            <div class="">
                <p>© {{date('Y')}} @lang('site.Developed by') <a href="https://dot.jo" rel="noopener norefereer">dot jo</a> @lang('site. All rights reserved.')</p>
            </div>
            <div class="">
                <a href="/privacy-policy">@lang('site.Privacy Policy')</a>
                <a href="/terms-and-conditions"> @lang('site.Terms and Conditions')</a>
            </div>
        </div>
    </div>
</footer>