@extends($activeTemplate.'layouts.frontend')
@php
$login = getContent('login.content', true);
@endphp
@section('content')
    <div class="header-wrapper register-header-section">
        <div class="menu-area" style="z-index: 9999;">
            <div class="menu-close">
                <i class="las la-times"></i>
            </div>
            <div class="d-md-none change-language changelanguageresponsive" style="position: absolute; top: 10px; left: 15px;">
                <select class="language langSel">
                    @foreach($language as $item)
                        <option value="{{$item->code}}" @if(session('lang')==$item->code) selected @endif>{{ __($item->name) }}</option>
                    @endforeach
                </select>
            </div>
            <ul class="menu">
                <li>
                    <a href="{{ route('home') }}">
                         <i class="fas la-home" style="margin-right: 5px;"></i>@lang('Home')</a>
                </li>
                <li>
                    <a href="{{ route('auction.all') }}">
                         <i class="fas la-gavel" style="margin-right: 5px;"></i>@lang('Auction')</a>
                </li>
                <li>
                    <a href="{{ route('product.all') }}">
                         <i class="fas la-store" style="margin-right: 5px;"></i>@lang('Buy It Now')</a>
                </li>
                @if (auth()->check())
                <li>
                   <a href="{{ route('user.bonus') }}">
                         <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                </li>
                @else
                <li>
                   <a href="{{ route('shopping-cart.unadd') }}">
                         <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
                </li>
                @endif
                <li>
                    <a href="{{ route('home') }}">
                         <i class="fas la-hand-holding-heart" style="margin-right: 5px;"></i>@lang('Charity Project')</a>
                </li>
                <li>
                    <a href="{{ route('privatesales') }}">
                        <i class="fas la-binoculars" style="margin-right: 5px;"></i>
                        @lang('Private Sales')
                    </a>
                </li>
                <li>
                    <a href="{{ route('blog') }}">
                         <i class="fas la-comment-dots" style="margin-right: 5px;"></i>@lang('Blog')</a>
                </li>
                <li>
                    <a href="{{ route('contact') }}">
                         <i class="fas la-envelope" style="margin-right: 5px;"></i>@lang('Contact')</a>
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
            
            <div class="d-md-none lightmodeswitchresponsive">
                <div>
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sun-bright" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-sun-bright fa-fw fa-xl"><path fill="currentColor" d="M256 0c-13.3 0-24 10.7-24 24V88c0 13.3 10.7 24 24 24s24-10.7 24-24V24c0-13.3-10.7-24-24-24zm0 400c-13.3 0-24 10.7-24 24v64c0 13.3 10.7 24 24 24s24-10.7 24-24V424c0-13.3-10.7-24-24-24zM488 280c13.3 0 24-10.7 24-24s-10.7-24-24-24H424c-13.3 0-24 10.7-24 24s10.7 24 24 24h64zM112 256c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H88c13.3 0 24-10.7 24-24zM437 108.9c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-45.3 45.3c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L437 108.9zM154.2 357.8c-9.4-9.4-24.6-9.4-33.9 0L75 403.1c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l45.3-45.3c9.4-9.4 9.4-24.6 0-33.9zM403.1 437c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-45.3-45.3c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9L403.1 437zM154.2 154.2c9.4-9.4 9.4-24.6 0-33.9L108.9 75c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l45.3 45.3c9.4 9.4 24.6 9.4 33.9 0zM256 368c61.9 0 112-50.1 112-112s-50.1-112-112-112s-112 50.1-112 112s50.1 112 112 112z" class=""></path></svg>
                </div>
                <div class="form-check form-switch" style="margin-bottom: 0;">
                  <input id="formcheckinput2" class="form-check-input" type="checkbox" checked role="switch" onchange="switchmode2()" />
                  <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                </div>
                <div style="margin-left: 7px;">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="moon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-moon fa-fw fa-xl"><path fill="currentColor" d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z" class=""></path></svg>
                </div>
            </div>
        
            <div class="change-language d-md-none mt-4 justify-content-center">
                <div class="sign-in-up">
                    @if (auth()->check())
                        <span>
                            <a href="{{ route('user.wishlist', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                @if($wishlist_count > 0)
                                    <i class="fas la-heart" style="color: red !important;"></i>
                                @else
                                    <i class="fas la-heart"></i>
                                @endif
                            </a>
                        </span>
                        <span>
                            <a style="position: relative;" href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                                <i class="fas la-shopping-cart"></i>
                                @if($shopping_count > 0)
                                    <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>
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
                            <a style="position: relative;" href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}">
                                <i class="fas la-shopping-cart"></i>
                                @if($shopping_count > 0)
                                    <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>
                                @endif
                            </a>
                        </span>
                    @endif
                </div>
            </div>
            @auth
            <div class="change-language d-md-none mt-4 justify-content-center">
                <div class="sign-in-up">
                    <span><i class="fas la-user"></i></span>
                    <a href="{{ route('user.home') }}">@lang('User Dashboard')</a>
                </div>
            </div>
            @endauth
        </div>
    </div>
    <div class="account-section" style="display: flex; justify-content: center; background: #001635ee; position: relative;">
        <div class="account-section-subpart">
            <div class="account__section-wrapper col-md-4 account-buyer-section" style="justify-content: center;">
                <div class="account__section-content bg--section">
                    <div class="w-100">
                        <div class="d-flex justify-content-center">
                            <div class="logo mb-5" style="max-width: unset !important;">
                                <h2 class="section-each-title">@lang('Buyer Login')</h2>
                            </div>
                        </div>
    
                        <form method="POST" action="{{ route('user.login') }}" onsubmit="return submitUserForm();"
                            class="account--form g-4">
                            @csrf
                            <div class="mb-3">
                                <label for="name">@lang('Username') / @lang('E-Mail')</label>
                                <input type="text" id="name" name="username" value="{{ old('username') }}" class="form-control" autocomplete="off" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="password">@lang('Password')</label>
                                <input type="password" id="password" name="password" class="form-control" autocomplete="off" required>
                            </div>
    
                            @php $recaptcha = loadReCaptcha() @endphp
    
                            @if($recaptcha)
                                <div class="mb-3">
                                    <input type="hidden" name="recaptcha" id="recaptcha">
                                   
                                    @php echo $recaptcha @endphp
                                </div>
                            @endif
    
                            @include($activeTemplate . 'partials.custom_captcha')
    
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label fs--14px" for="remember">
                                            @lang('Remember')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check ps-0 text-right">
                                        <a href="{{ route('user.password.request') }}" class="fs--14px text--base">@lang('Forgot password?')</a>
                                    </div>
                                </div>
                            </div>
    
                            <button type="submit" class="cmn--btn w-100 userloginbtns" style="font-weight: bolder; font-size: 18px;">@lang('SIGN IN')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="middle-section-logo">
                <a href="{{ route('home') }}" class="text-center" >
                    <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                </a>
                <h2></h2>
                <div class="text-center text--white" style="position: absolute; bottom: 10%; width: 250px;">
                    @lang("Don't have an Account?") <p style="padding: 0 !important; margin: 0 !important;"><a href="{{ route('user.register') }}"
                        class="text--base">@lang('Register Here')</a></p>
                </div>
            </div>
            <div class="account__section-wrapper col-md-4 account-seller-section" style="justify-content: center;">
                <div class="account__section-content bg--section">
                    <div class="w-100">
                        <div class="d-flex justify-content-center">
                            <div class="logo mb-5" style="max-width: unset !important;">
                                <h2 class="section-each-title">@lang('Seller Login')</h2>
                            </div>
                        </div>
    
                        <form method="POST" action="{{ route('merchant.login') }}" class="account--form g-4">
                            @csrf
                            <div class="mb-3">
                                <label for="name">@lang('Username') / @lang('E-Mail')</label>
                                <input type="text" id="name" name="username" value="{{ old('username') }}" class="form-control" autocomplete="off" required>
                            </div>
    
                            <div class="mb-3">
                                <label for="password">@lang('Password')</label>
                                <input type="password" id="password" name="password" class="form-control" autocomplete="off" required>
                            </div>
    
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label fs--14px" for="remember">
                                            @lang('Remember')&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check ps-0 text-right">
                                        <a href="{{ route('user.password.request') }}" class="fs--14px text--base">@lang('Forgot password?')</a>
                                    </div>
                                </div>
                            </div>
    
                            <button type="submit" class="cmn--btn w-100 userloginbtns" style="font-weight: bolder; font-size: 18px;">@lang('SIGN IN')</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="account-switch-section">
                <span>@lang('Buyer')</span>
                <div class="form-check form-switch" style="margin-bottom: 0; padding-right: 0;">
                    <input id="formcheckinput" class="form-check-input" type="checkbox" role="switch" onchange="switchmode()" style="position: relative; right: unset; top: unset; margin-left: 5px; margin-right: 5px;" />
                </div>
                <span>@lang('Seller')</span>
            </div>
            <div class="account-create-new-section">
                <div class="text-center text--white">
                    @lang('Don\'t have an Account')? <p style="padding: 0 !important; margin: 0 !important;"><a href="{{ route('user.register') }}"
                        class="text--base">@lang('Register Here')</a></p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-fixed-sec">
        <div class="container">
            <div>
                <div>
                    <a href="{{ route('home') }}" class="{{ menuActive('home') }}">
                        <i class="fas la-home"></i>
                        <span>@lang('Home')</span>
                    </a>
                </div>
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
                <!--<div>-->
                <!--    @if (auth()->check())-->
                <!--    <a href="{{ route('user.bonus') }}" class="{{ menuActive('user.bonus') }}">-->
                <!--        <i class="fas la-gift"></i>-->
                <!--        <span>@lang('Bonus Scheme')</span>-->
                <!--    </a>-->
                <!--    @else-->
                <!--    <a href="{{ route('shopping-cart.unadd') }}">-->
                <!--        <i class="fas la-gift"></i>-->
                <!--        <span>@lang('Bonus Scheme')</span>-->
                <!--    </a>-->
                <!--    @endif-->
                <!--</div>-->
                <div>
                    <a>
                        <i class="fas la-bars header-bar d-lg-none" style="margin-right: 0; width: auto; height: auto;"></i>
                        <span class="header-bar d-lg-none" style="margin-right: 0; width: auto; height: auto;">@lang('Others')</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .account-section-subpart {
        display: flex;
        justify-content: center;
        position: relative;
        height: 100vh;
        overflow-y: hidden;
        width: 100%;
    }
    
    .account-switch-section {
        display: none;
        position: fixed;
        top: 5%;
        left: 50%;
        transform: translate(-50%);
        z-index: 10;
    }
    
    .account-create-new-section {
        display: none;
        position: absolute;
        bottom: 85px;
        left: 50%;
        transform: translate(-50%);
    }
    
    .middle-section-logo {
        margin: 0 50px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10;
    }
    
    .middle-section-logo > a {
        position: absolute;
        top: 10%;
    }
    
    .middle-section-logo > a > img {
        width: 150px;
    }
    
    .middle-section-logo > h2 {
        text-transform: uppercase;
        font-weight: 100;
    }
    
    @media (min-width: 768px) {
        .section-each-title {
            font-size: 46px;
        }
    }
    
    .section-each-title {
        font-weight: 100;
    }
    
    .account-buyer-section {
        transition: 0.4s all ease;
    }
    
    .account-seller-section {
        transition: 0.4s all ease;
    }
    
    .account__section-wrapper .account__section-content {
        border-right: unset;
        border-left: unset;
    }
    
    @media (min-width: 769px) {
        .account-buyer-section {
            display: flex !important;
            position: unset;
        }
        
        .account-seller-section {
            display: flex !important;
            position: unset;
        }
        
        .middle-section-logo {
            display: flex !important;
        }
        
        .account-switch-section {
            display: none !important;
        }
        
        .account-create-new-section {
            display: none !important;
        }
    }
    
    @media (max-width: 768px) {
        .account-buyer-section {
            display: flex;
            position: absolute;
            top: 0;
        }
        
        .account-seller-section {
            display: flex;
            position: absolute;
            top: 100vh;
        }
        
        .middle-section-logo {
            display: none;
        }
        
        .account-switch-section {
            display: flex;
        }
        
        .account-create-new-section {
            display: block;
        }
    }
    
    .register-header-section {
        display: none;
    }
    
    @media (max-width: 991px) {
        .register-header-section {
            display: flex;
        }
    }
</style>
@endpush

@push('script')
    <script>
        "use strict";
        
        function onloadCallback() {
            console.log("reCAPTCHA has loaded!");
            grecaptcha.reset();
        };
        
        function switchmode() {
            if(document.getElementById("formcheckinput").checked) {
                $('.account-seller-section').css("top", "0");
                $('.account-buyer-section').css("top", "-100vh");
            } else {
                $('.account-seller-section').css("top", "100vh");
                $('.account-buyer-section').css("top", "0");
            }
        }

        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML =
                    '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
