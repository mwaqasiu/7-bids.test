@extends($activeTemplate.'layouts.frontend')
@php
    $contact = getContent('contact_us.content', true);
@endphp
@section('content')
    <!-- Contact -->
    <section class="contact-section pt-60 pb-120 pb-lg-0">
        <div class="container">
            <div class="contact-area" style="align-items: flex-start;">
                <div class="contact-content">
                    <div class="contact-content-top" style="margin-top: 30px;">
                        <h3 class="title">{{ __($contact->data_values->title) }}</h3>
                        <p>{{ __($contact->data_values->short_details) }}</p>
                    </div>
                    <div class="contact-content-botom">
                        <h5 class="subtitle">@lang('More Information')</h5>
                        <ul class="contact-info">
                            <li>
                                <div class="icon">
                                    <i class="las la-map-marker-alt"></i>
                                </div>
                                <div class="cont">
                                    <h6 class="name">@lang('Address')</h6>
                                    <span class="info">{{ __($contact->data_values->contact_details) }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="las la-envelope"></i>
                                </div>
                                <div class="cont">
                                    <h6 class="name">@lang('Email')</h6>
                                    <span class="info">{{ __($contact->data_values->email_address) }}</span>
                                </div>
                            </li>
                            <li>
                                <div class="icon">
                                    <i class="las la-phone-volume"></i>
                                </div>
                                <div class="cont">
                                    <h6 class="name">@lang('Phone')</h6>
                                    <span class="info">{{ __($contact->data_values->contact_number) }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="contact-wrapper bg--section">
                    <div class="section__header text-start icon__contain">
                        <h3 class="section__title">
                            <!--<div class="contact-icon">-->
                            <!--    <i class="las la-place-of-worship"></i>-->
                            <!--</div>-->
                            <div class="cont" style="padding-left: 0;">
                                @lang('Send Message')
                            </div>
                        </h3>
                    </div>
                    <form action="{{ route('contact.send') }}" method="POST" class="contact-form row">
                        @csrf
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name" class="form--label">@lang('Name')</label>
                                <input type="text" name="name" id="name" class="form-control form--control"
                                    value="{{ auth()->user() ? auth()->user()->fullname : old('name') }}"
                                    @if (auth()->user()) readonly @endif required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email" class="form--label">@lang('Email')</label>
                                <input type="text" name="email" id="email" class="form-control form--control"
                                    value="{{ auth()->user() ? auth()->user()->email : old('email') }}"
                                    @if (auth()->user()) readonly @endif required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="subject" class="form--label">@lang('Subject')</label>
                                <input type="text" name="subject" id="subject" class="form-control form--control"
                                    value="{{ old('subject') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="message" class="form--label">@lang('Message')</label>
                                <textarea name="message" id="message"
                                    class="form-control form--control" required>{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <input type="hidden" value="{{ getenv('REMOTE_ADDR') }}" name="ipaddress" id="ipaddress" required />
                        <div class="col-sm-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="cmn--btn w-100 contactsubmitbtn" style="display: none;">@lang('SUBMIT YOUR INQUIRY')</button>
                                <button type="button" class="cmn--btn w-100 contactclickbtn">@lang('SUBMIT YOUR INQUIRY')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact -->

    @if ($sections != null)
        @foreach (json_decode($sections) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('style')
<style>
    .form-control {
        background-color: #336699;
        color: #fff;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        var warningmsg = "@lang('Please fill in all the blanks.')";
        
        $('.contactclickbtn').on('click', function() {
            if($('#name').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#name').focus();
                return;
            } else if($('#email').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#email').focus();
                return;
            } else if($('#subject').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#subject').focus();
                return;
            } else if($('#message').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#message').focus();
                return;
            } else {
                $('.contactsubmitbtn').click();
            }
        });
    })(jQuery);
</script>
@endpush
