@extends($activeTemplate.'layouts.frontend')
@php
    $register = getContent('register.content', true);
    $policyPages = getContent('policy_pages.element');

@endphp
@section('content')
<div class="header-wrapper register-header-section">
    <div class="menu-area" style="z-index: 9999;">
        <div class="menu-close">
            <i class="las la-times"></i>
        </div>
        <ul class="menu">
            <li>
                <a class="text-uppercase" href="{{ route('home') }}">
                     <i class="fas la-home" style="margin-right: 5px;"></i>@lang('Home')</a>
            </li>
            <li>
                <a class="text-uppercase" href="{{ route('auction.all') }}">
                     <i class="fas la-gavel" style="margin-right: 5px;"></i>@lang('Auction')</a>
            </li>
            <li>
                <a class="text-uppercase" href="{{ route('product.all') }}">
                     <i class="fas la-store" style="margin-right: 5px;"></i>@lang('Marketplace')</a>
            </li>
            @if (auth()->check())
            <li>
               <a class="text-uppercase" href="{{ route('user.bonus') }}">
                     <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
            </li>
            @else
            <li>
               <a class="text-uppercase" href="{{ route('shopping-cart.unadd') }}">
                     <i class="fas la-gift" style="margin-right: 5px;"></i>@lang('Bonus Scheme')</a>
            </li>
            @endif
            
            <li>
                <a class="text-uppercase" href="{{ route('privatesales') }}">
                    <i class="fas la-binoculars" style="margin-right: 5px;"></i>
                    @lang('Private Sales')
                </a>
            </li>
            <li>
                <a class="text-uppercase" href="{{ route('contact') }}">
                     <i class="fas la-envelope" style="margin-right: 5px;"></i>@lang('Contact')</a>
            </li>
            <li>
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
        
        <!--<div class="d-md-none lightmodeswitchresponsive">-->
        <!--    <div>-->
        <!--        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sun-bright" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-sun-bright fa-fw fa-xl"><path fill="currentColor" d="M256 0c-13.3 0-24 10.7-24 24V88c0 13.3 10.7 24 24 24s24-10.7 24-24V24c0-13.3-10.7-24-24-24zm0 400c-13.3 0-24 10.7-24 24v64c0 13.3 10.7 24 24 24s24-10.7 24-24V424c0-13.3-10.7-24-24-24zM488 280c13.3 0 24-10.7 24-24s-10.7-24-24-24H424c-13.3 0-24 10.7-24 24s10.7 24 24 24h64zM112 256c0-13.3-10.7-24-24-24H24c-13.3 0-24 10.7-24 24s10.7 24 24 24H88c13.3 0 24-10.7 24-24zM437 108.9c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-45.3 45.3c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0L437 108.9zM154.2 357.8c-9.4-9.4-24.6-9.4-33.9 0L75 403.1c-9.4 9.4-9.4 24.6 0 33.9s24.6 9.4 33.9 0l45.3-45.3c9.4-9.4 9.4-24.6 0-33.9zM403.1 437c9.4 9.4 24.6 9.4 33.9 0s9.4-24.6 0-33.9l-45.3-45.3c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9L403.1 437zM154.2 154.2c9.4-9.4 9.4-24.6 0-33.9L108.9 75c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l45.3 45.3c9.4 9.4 24.6 9.4 33.9 0zM256 368c61.9 0 112-50.1 112-112s-50.1-112-112-112s-112 50.1-112 112s50.1 112 112 112z" class=""></path></svg>-->
        <!--    </div>-->
        <!--    <div class="form-check form-switch" style="margin-bottom: 0;">-->
        <!--      <input id="formcheckinput2" class="form-check-input" type="checkbox" checked role="switch" onchange="switchmode2()" />-->
        <!--      <label class="form-check-label" for="flexSwitchCheckDefault"></label>-->
        <!--    </div>-->
        <!--    <div style="margin-left: 7px;">-->
        <!--        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="moon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-moon fa-fw fa-xl"><path fill="currentColor" d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z" class=""></path></svg>-->
        <!--    </div>-->
        <!--</div>-->
        
        <div class="d-md-none lightmodeswitchresponsive">
            <input type="checkbox" class="checkbox" checked id="formcheckinput" onchange="switchmode()">
            <label for="formcheckinput" class="checkbox-label">
                <i class="fas fa-moon"></i>
                <i class="fas fa-sun"></i>
                <span class="ball"></span>
            </label>
        </div>
    
        <!--<div class="change-language d-md-none mt-4 justify-content-center">-->
        <!--    <div class="sign-in-up">-->
        <!--        @if (auth()->check())-->
        <!--            <span>-->
        <!--                <a href="{{ route('user.wishlist', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">-->
        <!--                    @if($wishlist_count > 0)-->
        <!--                        <i class="fas la-heart" style="color: red !important;"></i>-->
        <!--                    @else-->
        <!--                        <i class="fas la-heart"></i>-->
        <!--                    @endif-->
        <!--                </a>-->
        <!--            </span>-->
        <!--            <span>-->
        <!--                <a style="position: relative;" href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">-->
        <!--                    <i class="fas la-shopping-cart"></i>-->
        <!--                    @if($shopping_count > 0)-->
        <!--                        <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>-->
        <!--                    @endif-->
        <!--                </a>-->
        <!--            </span>-->
        <!--        @else-->
        <!--            <span>-->
        <!--                <a href="{{ route('user.wishlist', ['empty', getenv('REMOTE_ADDR')]) }}">-->
        <!--                    @if($wishlist_count > 0)-->
        <!--                        <i class="fas la-heart" style="color: red !important;"></i>-->
        <!--                    @else-->
        <!--                        <i class="fas la-heart"></i>-->
        <!--                    @endif-->
        <!--                </a>-->
        <!--            </span>-->
        <!--            <span>-->
        <!--                <a style="position: relative;" href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}">-->
        <!--                    <i class="fas la-shopping-cart"></i>-->
        <!--                    @if($shopping_count > 0)-->
        <!--                        <span style="position: absolute; top: 0; right: 0; transform: translate(65%, -19%); padding: 0; margin: 0; color: #fff !important; width: 18px; height: 18px; border-radius: 50px; background-color: red; text-align: center; display: flex; justify-content: center; align-items: center; font-size: 10px;">{{ $shopping_count }}</span>-->
        <!--                    @endif-->
        <!--                </a>-->
        <!--            </span>-->
        <!--        @endif-->
        <!--    </div>-->
        <!--</div>-->
        
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
<div class="account-section" style="display: flex; justify-content: center; position: relative;">
    <div class="account__section-wrapper col-md-6 account__section-register" style="display: flex; justify-content: center;">
        <div class="account__section-content bg--section account__section-content-reg" style="max-width: unset;">
            <div class="w-100">
                <div class="d-flex justify-content-center">
                    <div class="logo mb-5">
                        <a href="{{ route('home') }}" class="text-center" >
                            <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>

                <form action="{{ route('user.register') }}" method="POST" onsubmit="return submitUserForm();" class="account--form g-4">
                    @csrf
                    @if(session()->get('reference') != null)
                    <div class="mb-3">
                        <label for="referenceBy">@lang('Reference By')</label>
                        <input type="text" name="referBy" id="referenceBy" class="form-control" value="{{session()->get('reference')}}" readonly>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="firstname">@lang('First Name')</label>
                            <input type="text" id="firstname" name="firstname" class="form-control" autocomplete="off" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="lastname">@lang('Last Name')</label>
                            <input type="text" id="lastname" name="lastname" class="form-control" autocomplete="off" required>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="country">@lang('Country')</label>
                            <select name="country" id="country" class="form-control">
                                @foreach($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" name="mobile_code">
                        <input type="hidden" name="country_code">

                        <div class="col-sm-6 mb-3">
                            <label for="mobile">@lang('Mobile')</label>
                            <div class="input-group">
                                <span class="input-group-text mobile-code bg--base text-white border-0"></span>
                                <input type="tel" id="mobile" name="mobile" value="{{ old('mobile') }}" class="form-control checkUser" autocomplete="off" required>
                            </div>
                            <label class="error mobileExist"></label>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="username">@lang('Username')</label>
                            <input type="text" id="username" name="username" class="form-control checkUser" autocomplete="off" required>
                            <label class="error usernameExist"></label>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="email">@lang('E-Mail Address')</label>
                            <input type="text" id="email" name="email" class="form-control checkUser" autocomplete="off" required>
                            <label class="error emailExist"></label>
                        </div>
                        <div class="col-sm-6 mb-3 hover-input-popup">
                            <label for="password">@lang('Password')</label>
                            <input type="password" id="password" name="password" class="form-control" autocomplete="off" required>
                            @if($general->secure_password)
                                <div class="input-popup">
                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                    <p class="error number">@lang('1 number minimum')</p>
                                    <p class="error special">@lang('1 special character minimum')</p>
                                    <p class="error minimum">@lang('6 character password')</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-sm-6 mb-3">
                            <label for="confirm-password">@lang('Confirm Password')</label>
                            <input type="password" id="confirm-password" name="password_confirmation" class="form-control" autocomplete="off" required>
                        </div>
                        <input type="hidden" name="bonuspoint" id="bonuspoint" value="200" />
                        
                        <div class="col-sm-12 mb-3 terms-col-section">
                            <label for="confirm-password">@lang('Terms of Service and Privacy Policy')</label>
                            <div style="border: 1px solid #28364a; border-radius: 5px; height: 250px; overflow: auto;">
                                <div class="container">
                                    @foreach ($policyPages as $policyPage)
                                        <div class="hero-content text-center mt-4 mb-3">
                                            <h3 class="hero-title text--base">{{ __($policyPage->data_values->title) }}</h3>
                                        </div>
                                        <div>
                                            @php
                                                echo $policyPage->data_values->details
                                            @endphp
                                        </div>
                                    @endforeach
                	            </div>
                	        </div>
                        </div>
                    </div>

                    @php $recaptcha = loadReCaptcha() @endphp

                    @if($recaptcha)
                        <div class="mb-3">
                            <input type="hidden" name="recaptcha" id="recaptcha">
                            @php echo $recaptcha @endphp
                        </div>
                    @endif


                    @include($activeTemplate . 'partials.custom_captcha')


                    @if($general->agree)
                        <div class="mb-3">
                            <input type="checkbox" id="agree" name="agree">
                            <label for="agree" class="ms-1">@lang('I agree with')
                                @foreach ($policyPages as $policyPage)
                                    <a href="{{ route('policy', [$policyPage, slug($policyPage->data_values->title)]) }}" target="_blank" class="text--base">
                                        {{ __($policyPage->data_values->title) }}@if(!$loop->last) and @endif
                                    </a>
                                @endforeach
                            </label>
                        </div>
                    @endif

                    <button type="submit" class="cmn--btn w-100">@lang('Register')</button>
                </form>
                <div class="mt-5 text-center text--white" style="margin-top: 1.5rem !important; margin-bottom: 2rem !important;">
                    @lang('You already have an user account')? 
                    <p style="padding: 0 !important; margin: 0 !important;">
                        <a href="{{ route('user.login') }}" class="text--base">@lang('Login')</a>
                    </p>
                </div>
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
                    <span>@lang('Marketplace')</span>
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
                    <span class="header-bar d-lg-none" style="margin-right: 0; width: auto; height: auto;">@lang('Overview')</span>
                </a>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="existModalLongTitle" >@lang('You are with us')</h5>
        <button type="button" class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h6 class="text-center">@lang('You already have an account.')</h6>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('Close')</button>
        <a href="{{ route('user.login') }}" class="btn btn--base">@lang('Login')</a>
      </div>
    </div>
  </div>
</div>
@endsection
@push('style')
<style>
    #existModalLongTitle {
        text-transform: unset !important;
    }
    .form-control:disabled, .form-control[readonly]{
        background-color: transparent;
    }
    .country-code .input-group-prepend .input-group-text{
        background: #fff !important;
    }
    .country-code select{
        border: none;
    }
    .country-code select:focus{
        border: none;
        outline: none;
    }
    .hover-input-popup {
        position: relative;
    }
    .hover-input-popup:hover .input-popup {
        opacity: 1;
        visibility: visible;
    }
    .input-popup {
        position: absolute;
        bottom: 130%;
        left: 50%;
        width: 280px;
        background-color: #1a1a1a;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        -o-border-radius: 5px;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
        opacity: 0;
        visibility: hidden;
        -webkit-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
    }
    .input-popup::after {
        position: absolute;
        content: '';
        bottom: -19px;
        left: 50%;
        margin-left: -5px;
        border-width: 10px 10px 10px 10px;
        border-style: solid;
        border-color: transparent transparent #1a1a1a transparent;
        -webkit-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        transform: rotate(180deg);
    }
    .input-popup p {
        padding-left: 20px;
        position: relative;
    }
    .input-popup p::before {
        position: absolute;
        content: '';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        left: 0;
        top: 4px;
        line-height: 1;
        font-size: 18px;
    }
    .input-popup p.error {
        text-decoration: line-through;
    }
    .input-popup p.error::before {
        content: "\f057";
        color: #ea5455;
    }
    .input-popup p.success::before {
        content: "\f058";
        color: #28c76f;
    }
    
    .account__section-terms {
        display: flex;
        flex-direction: column;
        height: 100vh;
        overflow: auto;
    }
    
    .account-switch-section {
        display: none;
        position: fixed;
        top: 3%;
        left: 50%;
        transform: translate(-50%);
        z-index: 10;
    }
    
    .terms-col-section {
        display: block;
    }
    
    @media (max-width: 998px) {
        .account__section-terms {
            display: none;
        }
        
        .account-switch-section {
            display: none;
        }
        
        .terms-col-section {
            display: block;
        }
    }
    
    @media (min-width: 999px) {
        .account__section-terms {
            display: flex;
        }
        
        .account__section-register {
            display: flex;
        }
        
        .terms-col-section {
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
@push('script-lib')
<script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
      
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
        
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
        
        (function ($) {
            @if($mobile_code)
            $(`option[data-code={{ $mobile_code }}]`).attr('selected','');
            @endif

            $('select[name=country]').change(function(){
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            });
            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
            $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            
            $.get("https://ipinfo.io", function(response) {
                const regionNames = new Intl.DisplayNames(
                  ['en'], {type: 'region'}
                );
                $('select[name=country]').val(regionNames.of(response.country));
                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));
                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));
                $('.mobile-code').text('+'+$('select[name=country] :selected').data('mobile_code'));
            }, "json");
            
            @if($general->secure_password)
                $('input[name=password]').on('input',function(){
                    secure_password($(this));
                });
            @endif

            $('.checkUser').on('focusout',function(e){
                var url = '{{ route('user.checkUser') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {mobile:mobile,_token:token}
                }
                if ($(this).attr('name') == 'email') {
                    var data = {email:value,_token:token}
                }
                if ($(this).attr('name') == 'username') {
                    var data = {username:value,_token:token}
                }
                $.post(url,data,function(response) {
                    console.log(response['data'], response['type']);
                  if (response['data'] && response['type'] == 'email') {
                    $('#existModalCenter').modal('show');
                  }else if(response['data'] != null){
                    $(`.${response['type']}Exist`).text(`${response['type']} already exist`);
                  }else{
                    $(`.${response['type']}Exist`).text('');
                  }
                });
            });

        })(jQuery);

    </script>
@endpush
