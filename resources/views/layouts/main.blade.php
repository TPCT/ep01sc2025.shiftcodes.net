<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" style="overflow-x: hidden">
    <head>
        <title>
            @if (app(\App\Settings\General::class)->site_title)
                @hasSection('title')
                    @yield('title') -
                @endif
                {{app(\App\Settings\General::class)->site_title[app()->getLocale()] ?? config('app.name')}}
            @endif
        </title>

        <link rel="icon" type="image/x-icon" href="{{asset('/storage/' . \Awcodes\Curator\Models\Media::find(app(\App\Settings\Site::class)->fav_icon)?->path)}}"/>

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <x-layout.seo></x-layout.seo>

        <link
            href="{{asset('/css/bootstrap/bootstrap.min.css')}}"
            type="text/css"
            rel="stylesheet"
        />

        <link
            rel="stylesheet"
            type="text/css"
            href="{{asset('/js/slick-1.8.1/slick/slick.css')}}"
        />
        <link rel="stylesheet" href="{{asset('/js/slick-1.8.1/slick/slick-theme.css')}}" />
        <link rel="stylesheet" href="{{asset('/css/carousel.css')}}" />
        <link rel="stylesheet" href="{{asset('/css/fancybox.css')}}" />
        <link rel="stylesheet" href="{{asset('/css/owl.carousel.css')}}" />
        <link rel="stylesheet" href="{{asset('/css/intlTelInput.css')}}" />

        <link href="{{asset('/css/menu.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/regular.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/solid.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/brands.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/fontawesome.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/footer.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/navbar.css')}}" rel="stylesheet" type="text/css" />
        @stack('style')

        <link href="{{asset('/css/styles.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/arabic-styles.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/animate.css')}}" rel="stylesheet" type="text/css" />

        <script src="{{asset('/js/wow.js')}}"></script>
        <script>
            new WOW().init();
        </script>


        <link href="{{asset('/css/lite-youtube.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('/css/accessibility-tools.css')}}"/>
        <link rel="stylesheet" href="{{asset('/css/errors.css')}}"/>

        <script src="{{asset('/js/jquery-3.7.1.js')}}"></script>
        <script src="{{asset('/js/menu.js')}}"></script>

        <script src="{{asset('/js/slick-1.8.1/slick/slick.min.js')}}"></script>
        <script src="{{asset('/js/bootstrap/popper.min.js')}}"></script>
        <script src="{{asset('/js/bootstrap/bootstrap.bundle.min.js')}}"></script>

        <script src="{{asset('/js/carousel.umd.js')}}"></script>
        <script src="{{asset('/js/fancybox.umd.js')}}"></script>
        <script src="{{asset('/js/owl.carousel.js')}}"></script>
        <script src="{{asset('/js/intlTelInput.min.js')}}"></script>
        <script src="{{asset('/js/main.js')}}"></script>
        <script src="{{asset('/js/share-btn.js')}}"></script>
        <script src="{{asset('/js/lite-youtube.js')}}"></script>
    </head>

    <body class="{{app()->getLocale() == "ar" ? "arabic-version" : ""}}">
        <x-layout.header></x-layout.header>

        <main id="@yield('id')" class="@yield('class')">
            <div id="readspeakerDiv">
                @yield('content')
            </div>
        </main>

        <x-layout.footer></x-layout.footer>

        {!! NoCaptcha::renderJs() !!}

        @stack('script')
    </body>
</html>
