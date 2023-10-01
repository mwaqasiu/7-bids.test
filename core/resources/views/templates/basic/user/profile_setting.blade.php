@extends($activeTemplate.'userlayouts.userapp')

@section('panel')
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row mb-none-30">
            @csrf
            <div class="col-xl-4 col-lg-4 col-md-5 mb-30">
                <div class="card b-radius--5 overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="form-group">
                            <div class="image-upload">
                                <div class="thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(imagePath()['profile']['user']['path'] .'/' .auth()->user()->image,imagePath()['profile']['user']['size']) }})">
                                            <button type="button" class="remove-image"><i
                                                    class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div class="avatar-edit px-2">
                                        <input type="file" class="profilePicUpload" name="image" id="profilePicUpload1"
                                            accept=".png, .jpg, .jpeg">
                                        <label for="profilePicUpload1" class="bg--success">@lang('Upload Profile Photo')</label>
                                        <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'),
                                                @lang('jpg').</b> @lang('Image will be resized into 400x400px') </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card b-radius--5 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="d-flex p-3 bg--primary align-items-center">
                            <div class="pl-3">
                                <h4 class="text--white">{{ __($user->fullname) }}</h4>
                            </div>
                        </div>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Customer ID')
                                <span class="font-weight-bold">{{ __($user->customerid) }}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Username')
                                <span class="font-weight-bold">{{ __($user->username) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-7 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-50 border-bottom pb-2">@lang('Profile Information')</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')</label>
                                    <input class="form-control" type="text" name="firstname"
                                        value="{{ auth()->user()->firstname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Last Name')</label>
                                    <input class="form-control" type="text" name="lastname"
                                        value="{{ auth()->user()->lastname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Email')</label>
                                    <input class="form-control" type="email" name="email"
                                        value="{{ auth()->user()->email }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile')</label>
                                    <input class="form-control" type="text" name="mobile"
                                        value="{{ auth()->user()->mobile }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Address')</label>
                                    <input class="form-control" type="text" name="address"
                                        value="{{ auth()->user()->address->address }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('State')</label>
                                    <input class="form-control" type="text" name="state"
                                        value="{{ auth()->user()->address->state }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Zip')</label>
                                    <input class="form-control" type="text" name="zip"
                                        value="{{ auth()->user()->address->zip }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('City')</label>
                                    <input class="form-control" type="text" name="city"
                                        value="{{ auth()->user()->address->city }}">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Country')</label>
                                    <input class="form-control" type="text" name="country"
                                        value="{{ auth()->user()->address->country }}" >
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('user.change.password') }}" class="btn btn-lg btn--primary btn-block"><i class="fa fa-key"></i>@lang('Change Password')</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('style')
<style>
    .profile-wrapper .profile-user {
        text-align: center;
        width: 100%;
        max-width: 300px;
        margin: 0 auto 40px;
        position: relative;
    }
    
    .profile-wrapper .profile-form-area {
        width: calc(100% - 300px);
    }

    @media (min-width: 1200px) {
        .profile-wrapper .profile-form-area {
            padding-left: 60px;
        }
    }
	
	@media (max-width: 1199px) {
		.profile-wrapper .profile-form-area {
			width: 100%;
		}
	}
    
    .profile-wrapper {
        padding: 20px 0;
    }
    
    .text-end {
        text-align: right !important;
    }

</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        var prevImg = $('.profile-user .thumb').html();
        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $('.profile-user').find('.thumb');
                    preview.html(`<img src="${e.target.result}" alt="user">`);
                    preview.addClass('has-image');
                    preview.hide();
                    preview.fadeIn(650);
                    $(".remove-image").show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#profile-image").on('change', function() {
            proPicURL(this);
        });

        $(".remove-image").on('click', function() {
            $(".profile-user .thumb").html(prevImg);
            $(".profile-user .thumb").removeClass('has-image');
            $(this).hide();
        })

    })(jQuery);
</script>
@endpush
