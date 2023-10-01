@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="pt-3 pb-120">
    <div class="container mb-5">
        <div style="width: 100%; display: flex; justify-content: center; align-items: center;">
            <div id="demo" class="carousel slide" data-bs-ride="carousel" style="width: 85%;">
                <!-- Indicators/dots -->
                <div class="carousel-indicators">
                    @foreach($sliders as $slider)
                        @if($loop->iteration - 1 == 0)
                            <button type="button" data-bs-target="#demo" style="width: 15px; height: 15px; border-radius: 50%;" data-bs-slide-to="0" class="active"></button>
                        @else
                            <button type="button" data-bs-target="#demo" style="width: 15px; height: 15px; border-radius: 50%;" data-bs-slide-to="{{ $loop->iteration - 1 }}"></button>        
                        @endif
                    @endforeach
                </div>
                
                <!-- The slideshow/carousel -->
                <div class="carousel-inner">
                    @foreach($sliders as $slider)
                        @if($loop->iteration - 1 == 0)
                            <div class="carousel-item active">
                                <div style="width: 25%; position: relative; margin: auto; padding-top: 25%;">
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}" alt="{{ $slider->url }}" class="d-block" style="width: 100%; height: 100%; position: absolute; left: 0; right: 0; bottom: 0; top: 0; object-fit: fill;">
                                </div>
                            </div>
                        @else
                            <div class="carousel-item">
                                <div style="width: 25%; position: relative; margin: auto; padding-top: 25%;">
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}" alt="{{ $slider->url }}" class="d-block" style="width: 100%; height: 100%; position: absolute; left: 0; right: 0; bottom: 0; top: 0; object-fit: fill;">
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <form action="{{ route('sellwithus.send') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-center">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form--group col-md-12">
                                    <h4 class="sellwithush4">@lang('What would you like to sell?')</h4>
                                    <p class="mt-2" style="font-style: italic;">@lang('Give us as much informations as possible')</p>
                                </div>
                                <div class="form--group col-md-12 mt-4">
                                    <input type="text" class="form-control form--control form--control-2 inputsellwithus" name="artist" id="artist" placeholder="@lang('Artist or Maker')" value="" required>
                                </div>
                                <div class="form--group col-md-12">
                                    <input type="text" class="form-control form--control form--control-2 inputsellwithus" name="measure" id="measure" placeholder="@lang('Measurements')" value="" required>
                                </div>
                                <div class="form--group col-md-12">
                                    <textarea class="form-control form--control-2 selladdinfo inputsellwithus" name="addinfo" id="addinfo" placeholder="@lang('Additional informations')" required></textarea>
                                </div>
                                <input type="hidden" value="{{ getenv('REMOTE_ADDR') }}" name="ipaddress" id="ipaddress" required />
                                <div class="form--group col-md-12">
                                    <div style="display: inline-block;">
                                        @lang('Is the art object damaged or restored?')
                                    </div>
                                    <div style="display: inline-block;">
                                        <input type="radio" value="1" name="damagestatus" id="damagestatus" class="checkinput" required /> @lang('Yes')
                                        <input type="radio" value="2" name="damagestatus" id="damagestatus" class="checkinput" required /> @lang('No')
                                        <input type="radio" value="0" name="damagestatus" id="damagestatus" class="checkinput" required /> @lang('I don`t know')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="row justify-center">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form--group col-md-12">
                                    <h5 class="sellwithush4">@lang('Upload photos of your art object')</h5>
                                    <p class="mt-1" style="font-style: italic;">@lang('You can upload up to 6 images')</p>
                                </div>
                                <div class="form--group col-md-12">
                                    <input type="file" class="uploadimageinput selluploadimageinput1" name="uploadimage1" id="uploadimage1" multiple accept=".png, .jpg, .jpeg, .bmp" />
                                    <label for="uploadimage1" class="selluploadimage1">
                                        <div class="uploadbtnstyle">
                                            <i class="las la-upload"></i>
                                            <span>@lang('JPEG, GIF and PNG formats up to 3 MB are supported. Please send also files of certifications, valuations and receipts if you have.')</span>
                                        </div>
                                    </label>
                                    <div class="selluploadimageview selluploadimageview1">
                                        <label for="uploadimage1" >
                                            <i class="las la-upload"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="row justify-center">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form--group col-md-12">
                                    <h5 class="sellwithush4">@lang('Personal informations')</h5>
                                    <p class="mt-1" style="font-style: italic;">@lang('Your data will remain confidential.')</p>
                                </div>
                               {{-- <div class="form--group col-md-12 mb-4 mt-4">
                                    <input type="radio" name="sex" class="radioinput" value="0" required /> @lang('Madam')
                                    <input type="radio" name="sex" class="radioinput" value="1" required /> @lang('Mister')
                                </div>  --}}
                                <div class="form--group col-md-12">
                                    <input type="text" class="form-control form--control form--control-2 inputsellwithus" name="username" id="username" placeholder="@lang('Name')" value="" required>
                                </div>
                                <div class="form--group col-md-12">
                                    <input type="text" class="form-control form--control form--control-2 inputsellwithus" name="email" id="email" placeholder="@lang('Email address')" value="" required>
                                </div>
                                <div class="form--group col-md-12" style="text-align: center; margin-top: 40px;">
                                    <div class="row justify-center">
                                        <div class="col-md-6">
                                            <button type="button" class="cmn--btn w-100 sellwithusinquiryrealbtn">@lang('SUBMIT YOUR INQUIRY')</button>
                                            <button type="submit" class="cmn--btn w-100 sellwithusinquirybtn">@lang('SUBMIT YOUR INQUIRY')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('style')
<style>
    .justify-center {
        justify-content: center;
    }
    
    .checksection {
        display: flex;
        align-items: center;
    }
    
    .uploadimageinput {
        padding: 0;
        margin: 0;
        width: 0;
        display: none;
    }
    
    .checkinput {
        margin-right: 3px;
        margin-left: 10px;
    }
    
    .radioinput {
        margin-right: 3px;
        margin-left: 20px;
    }
    
    .uploadbtnstyle {
        display: flex;
        align-items: center;
        border-radius: 5px;
        border-style: dashed;
        border-width: 2px;
        border-color: #fff;
        padding: 15px;
        cursor: pointer;
    }
    
    .uploadbtnstyle > i {
        font-size: 40px;
        margin-right: 10px;
    }
    
    .sellwithusinquirybtn {
        display: none;
        font-weight: bolder;
        font-size: 18px;
        text-transform: uppercase;
    }
    
    .sellwithusinquiryrealbtn {
        font-weight: bolder;
        font-size: 18px;
        text-transform: uppercase;
    }
    
    .form--control-2 {
        background: #336699;
    }
    
    .form--control-2::-webkit-input-placeholder {
        color: #fff;
    }
    
    .form--control-2:-moz-placeholder {
        color: #fff;
    }
    
    .form--control-2::-moz-placeholder {
        color: #fff;
    }
    
    .form--control-2:-ms-input-placeholder {
        color: #fff;
    }
    
    .selluploadimageview {
        width: 100%;
        display: none;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }
    
    .selluploadimageview > div {
        position: relative;
        margin: 10px 5px;
    }
    
    .selluploadimageview > div > div {
        position: absolute;
        right: 5px;
        top: 5px;
        transform: translate(50%,-50%);
        display: flex;
        justify-content: center;
        align-items: center;
        width: 20px;
        height: 20px; 
        cursor: pointer;
        background-color: #ea5455;
        border-radius: 50%;
    }
    
    .selluploadimageview > div > div > i {
        font-size: 12px;
        color: white;
    }
    
    .selluploadimageview > div > img {
        width: 100px;
        height: 100px;
    }
    
    .selluploadimageview > label {
        width: 100px;
        height: 100px; 
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px dashed #fff;
        cursor: pointer;
        margin: 10px 5px;
    }
    
    .selluploadimageview > label > i {
        font-size: 40px;
    }
    
    @media only screen and (max-width: 768px) {
        .carousel-indicators [data-bs-target] {
            height: 10px !important;
            width: 10px !important;
        }
        
        .carousel-indicators {
            margin-bottom: 0px !important;
        }
    }
    
    .inputsellwithus:focus::placeholder {
        color: transparent !important;
    }
    
</style>
@endpush

@push('script')
<script>
    var sellimagenum1 = 0;
    var sellimagenum2 = 0;
    var sellimagenum3 = 0;
    var sellimagenum4 = 0;
    
    var sellimagecount1 = 0;
    var sellimagecount2 = 0;
    var sellimagecount3 = 0;
    var sellimagecount4 = 0;
    
    var warningmsg = "@lang('Please fill in all the blanks.')";
    
    (function ($) {
        "use strict";
        
        $('.sellwithusinquiryrealbtn').on('click', function() {
            if($('#artist').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#artist').focus();
                return;
            } else if($('#measure').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#measure').focus();
                return;
            } else if($('#addinfo').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#addinfo').focus();
                return;
            } else if($('#damagestatus').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#damagestatus').focus();
            } /*else if($('.radioinput').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('.radioinput').focus();
                return;
            }*/ else if($('#username').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#username').focus();
                return;
            } else if($('#email').val().trim() == "") {
                iziToast['warning']({
                    message: warningmsg,
                    position: "topRight"
                });
                $('#email').focus();
                return;
            } else if(sellimagecount1 == 0) {
                iziToast['warning']({
                    message: "@lang('Please upload minimum one photo.')",
                    position: "topRight"
                });
                $('.selluploadimageinput1').focus();
            } else {
                $('.sellwithusinquirybtn').click();
            }
        });
        
        async function selluploadimageURL1(input) {
            if (input.files && input.files[0]) {
                for(var ii = 0; ii < input.files.length; ii ++) {
                    if(sellimagecount1 >= 6) {
                        iziToast['error']({
                            message: "You can upload up to 6.",
                            position: "topRight"
                        });
                        return;
                    } else {
                        if(Number(input.files[0].size / 1024 / 1024) <= 3) {
                            var reader = new FileReader();
                            
                            reader.onload = function (e) {
                                $('.selluploadimage1').css('display', 'none');
                                $('.selluploadimageview1').css('display', 'flex');
                                $('.selluploadimageview1').append(`<div class="sellimage_data_item"><input name="sellimagereplaceinput1id[`+sellimagenum1+`][url]" id="sellimagereplaceinput1id`+sellimagenum1+`" type="hidden" required><img id="sellimagereplace1id` + sellimagenum1 +`" src="assets/images/loading.gif"/><div class="sellimg_item_remove1"><i class="fa fa-times"></i></div></div>`);
                            }
                            
                            reader.readAsDataURL(input.files[0]);
                            
                            var token = "{{ csrf_token() }}";
                            var url = '{{ route("sellwithus.oneimageupload") }}';
                    
                            var formData = new FormData();
                            formData.append("imagefile", input.files[0]);
                            formData.append("_token", token);
                            
                            await $.ajax({
                              method: 'post',
                              processData: false,
                              contentType: false,
                              cache: false,
                              data: formData,
                              enctype: 'multipart/form-data',
                              url: url,
                              success: function (responseURL) {
                                document.getElementById("sellimagereplaceinput1id"+sellimagenum1).value = responseURL;
                                document.getElementById("sellimagereplace1id"+sellimagenum1).src = "assets/images/product/" + responseURL;
                                sellimagenum1 ++;
                                sellimagecount1 ++;
                              },
                              error: function(data){
                                return;
                              }
                            });
                        }
                        else {
                            iziToast['error']({
                                message: "Size is larger than 3MB!",
                                position: "topRight"
                            });
                        }
                    }
                }
            }
        }
        
        $(".selluploadimageinput1").on('change', function() {
            if(sellimagecount1 >= 6) {
                iziToast['error']({
                    message: "You can upload up to 6.",
                    position: "topRight"
                });
            }
            else {
                selluploadimageURL1(this);
            }
        });
        
        $(document).on('click', '.sellimg_item_remove1', function () {
            $(this).closest('.sellimage_data_item').remove();
            sellimagecount1 --;
            if(sellimagecount1 == 0) {
                $('.selluploadimage1').css('display', 'inline-block');
                $('.selluploadimageview1').css('display', 'none');
            }
        });
    })(jQuery);
</script>
@endpush
