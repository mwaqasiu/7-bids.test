@extends($activeTemplate .'layouts.frontend')
@php
    $twoFA = getContent('2fa_verify.content', true);
@endphp
@section('content')
<div class="account-section" style="display: flex; justify-content: center; background-color: #001635ee;">
    <div class="account__section-wrapper col-md-6" style="display: flex; justify-content: center;">
        <div class="account__section-content bg--section" style="border-right: unset; border-left: unset;">
            <div class="w-100">
                <div class="d-flex justify-content-center">
                    <div class="logo mb-5">
                        <a href="{{ route('home') }}" class="text-center" >
                            <img src="{{ getImage(imagePath()['logoIcon']['path'] . '/logo.png') }}" alt="logo">
                        </a>
                    </div>
                </div>

                <div class="section__header text--white">
                    <h6 class="mb-0">@lang('2FA Verification')</h6>
                </div>

                <form method="POST" action="{{ route('user.go2fa.verify')}}" class="account--form g-4">
                    @csrf
                    <div class="mb-3 verified-code-section-blk">
                        <label for="code" class="form--label-2">@lang('Verification Code')</label>
                        <input type="text" id="code" name="code" value="{{ old('code') }}" class="form-control" autocomplete="off">
                    </div>
                    <div class="mb-3">
                        <label for="code" class="form--label-2">@lang('Verification Code')</label>
                        <div class="row code-container">
                            <div class="col-2">
                                <input type="text" id="email_verified_code-1" name="email_verified_code-1" data-next="email_verified_code-2" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                            <div class="col-2">
                                <input type="text" id="email_verified_code-2" name="email_verified_code-2" data-next="email_verified_code-3" data-previous="email_verified_code-1" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                            <div class="col-2">
                                <input type="text" id="email_verified_code-3" name="email_verified_code-3" data-next="email_verified_code-4" data-previous="email_verified_code-2" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                            <div class="container-col-stick">-</div>
                            <div class="col-2">
                                <input type="text" id="email_verified_code-4" name="email_verified_code-4" data-next="email_verified_code-5" data-previous="email_verified_code-3" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                            <div class="col-2">
                                <input type="text" id="email_verified_code-5" name="email_verified_code-5" data-next="email_verified_code-6" data-previous="email_verified_code-4" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                            <div class="col-2">
                                <input type="text" id="email_verified_code-6" name="email_verified_code-6" data-previous="email_verified_code-5" class="form-control email_verified_code" maxlength="1" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="cmn--btn w-100 addverifycode-submit-btn">@lang('Submit')</button>
                    <button type="button" class="cmn--btn w-100 addverifycode-btn">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .email_verified_code {
        text-align: center;
    }
    
    .addverifycode-submit-btn {
        display: none;
    }
    
    .verified-code-section-blk {
        display: none;
    }
    
    .code-container {
        position: relative;
    }
    
    .container-col-stick {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: fit-content;
    }
</style>
@endpush

@push('script')
<script>
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;

          $(this).val(function (index, value) {
             value = value.substr(0,7);
              return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
          });
      });
      
      $('.addverifycode-btn').on('click', function() {
            var verifycode = "";
            $('.code-container').find('input').each(function() {
                verifycode += $(this).val().toString();
            });
            if(verifycode.length < 6) {
                alert("Please input full code");
            }
            else {
                $('#code').val(verifycode);
                $('.addverifycode-submit-btn').click();
            }
        });
        
        $('.code-container').find('input').each(function() {
            $(this).on('keyup', function(e) {
                var parent = $($($(this).parent()).parent());
                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));
                    if (prev.length) {
                        $(prev).select();
                    }
                } else if((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
                    var next = parent.find('input#' + $(this).data('next'));
                    
                    if (next.length) {
                        $(next).select();
                    }
                }
            })
        });
    })(jQuery)
</script>
@endpush
