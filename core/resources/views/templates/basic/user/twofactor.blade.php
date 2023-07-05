@extends($activeTemplate.'userlayouts.userapp')
@section('panel')
<div class="row g-4">
    <div class="col-xl-6">
        @if (Auth::user()->ts)
            <div class="card cmn--card h-100">
                <div class="card-header">
                    <h4 class="card-title">@lang('Two-Factor Authenticator')</h4>
                </div>
                <div class="card-body">
                    <div class="form-group mx-auto text-center">
                        <button class="btn btn--danger btn-block btn-lg disablebtns">
                            @lang('Disable Two-Factor Authenticator')</button>
                    </div>
                </div>
            </div>
        @else
            <div class="card cmn--card h-100">
                <div class="card-header">
                    <h4 class="card-title">@lang('Two-Factor Authenticator')</h4>
                </div>
                <div class="card-body">
                    <div class="two-factor-content">
                        <div class="input--group input-group referral-group mb-4">
                            <input type="text" class="form-control form--control" value="{{$secret}}" readonly="" id="referralURL">
                            <button class="btn btn--primary copytext" id="copyBoard" type="button"><i class="las la-copy"></i></button>
                        </div>
                        <div class="two-factor-scan text-center mb-4">
                            <img class="two-factor-thumb" src="{{$qrCodeUrl}}" alt="images">
                        </div>
                        <div class="text-center">
                            <button class="btn btn--primary btn-block btn-lg enablebtns">@lang('Enable Two-Factor Authenticator')</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col-xl-6">
        <div class="card cmn--card h-100">
            <div class="card-header">
                <h4 class="card-title">@lang('Google Authenticator')</h4>
            </div>
            <div class="card-body">
                <div class="two-factor-content">
                   
                    <p class="two__fact__text mb-4">
                        @lang('Google Authenticator is a multifactor app for mobile devices. It generates timed codes used during the 2-step verification process. To use Google Authenticator, install the Google Authenticator application on your mobile device.')
                    </p>
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="__blank" class="btn btn--primary btn-block btn-lg">@lang('DOWNLOAD APP')</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="enableModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Verify to enable Authenticator')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('user.twofactor.enable')}}" method="POST">
                @csrf
                <div class="modal-body ">
                    <div class="form-group">
                        <input type="hidden" name="key" value="{{$secret}}">
                        <input type="text" class="form-control form--control-2" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Verify')</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!--Disable Modal -->
<div id="disableModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">@lang('Verify to disable Authenticator')</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{route('user.twofactor.disable')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control form--control-2" name="code" placeholder="@lang('Enter Google Authenticator Code')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--primary">@lang('Verify')</button>
                </div>
            </form>
        </div>
    </div>
</div> 
@endsection

@push('script')
    <script>
        (function($){
            "use strict";
            
            $('.enablebtns').click(function() {
                var modal = $('#enableModal');
                modal.modal('show');
            });
            
            $('.disablebtns').click(function() {
                var modal = $('#disableModal');
                modal.modal('show');
            });

            $('.copytext').on('click',function(){
                var copyText = document.getElementById("referralURL");
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                document.execCommand("copy");
                iziToast.success({message: "Copied: " + copyText.value, position: "topRight"});
            });
        })(jQuery);
    </script>
@endpush


