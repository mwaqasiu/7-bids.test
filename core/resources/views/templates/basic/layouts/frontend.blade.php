<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">


    <title> {{ $general->sitename(__($pageTitle)) }}</title>
    @include('partials.seo')
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/animate.css') }}">
    <link rel="stylesheet" href="{{asset('assets/global/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/global/css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/lightbox.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/owl.min.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/headline.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/main.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/color.php')}}?color={{ $general->base_color }}">

    <!--<link rel="icon" type="image/png" href="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}" sizes="16x16">-->

    @stack('style-lib')

    @stack('style')
    @livewireStyles
    
    <script>
        if(localStorage.getItem("lightmodedata") == null) {
            localStorage.setItem("lightmodedata", "mode");
            $('head').append(`<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/lightmode.css') }}">`);
        }
    </script>
    <script src="https://cloud.ccm19.de/app.js?apiKey=00674c0f4d54871ba82fade7999d171432408559ad4c4507&amp;domain=6516a116857d65f1fc0314b5" referrerpolicy="origin"></script>
</head>

<body>

    @stack('fbComment')

    <main class="main-body">

        @include($activeTemplate.'partials.preloader')

        <div class="overlay"></div>
        <a href="#" class="scrollToTop"><i class="las la-angle-up"></i></a>

        @if (!request()->routeIs('user.login') && !request()->routeIs('user.register') && !request()->routeIs('user.authorization') && !request()->routeIs('user.password.request') && !request()->routeIs('user.password.code.verify') && !request()->routeIs('user.password.reset'))
            @include($activeTemplate.'partials.header')
        @endif

        @if (!request()->routeIs('home') && !request()->routeIs('user.login') && !request()->routeIs('user.register') && !request()->routeIs('user.authorization') && !request()->routeIs('user.password.request') && !request()->routeIs('user.password.code.verify') && !request()->routeIs('user.password.reset') && !request()->routeIs('vendor.profile') && !request()->routeIs('admin.profile.view') && !request()->routeIs('merchant.profile.view'))
            @include($activeTemplate.'partials.breadcrumb')
        @endif

        @yield('content')

        @if (!request()->routeIs('user.login') && !request()->routeIs('user.register') && !request()->routeIs('user.authorization') && !request()->routeIs('user.password.request') && !request()->routeIs('user.password.code.verify') && !request()->routeIs('user.password.reset'))
            @include($activeTemplate.'partials.footer')
        @endif
    </main>

    @php
        $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
    @endphp

    @if(@$cookie->data_values->status && !session('cookie_accepted'))
        <div class="cookies-card bg--default text-center cookies--dark radius--10px">
            <div class="cookies-card__icon">
                <i class="fas fa-cookie-bite"></i>
            </div>
            <p class="mt-4 cookies-card__content">
                @if(session('lang') == "de")
                    @php echo @$cookie->data_values->german_description @endphp
                @else
                    @php echo @$cookie->data_values->description @endphp
                @endif
                <a class="d-inline" href="{{ @$cookie->data_values->link }}">@lang('Read Policy')</a>
            </p>
            <div class="cookies-card__btn mt-4">
                <button class="cookies-btn btn--base w-100" id="allow-cookie">@lang('Allow')</button>
            </div>
        </div>
    @endif


    <script src="{{asset('assets/global/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/global/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/rafcounter.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/lightbox.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/owl.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/masonry.pkgd.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/countdown.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/headline.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue.'js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('script-lib')

    @stack('script')

    @include('partials.plugins')

    @include('partials.notify')

    <script>
    
        (function ($) {
            "use strict";

            if(localStorage.getItem("lightmodedata") != null && localStorage.getItem("lightmodedata") != "null") {
                $('head').append(`<link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/lightmode.css') }}">`);
            }

            $(".langSel").on("change", function() {
                window.location.href = "{{route('home')}}/change/"+$(this).val() ;
            });

            var url = `{{ route('cookie.accept') }}`;

            $('#allow-cookie').on('click', function(){
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (response) {
                        $('.cookies-card').hide();
                    }
                });
            });

            var langsetval = "{{ $countrycode }}";
            var langcount = "{{ $countlist }}";

            setTimeout(function () {
                if(langcount == 0) {
                    if(langsetval == "de") {
                        var langmod = $('#languageModal');
                        langmod.modal('show');
                    }
                    if(langsetval == "at") {
                        var langmod = $('#languageModal');
                        langmod.modal('show');
                    }
                }
            }, 2000);

        })(jQuery);
    </script>
    @livewireScripts
    
    <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64bd6e5dcc26a871b02abc9c/1h61ve7q5';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>