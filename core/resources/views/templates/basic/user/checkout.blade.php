@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
<form action="{{ $checkstatus ? route('user.checkout.update') : route('user.checkout.save') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row mb-none-30">
        <input type="hidden" id="checkoutid" name="checkoutid" value="@if($checkstatus){{ $checkoutprofile[0]->id }}@endif" />
        <div class="col-xl-12 col-lg-12 col-md-12 mb-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-30 border-bottom pb-2">@lang('Shipping Address')</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('First Name') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="firstname" id="firstname" value="{{ $checkstatus ? $checkoutprofile[0]->firstname : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Last Name') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="lastname" id="lastname" value="{{ $checkstatus ? $checkoutprofile[0]->lastname : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Address') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="address" id="address" value="{{ $checkstatus ? $checkoutprofile[0]->address : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('City') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="city" id="city" value="{{ $checkstatus ? $checkoutprofile[0]->city : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Postal Code') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="postalcode" id="postalcode" value="{{ $checkstatus ? $checkoutprofile[0]->postalcode : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Country') <span style="color: red !important;">*</span></label>
                                <select class="form-control" aria-invalid="false" name="country" id="country" style="background-color: transparent;" required>
                                    @if($checkstatus)
                                        <option value=""></option>
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}" {{ $checkoutprofile[0]->country == $key ? "selected" : "" }}>
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value=""></option>
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}">
                                                {{ __($country->country) }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Tel.')</label>
                                <input class="form-control" type="text" name="tel" id="tel" value="{{ $checkstatus ? $checkoutprofile[0]->tel : '' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label font-weight-bold">@lang('Email') <span style="color: red !important;">*</span></label>
                                <input class="form-control" type="text" name="email" id="email" value="{{ $checkstatus ? $checkoutprofile[0]->email : '' }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-30 border-bottom pb-2">@lang('Payment Method')</h5>
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 mb-10">
                            <input type="hidden" name="paymentname" id="paymentname" value="{{ $checkstatus ? $checkoutprofile[0]->paymentname : '' }}" />
                            <ul class="payment-ul">
                                @forelse($paymentmethods as $method)
                                    <li class="paymentstepallitems @if($checkstatus) {{ $method->name == $checkoutprofile[0]->paymentname ? 'liactive' : '' }} @endif" data-method="{{ $method->name }}">
                                        <span><span class="payment-circle"></span>{{ $method->name }}</span>
                                        <image src="{{ getImage(imagePath()['product']['path'].'/'.$method->icon) }}" style="width: 50px; height: 30px;" />
                                    </li>
                                @empty
                                    <div style="background-color: transparent !important;">
                                        @lang('No Payment Method')
                                    </div>
                                @endforelse
                            </ul>
                            <div class="form-group mt-30">
                                <button type="button" class="btn btn--primary btn-block btn-lg checkoutsavebtn">@lang('Save Changes')</button>
                                <button type="submit" class="btn btn--primary btn-block btn-lg checkoutsavesubmitbtn">@lang('Save Changes')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('style')
<style>
    .payment-ul li {
        display: flex;
        justify-content: space-between;
        padding: 10px 0 10px 10px;
        cursor: pointer;
        margin: 5px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .payment-ul li > span {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .payment-ul li > span > .payment-circle {
        background-color: transparent;
        width: 20px;
        height: 20px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 10px;
        background-color: transparent;
        border: 5px solid transparent;
        outline: 1px solid #0475FF;
    }
    
    .payment-ul li.liactive > span {
        font-weight: bold;
    }
    
    .payment-ul li.liactive > span > .payment-circle {
        background-color: #0475FF;
        border: 5px solid white;
        outline: 1px solid #0475FF;
    }
    
    .checkoutsavesubmitbtn {
        display: none;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.paymentstepallitems').on('click', function() {
            $('.paymentstepallitems').removeClass('liactive');
            $(this).addClass('liactive');
            $('#paymentname').val($(this).data().method);
        });
        
        $('.checkoutsavebtn').on('click', function() {
            if($('#firstname').val().trim() == "") {
                $('#firstname').focus();
                iziToast['warning']({
                    message: "Please fill in First Name field.",
                    position: "topRight"
                });
            } else if($('#lastname').val().trim() == "") {
                $('#lastname').focus();
                iziToast['warning']({
                    message: "Please fill in Last Name field.",
                    position: "topRight"
                });
            } else if($('#address').val().trim() == "") {
                $('#address').focus();
                iziToast['warning']({
                    message: "Please fill in Address field.",
                    position: "topRight"
                });
            } else if($('#city').val().trim() == "") {
                $('#city').focus();
                iziToast['warning']({
                    message: "Please fill in City field.",
                    position: "topRight"
                });
            } else if($('#postalcode').val().trim() == "") {
                $('#postalcode').focus();
                iziToast['warning']({
                    message: "Please fill in Postal Code field.",
                    position: "topRight"
                });
            } else if($('#country').val().trim() == "") {
                $('#country').focus();
                iziToast['warning']({
                    message: "Please fill in Country field.",
                    position: "topRight"
                });
            } else if($('#email').val().trim() == "") {
                $('#email').focus();
                iziToast['warning']({
                    message: "Please fill in Email field.",
                    position: "topRight"
                });
            } else if($('.paymentstepallitems.liactive').length <= 0) {
                iziToast['warning']({
                    message: "Please select payment method.",
                    position: "topRight"
                });
            } else {
                $('.checkoutsavesubmitbtn').click();
            }
        });
    })(jQuery);
</script>
@endpush