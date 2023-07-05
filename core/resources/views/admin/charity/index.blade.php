@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card">
                <form action="" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                          <label>@lang('Charity Hide/Show')</label>
                          <div>
                              <label class="switch">
                                <input type="checkbox" name="pageflag" id="pageflag" {{ $charitydata->data_values->pageflag == 1 ? "checked" : "" }}>
                                <span class="slider round"></span>
                              </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>@lang('Blog Hide/Show')</label>
                          <div>
                              <label class="switch">
                                <input type="checkbox" name="blogpageflag" id="blogpageflag" {{ $charitydata->data_values->blogpageflag == 1 ? "checked" : "" }}>
                                <span class="slider round"></span>
                              </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label>@lang('Image')</label>
                          <div class="charityPicUploadView" style="background-image: url('{{getImage(imagePath()['product']['path'].'/'.$charitydata->data_values->url,imagePath()['product']['size'])}}');">
                                <div class="image-edit">
                                    <input type="hidden" name="imageurl" id="imageurl" value="{{ $charitydata->data_values->url }}" required />  
                                    <input type="file" style="width: 0; opacity: 0; display: none; padding: 0;" name="image" class="charityPicUpload" id="image" accept=".png, .jpg, .jpeg" >
                                    <label for="image" class="bg--primary">
                                        <i class="la la-pencil"></i>
                                    </label>
                                </div>
                                <div class="loadingview">
                                    <img src="https://www.1400g.de/assets/images/loading.gif" style="width: 70px; height: 70px;" />
                                </div>
                          </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Amount')</label>
                            <input type="number" class="form-control " placeholder="@lang('Amount')" name="amount" value="{{ $charitydata->data_values->amount }}" required/>
                        </div>
                        <div class="form-group">
                            <label>@lang('Add/Subtract')</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" class="form-control " placeholder="@lang('Balance')" name="subamount" value="{{ old('subamount') }}" required/>
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Add')" data-off="@lang('Subtract')" data-width="100%" name="status" checked>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label>@lang('Description') English</label>
                            <textarea class="form-control nicEdit" rows="10" name="description" placeholder="@lang('Description')">@php echo @$charitydata->data_values->description @endphp</textarea>
                        </div>
                        <div class="form-group">
                          <label>@lang('Description') German</label>
                            <textarea class="form-control nicEdit" rows="10" name="german_description" placeholder="@lang('Description')">@php echo @$charitydata->data_values->german_description @endphp</textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    .charityPicUploadView {
        width: 320px;
        height: 210px;
        display: block;
        border: 3px solid #f1f1f1;
        box-shadow: 0 0 5px 0 rgb(0 0 0 / 25%);
        border-radius: 10px;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .image-edit {
        position: absolute;
        bottom: auto;
        top: 160px;
        right: 0px;
    }
    
    .image-edit > label {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        text-align: center;
        line-height: 45px;
        border: 2px solid #fff;
        font-size: 18px;
        cursor: pointer;
    }
    
    .loadingview {
        position: absolute;
        top: 0;
        left: 0;
        width: 314px;
        background: rgba(0, 0, 0, 0.4);
        display: none;
        justify-content: center;
        align-items: center;
        height: 204px;
        border-radius: 10px;
    }
    
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 28px;
    }
    
    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    input:checked + .slider {
      background-color: #2196F3;
    }
    
    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }
    
    input:checked + .slider:before {
      -webkit-transform: translateX(22px);
      -ms-transform: translateX(22px);
      transform: translateX(22px);
    }
    
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }
    
    .slider.round:before {
      border-radius: 50%;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        async function uploadimageURL(input) {
            if (input.files && input.files[0]) {
                if(Number(input.files[0].size / 1024 / 1024) <= 3) {
                    var reader = new FileReader();
                    
                    reader.onload = function (e) {
                        document.getElementById("imageurl").value = "";
                        $('.loadingview').css('display', 'flex');
                        $('.charityPicUploadView').css('background-image', 'url(' + e.target.result + ')');
                        $('.charityPicUploadView').addClass('has-image');
                        $('.charityPicUploadView').hide();
                        $('.charityPicUploadView').fadeIn(650);
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
                        document.getElementById("imageurl").value = responseURL;
                        $('.loadingview').css('display', 'none');
                        iziToast['success']({
                            message: "Upload Success!",
                            position: "topRight"
                        });
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
        
        $(".charityPicUpload").on('change', function() {
            uploadimageURL(this);
        });
    })(jQuery);
</script>
@endpush