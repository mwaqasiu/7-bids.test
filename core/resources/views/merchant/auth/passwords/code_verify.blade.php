@extends('merchant.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        <div class="form-area" style="background-color: #001635;">
            <div class="form-wrapper" style="background-color: unset;">
                <h4 class="logo-text mb-15"><strong>@lang('Recover Account')</strong></h4>
                <form action="{{ route('merchant.password.verify.code') }}" method="POST" class="cmn-form mt-30">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div class="form-group">
                        <label>@lang('Verification Code')</label>
                        <input type="text" name="code" id="code" class="form-control">
                    </div>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <a href="{{ route('merchant.password.request') }}" class="text-muted text--small">@lang('Try to send again')</a>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="submit-btn mt-25">@lang('Verify Code') <i class="las la-sign-in-alt"></i></button>
                    </div>
                </form>
            </div>
        </div><!-- login-area end -->
    </div>
@endsection

@push('style')
<style>
    .form-area .form-wrapper::before {
        background-color: unset;
    }
    
    .form-area .form-wrapper::after {
        background-color: #001635ee;
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
    })(jQuery)
</script>
@endpush