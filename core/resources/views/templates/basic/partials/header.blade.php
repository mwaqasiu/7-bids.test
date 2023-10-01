@php
    $header = getContent('header.content', true);
    $news = getContent('news.data', true);
	$banner = getContent('banner.content', true);
    $features = getContent('feature.element');
@endphp
    <!-- Header -->
<div class="header-bg py-1">
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
                               style="font-size: 14px; font-family: 'Jost', sans-serif">{{ auth()->user()->firstname }}</a>
                        @endauth

                        @auth('merchant')
                            <a href="{{ route('merchant.dashboard') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">@lang('Seller Dashboard')</a>
                        @endauth

                        @if (!auth()->check() && !auth()->guard('merchant')->check())
                            <a href="{{ route('user.login') }}">@lang('User Login')</a>
                            <span class="userregisterslash"><i class="fas la-user-plus"></i></span>
                            <a href="{{ route('user.register') }}">@lang('Register')</a>
                        @endif


                    </div>
                    
                    <div class="d-none d-md-block lightdarksectionitem" style="margin-left: 10px; border: 1px solid #0E86D4; padding-right: 3px; border-radius: 8px;">
                        <input type="checkbox" class="checkbox" checked id="formcheckinput" onchange="switchmode()">
                        <label for="formcheckinput" class="checkbox-label">
                            <i class="fas fa-moon"></i>
                            <i class="fas fa-sun"></i>
                            <span class="ball"></span>
                        </label>
                    </div>

                    
                    <!-- <select class="language langSel">
                    @foreach($language as $item)
                        <option value="{{$item->code}}" @if(session('lang')==$item->code)
                            selected
                        @endif>{{ __($item->name) }}</option>
                    @endforeach
                    </select> -->
                </div>
                
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2 align-items-center">
               {{-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat" viewBox="0 0 16 16">
                    <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
                </svg> --}}
                <a href="/sellwithus" class="sellwithusatag">
                    <div style="font-size: 12px; line-height: 13px; font-weight: 600">
                        @lang($banner->data_values->link)
                    </div>
                </a>
            </div>
            <div class="Sbids-Logo">
                <a href="/">
                    <img id="logoImage" style="width: 60px; height: 60px;" src="{{ asset('assets/images/logoIcon/7-bids-logo-dm.png') }}">
                </a>
            </div>
            <div class="d-flex gap-2 align-items-center">
                <button type="button" class="border-0 bg-transparent top-search-icon" data-bs-toggle="modal" data-bs-target="#exampleModal" style="padding: 0; margin: 0; height: 25px; color: rgba(var(--bs-link-color-rgb),var(--bs-link-opacity,1));">
                    <!--<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">-->
                    <!--    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>-->
                    <!--</svg>-->
                    <i class="las la-search"></i>
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered bg-transparent">
                        <div class="modal-content bg-transparent" style="border: 0 !important">
                            <div class="d-flex justify-content-center align-items-center">
                                <form action="{{ route('product.search') }}" class="search-form" style="border: 0 !important">
                                    <div class="input-group input--group searchkeybtngroup" style="position: relative;">
                                        <input type="text" class="form-control"
                                               style="border: 0 !important; border-radius: unset; border-left: 0 !important; height: -1px !important; color: #fff !important; background-color: transparent !important; font-size:18px;"
                                               id="search_input_text_product"
                                               name="search_key" value="{{ request()->search_key }}"
                                               placeholder="@lang('Search Item')">
                                        <div class="searchtextclearbtn"
                                             style="background: transparent; position: absolute; right: 92px; top: 21px; z-index: 30; transform: translateY(-50%); cursor: pointer;">
                                            <i class="las la-times" style="font-size: 16px;"></i>
                                        </div>
                                        <button type="submit" class="cmn--btn" style="border: 0 !important;">
                                            <i class="las la-search" style="font-size:21px"></i></button>
                                        @if (auth()->check())
                                            <button type="button" onclick="bellbtnwithsearch()"
                                                    class="cmn--btn" style="border: 0 !important" ><i
                                                    class="las la-bell" style="font-size:23px"></i></button>
                                        @else
                                            <button type="button" onclick="bellbtnwithlogin()" style="border: 0 !important" class="cmn--btn">
                                                <i class="las la-bell" style="font-size:23px"></i></button>
                                        @endif
                                        <!--<div class="search_bottom_list_view">-->
                                        <!--    <p>Type your keyword and receive a notification when new results match your search.</p>-->
                                        <!--</div>-->
                                    </div>
                                </form>
                                <div style="display: none;">
                                    <form action="{{ route('user.searchitem.save') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="search_keys" id="form_search_keys" value=""/>
                                        <input type="submit" id="searchitemsubmitbtn" value="submit"/>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (auth()->check())
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.wishlist', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                @if($wishlist_count > 0)
                                    <i class="lar la-heart" style="color: red !important;"></i>
                                @else
                                    <i class="lar la-heart"></i>
                                @endif
                            </a>
                        </span>
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                <i class="las la-shopping-bag"></i>
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
                                    <i class="lar la-heart" style="color: red !important;"></i>
                                @else
                                    <i class="lar la-heart"></i>
                                @endif
                            </a>
                        </span>
                    <span>
                            <a style="position: relative;"
                               href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}">
                                <i class="las la-shopping-bag"></i>
                                @if($shopping_count > 0)
                                    <span
                                        style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>
                                @endif
                            </a>
                        </span>
                @endif

                @auth
                    <a class="bonussection" href="{{ route('user.bonus') }}">
                        <label class="bonus-text">
                            {{ $bonuscount }} pts
                        </label>
                    </a>
                @endauth
                
            </div>
        </div>
    </div>
    <hr class="my-1">
    <div class="container headernavbardesktop">
        <div class="header-wrapper justify-content-center">
            <div class="menu-area">
                <div class="menu-close">
                    <i class="las la-times"></i>
                </div>
                <ul class="menu">
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('home') }}">
                            <i class="fas la-home" style="margin-right: 5px;"></i>@lang('Home')</a>
                    </li>
                    <li class="menu-underline">
                        <a class="text-uppercase" href="{{ route('auction.all') }}">
                            <i class="fas la-gavel" style="margin-right: 5px;"></i>@lang('Online Auction')</a>
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
                            <span class="userregisterslash"><i class="fas la-user-plus"></i></span>
                            <a href="{{ route('user.register') }}">@lang('Register')</a>
                        </div>
                    </div>
                @endif

                @auth
                    <div class="change-language d-md-none mt-4 justify-content-center">
                        <div class="sign-in-up">
                            <span><i class="fas la-user"></i></span>
                            <a href="{{ route('user.home') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">{{ auth()->user()->firstname }}</a>
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
    <div class="container headernavbarmobile">
        <div class="header-wrapper justify-content-center">
            <div class="menu-area">
                <div class="menu-close">
                    <i class="las la-times"></i>
                </div>
                <ul class="menu">
                    <li class="">
                        <a class="text-uppercase" href="{{ route('home') }}">
                            <i class="fas la-home" style="margin-right: 5px;"></i>@lang('Home')</a>
                    </li>
                    <li class="">
                        <a class="text-uppercase" href="{{ route('auction.all') }}">
                            <i class="fas la-gavel" style="margin-right: 5px;"></i>@lang('Online Auction')</a>
                    </li>
                    <li class="">
                        <a class="text-uppercase" href="{{ route('product.all') }}">
                            <i class="fas la-store" style="margin-right: 5px;"></i>@lang('Buy It Now')</a>
                    </li>
                    @if (auth()->check())
                        <li class="">
                            <a class="text-uppercase" href="{{ route('user.bonus') }}">
                                <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                        </li>
                    @else
                        <li class="">
                            <a class="text-uppercase" href="{{ route('shopping-cart.unadd') }}">
                                <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                        </li>
                    @endif
                    @if($charitydata->data_values->pageflag == 1)
                        <li class="">
                            <a class="text-uppercase" href="{{ route('charity') }}">
                                <i class="fas la-hand-holding-heart"
                                   style="margin-right: 5px;"></i>@lang('Charity Project')</a>
                        </li>
                    @endif
                    <li class="">
                        <a class="text-uppercase" href="{{ route('privatesales') }}">
                            <i class="fas la-binoculars" style="margin-right: 5px;"></i>
                            @lang('Private Sales')
                        </a>
                    </li>
                    @if($charitydata->data_values->blogpageflag == 1)
                        <li class="">
                            <a class="text-uppercase" href="{{ route('blog') }}">
                                <i class="fas la-comment-dots" style="margin-right: 5px;"></i>@lang('Blog')</a>
                        </li>
                    @endif
                    <li class="">
                        <a class="text-uppercase" href="{{ route('contact') }}">
                            <i class="fas la-envelope" style="margin-right: 5px;"></i>@lang('Contact')</a>
                    </li>
                    <li class="">
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
                            <span class="userregisterslash"><i class="fas la-user-plus"></i></span>
                            <a href="{{ route('user.register') }}">@lang('Register')</a>
                        </div>
                    </div>
                @endif

                @auth
                    <div class="change-language d-md-none mt-4 justify-content-center">
                        <div class="sign-in-up">
                            <span><i class="fas la-user"></i></span>
                            <a href="{{ route('user.home') }}"
                               style="font-size: 14px; font-family: 'Jost', sans-serif">{{ auth()->user()->firstname }}</a>
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
                           <button type="submit" class="cmn--btn languagebuttonclass" style="width: 100%;">Fortfahren</button>
                       </div>
                       <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
                           <input type="hidden" value="{{ $ipaddress }}" name="ipaddress" />
                           <p style="margin: 0; padding: 0; font-size: 24px; margin-bottom: 20px; text-align: center;">@lang('Other language')?</p>
                           <select class="lagnuagesettingselect language" name="germanlang" style="height: 32px; padding-left: 5px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 4px; margin-left: 15px; outline: none; background: transparent; color: #fff;">
                               <option style="background: #001631;" value="de" selected>{{ __("German") }}</option>
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
        
        if(localStorage.getItem("lightmodedata") != null && localStorage.getItem("lightmodedata") != "null") {
            document.getElementById("formcheckinput").checked = false;
            document.getElementById('logoImage').src = "{{ asset('assets/images/logoIcon/7-bids-logo-lm.png') }}"
        }
        
        function bellbtnwithlogin() {
            iziToast['warning']({
                message: "@lang('You must logged in.')",
                position: "topRight"
            });
        }
        
        function bellbtnwithsearch() {
            if(document.getElementById('search_input_text_product').value.trim() == "") {
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
            if(!document.getElementById("formcheckinput").checked) {
                await localStorage.setItem("lightmodedata", "mode");
                await document.location.reload(true);
            }
            else {
                await localStorage.setItem("lightmodedata", null);
                await document.location.reload(true);
            }
        }
        
        async function switchmode2() {
            if(!document.getElementById("formcheckinput2").checked) {
                await localStorage.setItem("lightmodedata", "mode");
                await document.location.reload(true);
            }
            else {
                await localStorage.setItem("lightmodedata", null);
                await document.location.reload(true);
            }
        }
        
        
        (function ($) {
            "use strict";
            
            $('.searchtextclearbtn').css('display', 'none');
            
            $('.changelangbtn').on('click', function() {
                var langmodals = $('#languageModal');
                langmodals.modal('show');
            });
            
            $('.searchtextclearbtn').on('click', function() {
                $('#search_input_text_product').val("");
                $('.searchtextclearbtn').css('display', 'none');
            });
            
            $('#search_input_text_product').on('input', function() {
                if($('#search_input_text_product').val() == "") {
                    $('.searchtextclearbtn').css('display', 'none');
                } else {
                    $('.searchtextclearbtn').css('display', 'block');
                }
            });
            
            $('.lagnuagesettingselect').on('change', function() {
                if($(this).val() == "en") {
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
