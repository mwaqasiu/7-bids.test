@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.frontend.sections.content', $key) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="element">
                        @if(@$data)
                            <input type="hidden" name="id" value="{{$data->id}}">
                        @endif
                        
                        <input type="hidden" id="pdfuploadurlhidden" value="{{ route("admin.product.onepdf") }}" />
                        <input type="hidden" id="pdfuploadtokenhidden" value="{{ csrf_token() }}" />
                        
                        <div class="form-row">
                            @php
                                $imgCount = 0;
                            @endphp
                            @foreach($section->element as $k => $content)
                                @if($k == 'images')
                                    @php
                                        $imgCount = collect($content)->count();
                                    @endphp
                                    @foreach($content as $imgKey => $image)
                                            <div class="col-md-4">
                                                <input type="hidden" name="has_image[]" value="1">
                                                <div class="form-group">
                                                    <label>{{ __(inputTitle($imgKey)) }}</label>
                                                    <div class="image-upload">
                                                        <div class="thumb">
                                                            <div class="avatar-preview">
                                                                <div class="profilePicPreview" style="background-image: url({{getImage('assets/images/frontend/' . $key .'/'. @$data->data_values->$imgKey,@$section->element->images->$imgKey->size) }})">
                                                                    <button type="button" class="remove-image"><i class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="avatar-edit">
                                                                <input type="file" class="profilePicUpload imagefrontelements" name="image_input[{{ $imgKey }}]" id="profilePicUpload{{ $loop->index }}" accept=".png, .jpg, .jpeg" required>
                                                                <label for="profilePicUpload{{ $loop->index }}" class="bg--primary">{{ __(inputTitle($imgKey)) }}</label>
                                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png')</b>.
                                                                    @if(@$section->element->images->$imgKey->size)
                                                                        | @lang('Will be resized to'):
                                                                        <b>{{@$section->element->images->$imgKey->size}}</b>
                                                                        @lang('px').
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    @endforeach
                                    <div class="@if($imgCount > 1) col-md-12 @else col-md-8 @endif">
                                        @push('divend')
                                    </div>
                                        @endpush

                                    @elseif($content == 'icon')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <div class="input-group has_append">
                                                    <input type="text" class="form-control icon" name="{{ $k }}" value="{{ @$data->data_values->$k }}" required>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary iconPicker" data-icon="{{ @$data->data_values->$k ? substr(@$data->data_values->$k,10,-6) : 'las la-home' }}" role="iconpicker"></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                @else
                                    @if($content == 'textarea')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" required>{{ @$data->data_values->$k}}</textarea>
                                            </div>
                                        </div>

                                    @elseif($content == 'textarea-nic')

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __(inputTitle($k)) }}</label>
                                                <textarea rows="10" class="form-control nicEdit" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" >{{ @$data->data_values->$k}}</textarea>
                                            </div>
                                        </div>

                                    @elseif($k == 'select')
                                        @php
                                            $selectName = $content->name;
                                        @endphp
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-control-label  font-weight-bold">{{__(inputTitle(@$selectName))}}</label>
                                                    <select class="form-control" name="{{ @$selectName }}">
                                                        @foreach($content->options as $selectItemKey => $selectOption)
                                                            <option value="{{ $selectItemKey }}" @if(@$data->data_values->$selectName == $selectItemKey) selected @endif>{{ __($selectOption) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                    @else
                                        @if($key == "policy_pages" && $k == "pdf")
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label style="cursor: pointer;" for="pdfuploadinput">@lang('PDF Upload') <i class="las la-upload" style="color: red;"></i></label>
                                                    <input type="file" id="pdfuploadinput" accept=".pdf,.PDF" style="display: none;" />
                                                    <input type="text" id="realpdfuploadinput" readonly class="form-control" placeholder="@lang('PDF Upload')" name="{{$k}}" value="{{ @$data->data_values->$k }}" required/>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ __(inputTitle($k)) }}</label>
                                                    <input type="text" class="form-control" placeholder="{{ __(inputTitle($k)) }}" name="{{$k}}" value="{{ @$data->data_values->$k }}" required/>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                            @stack('divend')
                        </div>

                        <div class="form-group">
                            @if(@$data)
                                <button type="submit" style="display: none;" class="btn btn--primary btn-block btn-lg updatesubmitbutton">@lang('Update')</button>
                                <button type="button" class="btn btn--primary btn-block btn-lg updatebuttonbtn">@lang('Update')</button>
                            @else
                                <button type="submit" style="display: none;" class="btn btn--primary btn-block btn-lg createsubmitbutton">@lang('Create')</button>
                                <button type="button" class="btn btn--primary btn-block btn-lg createbuttonbtn">@lang('Create')</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection



@push('breadcrumb-plugins')
    <a href="{{route('admin.frontend.sections',$key)}}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="fa fa-fw fa-backward"></i>@lang('Go Back')</a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush

@push('script')
    <script>

        (function ($) {
            "use strict";
            $('.iconPicker').iconpicker().on('change', function (e) {
                $(this).parent().siblings('.icon').val(`<i class="${e.icon}"></i>`);
            });
            
            $('.updatebuttonbtn').on('click', function() {
                if($('.imagefrontelements').val() == "") {
                    $('.imagefrontelements').focus();
                    iziToast['error']({
                        message: "The image field is required.",
                        position: "topRight"
                    });
                } else {
                    $('.updatesubmitbutton').click();
                }
            });
            
            $('.createbuttonbtn').on('click', function() {
                if($('.imagefrontelements').val() == "") {
                    $('.imagefrontelements').focus();
                    iziToast['error']({
                        message: "The image field is required.",
                        position: "topRight"
                    });
                } else {
                    $('.createsubmitbutton').click();
                }
            });
            
            $('#pdfuploadinput').on('change', async function() {
                if(this.files && this.files[0]) {
                    if(Number(this.files[0].size / 1024 / 1024) <= 10) {
                        var stoken = $('#pdfuploadtokenhidden').val();
                        var surl = $('#pdfuploadurlhidden').val()
                        
                        var formData = new FormData();
                        formData.append("pdffile", this.files[0]);
                        formData.append("_token", stoken);
                        
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: surl,
                          success: function (responseURL) {
                            iziToast['success']({
                                message: "PDF Upload Success!",
                                position: "topRight"
                            });
                            $('#realpdfuploadinput').val(responseURL);
                          },
                          error: function(data){
                            return;
                          }
                        });
                    } else {
                        iziToast['error']({
                            message: "Size is larger than 10MB!",
                            position: "topRight"
                        });
                    }
                }
            });
        })(jQuery);
    </script>
@endpush
