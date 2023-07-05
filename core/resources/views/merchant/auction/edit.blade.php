@extends('merchant.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('merchant.product.auction.update', $auction->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-footer">
                        <button type="button" onclick="checkstatus()" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                    <div class="card-body">
                        <div class="payment-method-item">
                            <div class="payment-method-header">
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Image') <span class="text-danger">*</span></label>
                                    <div class="thumb" style="width: 350px;">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="width: 330px;background-image: url('{{getImage(imagePath()['product']['path'].'/'.$auction->image,imagePath()['product']['size'])}}');"></div>
                                            <div class="percent_div">
                                                <div class="progress_exit">
                                                    <i class="fas fa-times"></i>
                                                </div>
                                                <div id="progress_div" class="progress_div">
                                                    <span id="percent_span" class="percent_span">0%</span>
                                                    <div id="bar_div" class="bar_div"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input type="hidden" value='{{ csrf_token() }}' id="cs_token_hidden" >
                                            <input type="hidden" value='{{ route("admin.product.oneimage") }}' id="url_hidden" >
                                            <input type="hidden" value="{{ count($auction->imagereplaceinput) }}" id="imagenum_hidden" >
                                            <input type="file" style="padding: 0;" name="image" class="profilePicUpload" id="image" accept=".png, .jpg, .jpeg, .bmp" multiple/>
                                            <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                        </div>
                                    </div>
                                    <div style="width: 350px;" class="imagelist_block">
                                        @if ($auction->imagereplaceinput)
                                            @foreach ($auction->imagereplaceinput as $imgri)
                                                <div class="image_data_item" style="text-align: center;position: relative; width: 45px; height: 35px; margin: 2px 0;display: inline-block;">
                                                    <input name="imagereplaceinput[{{ $loop->iteration }}][url]" id="imagereplaceinput`+imagenum+`" type="hidden" value="{{ $imgri['url'] }}" required>
                                                    <img id="image_replace_id{{ $loop->iteration }}" src={{ getImage(imagePath()['product']['path'].'/'.$imgri['url']) }} class="replace-image" style="width: 45px;height: 35px; cursor: pointer;" >
                                                    <div style="position: absolute; right: 0; top: 0; transform: translate(50%,-50%); display: flex; justify-content: center; align-items: center; width: 10px; height: 10px; cursor: pointer; background-color: #ea5455; border-radius: 50%;" class="img_item_remove">
                                                        <i class="fa fa-times" style="font-size: 8px; color: white;"></i>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="content content_div_blk" style="width: calc(100% - 350px);">
                                    <div class="row mb-none-15">
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Name') <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control " placeholder="@lang('Product Name')" name="name" value="{{ $auction->name }}" required/>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Category') <span class="text-danger">*</span></label>
                                                <select name="category" class="form-control" required>
                                                    <option value="">@lang('Select One')</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ $auction->category_id == $category->id ? 'Selected':'' }}>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <label class="font-weight-bold">@lang('Price') <span class="text-danger">*</span></label>
                                            <div class="input-group has_append">
                                                <input type="text" class="form-control" placeholder="0" name="price" value="{{ getAmount($auction->price) }}" required/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">{{ __($general->cur_text) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Schedule') <span class="text-danger">*</span></label>
                                                <select name="schedule" class="form-control" required>
                                                    <option value="">Select schedule</option>
                                                    <option value="1weeks" {{ $auction->schedule == '1weeks' ? 'Selected' : '' }}>@lang('1 weeks')</option>
                                                    <option value="2weeks" {{ $auction->schedule == '2weeks' ? 'Selected' : '' }}>@lang('2 weeks')</option>
                                                    <option value="3weeks" {{ $auction->schedule == '3weeks' ? 'Selected' : '' }}>@lang('3 weeks')</option>
                                                    <option value="4weeks" {{ $auction->schedule == '4weeks' ? 'Selected' : '' }}>@lang('4 weeks')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-xl-4 col-lg-6 started_at">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Start') <span class="text-danger">now</span></label>
                                                <input type="text" name="started_at" placeholder="@lang('or select Date & Time')" id="startDateTime" data-position="bottom left" class="form-control border-radius-5" value="{{ $auction->started_at }}" autocomplete="off"/>
                                            </div>
                                        </div>
                                        <!--<div class="col-sm-12 col-xl-4 col-lg-6">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <label class="font-weight-bold">@lang('Expired_at') <span class="text-danger">*</span></label>-->
                                        <!--        <input type="text" name="expired_at" placeholder="@lang('Select Date & Time')" id="endDateTime" data-position="bottom left" class="form-control border-radius-5" value="{{ $auction->expired_at }}" autocomplete="off" required/>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="font-weight-bold">@lang('Headline')</label>
                                                <textarea rows="4" class="form-control border-radius-5" name="short_description">{{ $auction->short_description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mt-3">
                                <label class="font-weight-bold">@lang('Description') <span class="text-danger">*</span></label>
                                <textarea rows="8" class="form-control border-radius-5 nicEdit" name="long_description">{{ $auction->long_description }}</textarea>
                            </div>

                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="card border--primary mt-3">
                                        <h5 class="card-header bg--primary  text-white">@lang('Specification')
                                            <!-- <button type="button" class="btn btn-sm btn-outline-light float-right addUserData"><i class="la la-fw la-plus"></i>@lang('Add New')
                                            </button> -->
                                        </h5>

                                        <div class="card-body">
                                            <div class="row addedField">
                                                @if ($auction->specification)
                                                    @foreach ($auction->specification as $spec)
                                                        @if ($loop->iteration == 1)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Creator') <span class="text-danger">*</span> :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classCreator" type="text" value="{{ $spec['value'] }}" required placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div class="col-md-12 user-data">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                    <span>@lang('Dimensions') <span class="text-danger">*</span> :</span>
                                                                </div>
                                                                <div class="col-md-8 dimension_div" style="margin: auto; display: flex; justify-content: space-between;">
                                                                @foreach ($auction->specification as $spec)
                                                                    @if ($loop->iteration == 2)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" class="form-control dimensionHeight"  type="text" value="{{ $spec['value'] }}" style="margin: 0 5px 0 0;" placeholder="@lang('Height')">
                                                                    @elseif ($loop->iteration == 3)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" class="form-control dimensionWidth"  type="text" value="{{ $spec['value'] }}" style="margin: 0 5px;" placeholder="@lang('Width')">
                                                                    @elseif ($loop->iteration == 4)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" class="form-control dimensionDepth"  type="text" value="{{ $spec['value'] }}" style="margin: 0 5px;" placeholder="@lang('Depth')">
                                                                    @elseif ($loop->iteration == 5)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" class="form-control dimensionDiameter"  type="text" value="{{ $spec['value'] }}" style="margin: 0 5px;" placeholder="@lang('Diameter')">
                                                                    @elseif ($loop->iteration == 6)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" class="form-control dimensionWeight"  type="text" value="{{ $spec['value'] }}" style="margin: 0 0 0 5px;" placeholder="@lang('Weight')">
                                                                    @endif
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @foreach ($auction->specification as $spec)
                                                        @if ($loop->iteration == 7)
                                                            <div class="col-md-12 user-data">
                                                                <div class="form-group">
                                                                    <div class="input-group mb-md-0 mb-4">
                                                                        <div class="col-md-4" style="text-align: right; margin: auto;">
                                                                            <span>@lang('Age') <span class="text-danger">*</span> :</span>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                            <input name="specification[{{ $loop->iteration }}][value]" class="form-control classAge" type="text" value="{{ $spec['value'] }}" required placeholder="@lang('Field Value')">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div class="col-md-12 user-data">
                                                        <div class="form-group">
                                                            <div class="input-group mb-md-0 mb-4">
                                                                <div class="col-md-4" style="text-align: right; margin: 5px 0;">
                                                                    <span>@lang('Features'):</span>
                                                                </div>
                                                                <div class="col-md-8 feature_div" style="margin: auto; display: flex; flex-direction: column; justify-content: space-between;">
                                                                @foreach ($auction->specification as $spec)
                                                                    @if ($loop->iteration == 8)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_ec" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ec" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ec()" class="btn btn-outline--primary" id="btn-label-outlined-other-ec" for="btn-check-outlined-other-ec">Excellent Condition</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ec" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ec()" class="btn btn-outline--primary active" id="btn-label-outlined-other-ec" for="btn-check-outlined-other-ec">Excellent Condition</label>
                                                                        @endif
                                                                    @elseif ($loop->iteration == 9)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_fo" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-fo" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_fo()" class="btn btn-outline--primary" id="btn-label-outlined-other-fo" for="btn-check-outlined-other-fo">Certificated</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-fo" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_fo()" class="btn btn-outline--primary active" id="btn-label-outlined-other-fo" for="btn-check-outlined-other-fo">Certificated</label>
                                                                        @endif
                                                                    @elseif ($loop->iteration == 10)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_ml" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ml" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ml()" class="btn btn-outline--primary" id="btn-label-outlined-other-ml" for="btn-check-outlined-other-ml">Mentioned in Literature</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-ml" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_ml()" class="btn btn-outline--primary active" id="btn-label-outlined-other-ml" for="btn-check-outlined-other-ml">Mentioned in Literature</label>
                                                                        @endif
                                                                    @elseif ($loop->iteration == 11)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_le" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-le" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_le()" class="btn btn-outline--primary" id="btn-label-outlined-other-le" for="btn-check-outlined-other-le">Limited Edition</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-le" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_le()" class="btn btn-outline--primary active" id="btn-label-outlined-other-le" for="btn-check-outlined-other-le">Limited Edition</label>
                                                                        @endif
                                                                    @elseif ($loop->iteration == 12)
                                                                        <input type="hidden" value="{{ $spec['name'] }}" required name="specification[{{ $loop->iteration }}][name]" >
                                                                        <input name="specification[{{ $loop->iteration }}][value]" id="other_np" class="form-control" value="{{ $spec['value'] }}" type="hidden">
                                                                        @if ($spec['value'] == null && $spec['value'] == "")
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-np" autocomplete="off">
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_np()" class="btn btn-outline--primary" id="btn-label-outlined-other-np" for="btn-check-outlined-other-np">Noteworthy Provenance</label>
                                                                        @else
                                                                            <input type="checkbox" style="display: none;" class="btn-check" id="btn-check-outlined-other-np" autocomplete="off" checked >
                                                                            <label style="margin: 5px 0; width: 200px;" onclick="check_oth_np()" class="btn btn-outline--primary active" id="btn-label-outlined-other-np" for="btn-check-outlined-other-np">Noteworthy Provenance</label>
                                                                        @endif
                                                                    @endif
                                                                @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" onclick="checkstatus()" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                    <div class="card-footer" style="display: none;">
                        <button type="submit" id="formsubmit-btn" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('breadcrumb-plugins')
    <a href="{{ route('merchant.product.auction.index') }}" class="btn btn-sm btn--primary text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

@push('style')
    <style>
        .payment-method-item .payment-method-header .thumb .avatar-edit{
            bottom: auto;
            top: 165px;
        }
        
        .percent_div {
            width: 330px;
            height: 210px;
            position: absolute;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            z-index: 10;
            display: none;
            justify-content: center;
            align-items: center;
        }
        
        .progress_exit {
            position: absolute;
            top: 10px;
            right: 20px;
        }
        
        .progress_exit > i {
            font-size: 20px;
            color: #ea5455;
            cursor: pointer;
        }
        
        .progress_div {
            width: 80%;
            background-color: rgba(0, 0, 0, 0.8);
            position: relative;
        }
        
        .percent_span {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
        }
        
        .bar_div {
            width: 0%;
            height: 30px;
            background-color: #ea5455;
            text-align: right;
            line-height: 30px;
            color: white;
            transition: 0.5s all ease-in-out;
        }
        
        @media (max-width: 958px) {
            .dimension_div {
                flex-direction: column;
            }
            
            .dimension_div > input {
                margin: 5px 0 !important;
            }
            
            .content_div_blk {
                width: fit-content !important;
            }
        }
        
        @media (max-width: 768px) {
            .feature_div {
                align-items: flex-end;
            }
        }
        
        @media (max-width: 500px) {
            .feature_div > label {
                width: 100% !important;
            }
        }
        
    </style>
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
    <script>
        function checkstatus() {
            if($('input[name=name]').val() == "") {
                $('input[name=name]').focus();
                iziToast['error']({
                    message: "The name field is required.",
                    position: "topRight"
                });
            } else if($('select[name=category]').val() == "") {
                $('select[name=category]').focus();
                iziToast['error']({
                    message: "The category field is required.",
                    position: "topRight"
                });
            } else if($('input[name=price]').val() == "") {
                $('input[name=price]').focus();
                iziToast['error']({
                    message: "The price field is required.",
                    position: "topRight"
                });
            } else if($('select[name=schedule]').val() == "") {
                $('select[name=schedule]').focus();
                iziToast['error']({
                    message: "The schedule field is required.",
                    position: "topRight"
                });
            } else if($("div.nicEdit-main").html() == "<br>" || $("div.nicEdit-main").html() == "") {
                $('div.nicEdit-main').focus();
                iziToast['error']({
                    message: "The description field is required.",
                    position: "topRight"
                });
            } else if($('.classCreator').val() == "") {
                $('.classCreator').focus();
                iziToast['error']({
                    message: "The creator field is required.",
                    position: "topRight"
                });
            } else if($(".dimensionHeight").val() == "" && $(".dimensionWidth").val() == "" && $(".dimensionDepth").val() == "" && $(".dimensionDiameter").val() == "" && $(".dimensionWeight").val() == "") {
                $(".dimensionHeight").focus();
                iziToast['error']({
                    message: "Please type at least one dimension.",
                    position: "topRight"
                });
            } else if($('.classAge').val() == "") {
                $('.classAge').focus();
                iziToast['error']({
                    message: "The age field is required.",
                    position: "topRight"
                });
            } else {
                $("#formsubmit-btn").click();
            }
        }
    
        function check_de_hg() {
            if(document.getElementById("btn-check-outlined-demension-hg").checked) {
                $('#btn-label-outlined-demension-hg').removeClass("active");
                $('#demension_hg').val("");
            }
            else {
                $('#btn-label-outlined-demension-hg').addClass("active");
                $('#demension_hg').val("height");
            }
        }

        function check_de_wd() {
            if(document.getElementById("btn-check-outlined-demension-wd").checked) {
                $('#btn-label-outlined-demension-wd').removeClass("active");
                $('#demension_wd').val("");
            }
            else {
                $('#btn-label-outlined-demension-wd').addClass("active");
                $('#demension_wd').val("width");
            }
        }

        function check_de_dp() {
            if(document.getElementById("btn-check-outlined-demension-dp").checked) {
                $('#btn-label-outlined-demension-dp').removeClass("active");
                $('#demension_dp').val("");
            }
            else {
                $('#btn-label-outlined-demension-dp').addClass("active");
                $('#demension_dp').val("width");
            }
        }
        
        function check_de_dm() {
            if(document.getElementById("btn-check-outlined-demension-dm").checked) {
                $('#btn-label-outlined-demension-dm').removeClass("active");
                $('#demension_dm').val("");
            }
            else {
                $('#btn-label-outlined-demension-dm').addClass("active");
                $('#demension_dm').val("width");
            }
        }

        function check_oth_ec() {
            if(document.getElementById("btn-check-outlined-other-ec").checked) {
                $('#btn-label-outlined-other-ec').removeClass("active");
                $('#other_ec').val("");
            }
            else {
                $('#btn-label-outlined-other-ec').addClass("active");
                $('#other_ec').val("condition");
            }
        }

        function check_oth_fo() {
            if(document.getElementById("btn-check-outlined-other-fo").checked) {
                $('#btn-label-outlined-other-fo').removeClass("active");
                $('#other_fo').val("");
            }
            else {
                $('#btn-label-outlined-other-fo').addClass("active");
                $('#other_fo').val("owner");
            }
        }

        function check_oth_ml() {
            if(document.getElementById("btn-check-outlined-other-ml").checked) {
                $('#btn-label-outlined-other-ml').removeClass("active");
                $('#other_ml').val("");
            }
            else {
                $('#btn-label-outlined-other-ml').addClass("active");
                $('#other_ml').val("literature");
            }
        }

        function check_oth_ci() {
            if(document.getElementById("btn-check-outlined-other-ci").checked) {
                $('#btn-label-outlined-other-ci').removeClass("active");
                $('#other_ci').val("");
            }
            else {
                $('#btn-label-outlined-other-ci').addClass("active");
                $('#other_ci').val("collector");
            }
        }

        function check_oth_le() {
            if(document.getElementById("btn-check-outlined-other-le").checked) {
                $('#btn-label-outlined-other-le').removeClass("active");
                $('#other_le').val("");
            }
            else {
                $('#btn-label-outlined-other-le').addClass("active");
                $('#other_le').val("edition");
            }
        }

        function check_oth_np() {
            if(document.getElementById("btn-check-outlined-other-np").checked) {
                $('#btn-label-outlined-other-np').removeClass("active");
                $('#other_np').val("");
            }
            else {
                $('#btn-label-outlined-other-np').addClass("active");
                $('#other_np').val("provenance");
            }
        }

        (function ($) {
            "use strict";

            var specCount = `{{ $auction->specification ? count($auction->specification) : 0 }}`;
            specCount = parseInt(specCount);
            specCount = specCount ? specCount + 1 : 1;

            // Create start date
            var start = new Date(),
                    prevDay,
                    startHours = 0;

                // 09:00 AM
                start.setHours(0);
                start.setMinutes(0);

                // If today is Saturday or Sunday set 10:00 AM
                if ([6, 0].indexOf(start.getDay()) != -1) {
                    start.setHours(10);
                    startHours = 10
                }
            // date and time picker
            $('#startDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

            // date and time picker
            $('#endDateTime').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

            $('.progress_exit i').on('click', function () {
               $('.percent_div').css('display', 'none');
            });

            $('input[name=currency]').on('input', function () {
                $('.currency_symbol').text($(this).val());
            });
            $('.addUserData').on('click', function () {
                var html = `
                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-4">
                                    <input name="specification[${specCount}][name]" class="form-control" type="text" required placeholder="@lang('Field Name')">
                                </div>
                                <div class="col-md-6">
                                    <input name="specification[${specCount}][value]" class="form-control" type="text" required placeholder="@lang('Field Value')">
                                </div>
                                <div class="col-md-2 mt-md-0 mt-2 text-right">
                                    <span class="input-group-btn">
                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('.addedField').append(html);
                specCount += 1;
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });
            
            $(document).on('click', '.replace-image', function () {
                $('.profilePicPreview').css('background-image', 'url(' + $(this).attr("src") + ')');
            });

            $(document).on('click', '.img_item_remove', function () {
                $(this).closest('.image_data_item').remove();
            });

            @if(old('currency'))
                $('input[name=currency]').trigger('input');
            @endif

            // $("[name=schedule]").on('change', function(e){
            //     var schedule = e.target.value;

            //     if(schedule != 1){
            //         $("[name=started_at]").attr('disabled', true);
            //         $('.started_at').css('display', 'none');
            //     }else{
            //         $("[name=started_at]").attr('disabled', false);
            //         $('.started_at').css('display', 'block');
            //     }
            // }).change();

        })(jQuery);
    </script>
@endpush
