@php
    $header = getContent('header.content', true);
    $news = getContent('news.data', true);
	$banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp
    <!-- Header -->
<div class="header-bg pt-2">
    <div class="container">
        <div class="heaer-wrapper first-header-part-section">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @if(session('lang') == "de")
                        @php echo $news->data_values->description_german @endphp
                    @else
                        @php echo $news->data_values->description @endphp
                    @endif
                </div>
                <div class="change-language ">
                    <div class="sign-in-up d-none d-sm-block">
                        <span><i class="fas la-user"></i></span>
                        @auth
                            <a href="{{ route('user.home') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">@lang('Buyer Dashboard')</a>
                        @endauth

                        @auth('merchant')
                            <a href="{{ route('merchant.dashboard') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">@lang('Seller Dashboard')</a>
                        @endauth

                        @if (!auth()->check() && !auth()->guard('merchant')->check())
                            <a href="{{ route('user.login') }}">@lang('User Login')</a>
                            <span><i class="fas la-user-plus"></i></span>
                            <a href="{{ route('user.register') }}">@lang('Register')</a>
                        @endif


                    </div>
                    <!-- <select class="language langSel">
                    @foreach($language as $item)
                        <option value="{{$item->code}}" @if(session('lang')==$item->code)
                            selected
@endif>{{ __($item->name) }}</option>

                    @endforeach
                    </select> -->
                </div>
                {{--                <div class="change-language ms-auto me-3" style="margin-right: 10px;">--}}
                {{--                    <select class="language langSel">--}}
                {{--                        @foreach($language as $item)--}}
                {{--                            <option value="{{ $item->code }}" @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>--}}
                {{--                        @endforeach--}}
                {{--                    </select>--}}
                {{--                </div>--}}
                {{--                <div class="header-another-main-section" style="display: flex; flex-direction: row; justify-content: center; align-items: center;">--}}
                {{--                    <div>--}}
                {{--                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sun-bright" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-sun-bright fa-fw fa-xl"><path fill="currentColor" d="M256 0c-13.3 0-24 10.7-24 24V88c0 13.3 10.7 24 24 24s24-10.7 24-24V24c0-13.3-10.7-24-24-24zm0 400c-13.3 0-24 10.7-24 24v64c0 13.3 10.7 24 24 24s24-10.7 24-24V424c0-13.3-10.7-24-24-24zM488 280c13.3 0 24-10.7 24-24s-10.7-24-24-24H424c-13.3 0-24 10.7-24 24s10.7 24 24 24h64zM112 256c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H88c13.3 0 24-10.7 24-24zM437 108.9c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-45.3 45.3c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L437 108.9zM154.2 357.8c-9.4-9.4-24.6-9.4-33.9 0L75 403.1c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l45.3-45.3c9.4-9.4 9.4-24.6 0-33.9zM403.1 437c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-45.3-45.3c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9L403.1 437zM154.2 154.2c9.4-9.4 9.4-24.6 0-33.9L108.9 75c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l45.3 45.3c9.4 9.4 24.6 9.4 33.9 0zM256 368c61.9 0 112-50.1 112-112s-50.1-112-112-112s-112 50.1-112 112s50.1 112 112 112z" class=""></path></svg>--}}
                {{--                    </div>--}}
                {{--                    <div class="form-check form-switch" style="margin-bottom: 0;">--}}
                {{--                      <input id="formcheckinput" class="form-check-input" type="checkbox" checked role="switch" onchange="switchmode()" />--}}
                {{--                      <label class="form-check-label" for="flexSwitchCheckDefault"></label>--}}
                {{--                    </div>--}}
                {{--                    <div style="margin-left: 7px;">--}}
                {{--                        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="moon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-moon fa-fw fa-xl"><path fill="currentColor" d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z" class=""></path></svg>--}}
                {{--                    </div>--}}
                {{--                    <div class="header-another-section">--}}
                {{--                        @if (auth()->check())--}}
                {{--                            <span>--}}
                {{--                                <a href="{{ route('user.wishlist', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">--}}
                {{--                                    @if($wishlist_count > 0)--}}
                {{--                                        <i class="fas la-heart" style="color: red !important;"></i>--}}
                {{--                                    @else--}}
                {{--                                        <i class="fas la-heart"></i>--}}
                {{--                                    @endif--}}
                {{--                                </a>--}}
                {{--                            </span>--}}
                {{--                            <span>--}}
                {{--                                <a style="position: relative;" href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">--}}
                {{--                                    <i class="fas la-shopping-cart"></i>--}}
                {{--                                    @if($shopping_count > 0)--}}
                {{--                                        <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>--}}
                {{--                                    @endif--}}
                {{--                                </a>--}}
                {{--                            </span>--}}
                {{--                        @else--}}
                {{--                            <span>--}}
                {{--                                <a href="{{ route('user.wishlist', ['empty', getenv('REMOTE_ADDR')]) }}">--}}
                {{--                                    @if($wishlist_count > 0)--}}
                {{--                                        <i class="fas la-heart" style="color: red !important;"></i>--}}
                {{--                                    @else--}}
                {{--                                        <i class="fas la-heart"></i>--}}
                {{--                                    @endif--}}
                {{--                                </a>--}}
                {{--                            </span>--}}
                {{--                            <span>--}}
                {{--                                <a style="position: relative;" href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}">--}}
                {{--                                    <i class="fas la-shopping-cart"></i>--}}
                {{--                                    @if($shopping_count > 0)--}}
                {{--                                        <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>--}}
                {{--                                    @endif--}}
                {{--                                </a>--}}
                {{--                            </span>--}}
                {{--                        @endif--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--                @auth--}}
                {{--                    <div class="header-logout-button-section">--}}
                {{--                        <a href="{{ route('user.logout') }}" style="margin-left: 5px; display: flex; justify-content: center; align-items: center; background: red !important; padding: 5px 10px; border-radius: 5px;">--}}
                {{--                            <i class="las la-sign-out-alt" style="font-size: 20px; background: transparent; color: #fff !important;"></i>--}}
                {{--                        </a>--}}
                {{--                    </div>--}}
                {{--                @endauth--}}
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container py-2">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                    <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                </svg>
                <a href="{{ $banner->data_values->link_url }}" >
                    <div style="font-size: 12px; line-height: 13px; font-weight: 600">
                        @lang($banner->data_values->link)
                        <div>CHAT BOT</div>
                    </div>
                </a>
            </div>
            <div class="fw-bold ">LOGO</div>
            <div class="d-flex gap-2 align-items-center">
                <div class="d-none d-md-block">
                    <input type="checkbox" class="checkbox" checked id="formcheckinput" onchange="switchmode()">
                    <label for="formcheckinput" class="checkbox-label">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                        <span class="ball"></span>
                    </label>
                </div>
                <div class="d-none d-md-block change-language">
                    <select class="language langSel">
                        <option value="en" @if(session('lang') == 'en') selected @endif>@lang('English')</option>
                        <option value="de" @if(session('lang') == 'de') selected @endif>@lang('German')</option>
                    </select>
                </div>
{{--                            <div class="change-language rounded-circle" style="padding: 15px 12px; background-color: #d1936f">--}}
{{--                                <select class="language langSel">--}}
{{--                                    @foreach($language as $item)--}}
{{--                                        <option value="{{ $item->code }}"--}}
{{--                                                @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
                @if (auth()->check())
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.wishlist', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                @if($wishlist_count > 0)
                                    <i class="fas la-heart" style="color: red !important;"></i>
                                @else
                                    <i class="fas la-heart"></i>
                                @endif
                            </a>
                        </span>
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                <i class="fas la-shopping-cart"></i>
                                @if($shopping_count > 0)
                                    <span
                                        style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>
                                @endif
                            </a>
                        </span>
                @else
                    <span>
                            <a href="{{ route('user.wishlist', ['empty', getenv('REMOTE_ADDR')]) }}">
                                @if($wishlist_count > 0)
                                    <i class="fas la-heart" style="color: red !important;"></i>
                                @else
                                    <i class="fas la-heart"></i>
                                @endif
                            </a>
                        </span>
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}">
                                <i class="fas la-shopping-cart"></i>
                                @if($shopping_count > 0)
                                    <span
                                        style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>
                                @endif
                            </a>
                        </span>
                @endif

                @auth
                    <a href="{{ route('user.bonus') }}">
                        <label class="bonus-text">
                            {{ $bonuscount }} pts
                        </label>
                    </a>
                @endauth
            </div>
        </div>

        <div class="header-wrapper justify-content-center">
            <div class="menu-area">
                <div class="menu-close">
                    <i class="las la-times"></i>
                </div>
                <div class="d-md-none change-language changelanguageresponsive"
                     style="position: absolute; top: 10px; left: 15px;">
                    <select class="language langSel">
                        @foreach($language as $item)
                            <option value="{{$item->code}}"
                                    @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <ul class="menu">
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('home') }}">
                            <i class="fas la-home" style="margin-right: 5px;"></i>@lang('Home')</a>
                    </li>
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('auction.all') }}">
                            <i class="fas la-gavel" style="margin-right: 5px;"></i>@lang('Auction')</a>
                    </li>
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('product.all') }}">
                            <i class="fas la-store" style="margin-right: 5px;"></i>@lang('Buy It Now')</a>
                    </li>
                    @if (auth()->check())
                        <li class="menu-underline">
                            <a class="text-uppercase" href="{{ route('user.bonus') }}">
                                <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                        </li>
                    @else
                        <li class="menu-underline">
                            <a class="text-uppercase" href="{{ route('shopping-cart.unadd') }}">
                                <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                        </li>
                    @endif
                    @if($charitydata->data_values->pageflag == 1)
                        <li class="menu-underline">
                            <a class="text-uppercase" href="{{ route('charity') }}">
                                <i class="fas la-hand-holding-heart"
                                   style="margin-right: 5px;"></i>@lang('Charity Project')</a>
                        </li>
                    @endif
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('privatesales') }}">
                            <i class="fas la-binoculars" style="margin-right: 5px;"></i>
                            @lang('Private Sales')
                        </a>
                    </li>
                    @if($charitydata->data_values->blogpageflag == 1)
                        <li class="menu-underline">
                            <a class="text-uppercase" href="{{ route('blog') }}">
                                <i class="fas la-comment-dots" style="margin-right: 5px;"></i>@lang('Blog')</a>
                        </li>
                    @endif
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('contact') }}">
                            <i class="fas la-envelope" style="margin-right: 5px;"></i>@lang('Contact')</a>
                    </li>
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('faq') }}">
                            <i class="fas fa-question-circle" style="margin-right: 5px;"></i>
                            @lang('FAQ')
                        </a>
                    </li>
                </ul>
                @if (!auth()->check() && !auth()->guard('merchant')->check())
                    <div class="change-language d-md-none mt-4 justify-content-center">
                        <div class="sign-in-up">
                            <span><i class="fas la-user"></i></span>
                            <a href="{{ route('user.login') }}">@lang('User Login')</a>
                            <span><i class="fas la-user-plus"></i></span>
                            <a href="{{ route('user.register') }}">@lang('Register')</a>
                        </div>
                    </div>
                @endif

                @auth
                    <div class="change-language d-md-none mt-4 justify-content-center">
                        <div class="sign-in-up">
                            <span><i class="fas la-user"></i></span>
                            <a href="{{ route('user.home') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">@lang('Buyer Dashboard')</a>
                        </div>
                    </div>
                @endauth
                @auth('merchant')
                    <div class="change-language d-md-none mt-4 justify-content-center">
                        <div class="sign-in-up">
                            <span><i class="fas la-user"></i></span>
                            <a href="{{ route('merchant.dashboard') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">@lang('Seller Dashboard')</a>
                        </div>
                    </div>
                @endauth
                <div class="d-md-none lightmodeswitchresponsive">
                    <input type="checkbox" class="checkbox" checked id="formcheckinput" onchange="switchmode()">
                    <label for="formcheckinput" class="checkbox-label">
                        <i class="fas fa-moon"></i>
                        <i class="fas fa-sun"></i>
                        <span class="ball"></span>
                    </label>
                </div>
            </div>
            <!--<div class="header-bar d-lg-none">-->
            <!--    <span></span>-->
            <!--    <span></span>-->
            <!--    <span></span>-->
            <!--</div>-->
        </div>
    </div>
</div>
@php
    $answernotificationcount = $answerauctionNotifications + $answerproductNotifications + $userNotifications;
@endphp
<div class="footer-fixed-sec">
    <div class="container">
        <div>
            <div>
                <a href="{{ route('home') }}" class="{{ menuActive('home') }} ">
                    <i class="fas la-home"></i>
                    <span>@lang('Home')</span>
                </a>
            </div>
            @if (!auth()->check())
                <div>
                    <a href="{{ route('user.login') }}" class="{{ menuActive('user.login') }}">
                        <i class="fas la-user"></i>
                        <span>@lang('Login')</span>
                    </a>
                </div>
            @endif
            @auth
                <div style="position: relative;">
                    <a href="{{ route('user.home') }}" class="{{ menuActive('user.home') }}">
                        <i class="fas la-user"></i>
                        <span>@lang('Dashboard')</span>
                    </a>
                    @if($answernotificationcount > 0)
                        <div class="answernotificationstatus"></div>
                    @endif
                </div>
            @endauth
            @auth('merchant')
                <div>
                    <a href="{{ route('merchant.dashboard') }}" class="{{ menuActive('merchant.dashboard') }}">
                        <i class="fas la-user"></i>
                        <span>@lang('Dashboard')</span>
                    </a>
                </div>
            @endauth
            <div>
                <a href="{{ route('auction.all') }}" class="{{ menuActive('auction.all') }}">
                    <i class="fas la-gavel"></i>
                    <span>@lang('Auction')</span>
                </a>
            </div>
            <div>
                <a href="{{ route('product.all') }}" class="{{ menuActive('product.all') }}">
                    <i class="fas la-store"></i>
                    <span>@lang('Buy It Now')</span>
                </a>
            </div>
            <div>
                <div>
                    <i class="fas la-bars header-bar d-lg-none" style="margin-right: 0; width: auto; height: auto;"></i>
                    <span class="header-bar d-lg-none"
                          style="margin-right: 0; width: auto; height: auto;">@lang('Overview')</span>
                </div>
            </div>
        </div>
    </div>
</div>
{{--<div class="header-top bg--section">--}}
{{--    <div class="container">--}}
{{--        <div class="header__top__wrapper">--}}
{{--            <ul>--}}

{{--            </ul>--}}
{{--            <form action="{{ route('product.search') }}" class="search-form">--}}
{{--                <div class="input-group input--group searchkeybtngroup" style="position: relative;">--}}
{{--                    <input type="text" class="form-control" id="search_input_text_product" name="search_key" value="{{ request()->search_key }}" placeholder="@lang('Search Item')">--}}
{{--                    <div class="searchtextclearbtn" style="background: transparent; position: absolute; right: 92px; top: 20px; z-index: 1; transform: translateY(-50%); cursor: pointer;">--}}
{{--                        <i class="las la-times"></i>--}}
{{--                    </div>--}}
{{--                    <button type="submit" class="cmn--btn" style="border-right-style: solid; border-right-color: rgba(255, 255, 255, 0.2);"><i class="las la-search"></i></button>--}}
{{--                    @if (auth()->check())--}}
{{--                        <button type="button" onclick="bellbtnwithsearch()" class="cmn--btn"><i class="las la-bell"></i></button>--}}
{{--                    @else--}}
{{--                        <button type="button" onclick="bellbtnwithlogin()" class="cmn--btn"><i class="las la-bell"></i></button>--}}
{{--                    @endif--}}
{{--                    <!--<div class="search_bottom_list_view">-->--}}
{{--                    <!--    <p>Type your keyword and receive a notification when new results match your search.</p>-->--}}
{{--                    <!--</div>-->--}}
{{--                </div>--}}
{{--            </form>--}}
{{--            <div style="display: none;">--}}
{{--                <form action="{{ route('user.searchitem.save') }}" method="POST">--}}
{{--                    @csrf--}}
{{--                    <input type="hidden" name="search_keys" id="form_search_keys" value="" />--}}
{{--                    <input type="submit" id="searchitemsubmitbtn" value="submit" />--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{-- Language modal --}}
<div id="languageModal" class="modal fade">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="modal-close btn text--danger" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('german.change.lang') }}" method="POST">
                    @csrf
                    <div
                        style="display: flex; flex-direction: column; justify-content: center; align-items: center; margin: 15px 0;">
                        <div style="margin-bottom: 30px;">
                            <img src="https://www.geonames.org/flags/x/de.gif" class="languageimageclass"
                                 width="100px"/>
                        </div>
                        <p class="languagepclass"
                           style="margin: 0; padding: 0; font-size: 24px; font-weight: bold; margin-bottom: 30px; text-align: center;">
                            Ihre Sprache ist auf Deutsch festgelegt.</p>
                        <div style="width: 50%; margin-bottom: 30px;">
                            <button type="submit" class="cmn--btn languagebuttonclass" style="width: 100%;">Fortfahren
                            </button>
                        </div>
                        <div
                            style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <input type="hidden" value="{{ $ipaddress }}" name="ipaddress"/>
                            <p style="margin: 0; padding: 0; font-size: 24px; margin-bottom: 20px; text-align: center;">
                                Other language?</p>
                            <select class="lagnuagesettingselect language" name="germanlang"
                                    style="height: 32px; padding-left: 5px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 4px; outline: none; background: transparent; color: #fff;">
                                <option style="background: #001631;" value="de" selected>{{ __("Select") }}</option>
                                <option style="background: #001631;" value="en">{{ __("English") }}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End Language modal --}}

@push('script')

    <script>
        "use strict";

        if (localStorage.getItem("lightmodedata") != null && localStorage.getItem("lightmodedata") != "null") {
            document.getElementById("formcheckinput").checked = false;
            document.getElementById("formcheckinput2").checked = false;
        }

        function bellbtnwithlogin() {
            iziToast['warning']({
                message: "You must log in first.",
                position: "topRight"
            });
        }

        function bellbtnwithsearch() {
            if (document.getElementById('search_input_text_product').value.trim() == "") {
                iziToast['warning']({
                    message: "Type your keyword and receive a notification when new results match your search.",
                    position: "topRight"
                });
                // $('.search_bottom_list_view').css('display', 'block');
            } else {
                document.getElementById('form_search_keys').value = document.getElementById('search_input_text_product').value.trim();
                document.getElementById('searchitemsubmitbtn').click();
            }
        }

        // $('.search_bottom_list_view').on('click', function() {
        //     $('.search_bottom_list_view').css('display', 'none');
        // });

        async function switchmode() {
            if (!document.getElementById("formcheckinput").checked) {
                await localStorage.setItem("lightmodedata", "mode");
                await document.location.reload(true);
            } else {
                await localStorage.setItem("lightmodedata", null);
                await document.location.reload(true);
            }
        }

        async function switchmode2() {
            if (!document.getElementById("formcheckinput2").checked) {
                await localStorage.setItem("lightmodedata", "mode");
                await document.location.reload(true);
            } else {
                await localStorage.setItem("lightmodedata", null);
                await document.location.reload(true);
            }
        }

        (function ($) {
            "use strict";

            $('.searchtextclearbtn').on('click', function () {
                $('#search_input_text_product').val("");
                $('#search_input_text_product').focus();
            });

            $('.changelangbtn').on('click', function () {
                var langmodals = $('#languageModal');
                langmodals.modal('show');
            });

            $('.lagnuagesettingselect').on('change', function () {
                if ($(this).val() == "en") {
                    $('.languageimageclass').attr("src", "https://www.geonames.org/flags/x/gb.gif");
                    $('.languagepclass').text("Your language is set to English.");
                    $('.languagebuttonclass').text("Continue");
                } else {
                    $('.languageimageclass').attr("src", "https://www.geonames.org/flags/x/de.gif");
                    $('.languagepclass').text("Ihre Sprache ist auf Deutsch festgelegt.");
                    $('.languagebuttonclass').text("Fortfahren");
                }
            });
        })(jQuery);
    </script>
@endpush
<!-- Header -->
