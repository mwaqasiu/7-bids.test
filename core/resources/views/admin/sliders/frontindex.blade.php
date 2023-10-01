@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Main Heading')</th>
                                    <th>@lang('Sub text')</th>
                                    <th>@lang('Link')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($sliders) == 0)
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @else
                                    @foreach($sliders as $slider)
                                        <tr>
                                            <td data-label="@lang('S.N')">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td data-label="@lang('Image')">
                                                <img style="width: 50px;" src="{{ getImage(imagePath()['product']['path'].'/'.$slider->url) }}">
                                            </td>
                                            <td>
                                               <i class="las la-pencil-alt text--shadow" data-toggle="tooltip" data-original-title="{{$slider->main_heading}}"></i>
                                            </td>
                                            <td>
                                                <i class="las la-pencil-alt text--shadow" data-toggle="tooltip" data-original-title="{{$slider->sub_text}}"></i>
                                            </td>
                                            <td>
                                                <i class="las la-edit" data-toggle="tooltip" data-original-title="{{$slider->slider_link}}"></i>
                                            </td>
                                            <td data-label="@lang('Status')">
                                                @if($slider->status == 0)
                                                    <span class="text--small badge font-weight-normal badge--danger">@lang('Pending')</span>
                                                @else
                                                    <span class="text--small badge font-weight-normal badge--success">@lang('Live')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Action')">
                                                <button type="button" class="icon-btn btn--success sliderEditBtn" data-toggle="tooltip" data-original-title="@lang('Edit')" data-url="{{ $slider->url }}" data-id="{{ $slider->id }}" data-mainheading="{{ $slider->main_heading }}" data-subtext="{{ $slider->sub_text }}" data-engmainheading="{{ $slider->eng_main_heading }}" data-engsubtext="{{ $slider->eng_sub_text }}" data-sliderlink="{{ $slider->slider_link }}">
                                                    <i class="las la-edit text--shadow"></i>
                                                </button>
                                                @if($slider->status == 0)
                                                    <button type="button" class="icon-btn btn--success sliderliveBtn" data-toggle="tooltip" data-original-title="@lang('Live')" data-id="{{ $slider->id }}">
                                                        <i class="las la-check text--shadow"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="icon-btn btn--danger sliderpendingBtn" data-toggle="tooltip" data-original-title="@lang('Pending')" data-id="{{ $slider->id }}">
                                                        <i class="las la-check text--shadow"></i>
                                                    </button>
                                                @endif
                                                <button class="icon-btn btn--danger deleteOneSlider" data-id="{{ $slider->id }}">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div id="addSliderModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Item')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="px-3" action="{{route('admin.add.frontsliders')}}" method="POST">
                    @csrf
                    <div class="modal-body" style="display: flex; justify-content: center;">
                        <input type="file" class="slideuploadimageinput" name="uploadimage" id="uploadimage" accept=".png, .jpg, .jpeg, .bmp" required />
                        <div class="slideuploadimageview">
                            <label for="uploadimage" class="uploadimagelabel" >
                                <i class="las la-upload"></i>
                            </label>
                            <div class="slideitemview">
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Main Heading German</label>
                        <input type="text" class="form-control" name="main_heading" placeholder="Enter main heading">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Sub Text German</label>
                        <textarea type="text" class="form-control resize--none" name="sub_text" placeholder="Enter sub text"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Main Heading English</label>
                        <input type="text" class="form-control" name="eng_main_heading" placeholder="Enter main heading">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Sub Text English</label>
                        <textarea type="text" class="form-control resize--none" name="eng_sub_text" placeholder="Enter sub text"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Link</label>
                        <input type="url" class="form-control" name="slider_link" placeholder="link">
                    </div>
                    <input type="hidden" name="slider_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('ADD')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Edit MODAL --}}
    <div id="editSliderModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Item')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="px-3" action="{{route('admin.edit.frontsliders')}}" method="POST">
                    @csrf
                    <div class="modal-body" style="display: flex; justify-content: center;">
                        <div class="slideuploadimageview">
                            <label for="uploadimage" class="edituploadimagelabel" >
                                <i class="las la-upload"></i>
                            </label>
                            <div class="editslideitemview">
                                <div class="slideitemimageview">
                                    <input name="edit_url" id="edit_url" type="hidden" required>
                                    <img src="https://7-bids.com/assets/images/loading.gif" id="editsliderimageitem" class="sliderimageitem" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Main Heading German</label>
                        <input type="text" class="form-control" name="edit_main_heading" placeholder="Enter main heading">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Sub Text German</label>
                        <textarea type="text" class="form-control resize--none" name="edit_sub_text" placeholder="Enter sub text"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Main Heading English</label>
                        <input type="text" class="form-control" name="edit_eng_main_heading" placeholder="Enter main heading">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Sub Text English</label>
                        <textarea type="text" class="form-control resize--none" name="edit_eng_sub_text" placeholder="Enter sub text"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Link</label>
                        <input type="url" class="form-control" name="edit_slider_link" placeholder="link">
                    </div>
                    <input type="hidden" name="slider_edit_id" id="slider_edit_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('EDIT')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Delete Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.delete.frontsliders')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
                    </div>
                    <input type="hidden" name="slider_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Live slider MODAL --}}
    <div id="liveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Live Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.live.frontsliders')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('live')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
                    </div>
                    <input type="hidden" name="slider_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Pending slider MODAL --}}
    <div id="pendingModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Pending Confirmation')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.pending.frontsliders')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('pend')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
                    </div>
                    <input type="hidden" name="slider_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')

<div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">
    <button class="btn btn--primary box--shadow1 text--small addSliderbtn" href="#"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</button>
</div>

@endpush

@push('style')
    <style>
        .slideuploadimageinput {
            display: none;
        }

        .slideuploadimageview {
            width: 120px;
            height: 80px;
            border: 1px dashed #000;
            border-radius: 5px;
        }

        .slideuploadimageview > label {
            cursor: pointer;
            margin: 0;
            width: 120px;
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slideuploadimageview > label > i {
            font-size: 50px;
        }

        .sliderimageitem {
            width: 120px;
            height: 80px;
        }

        .slidermodalbody {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .slideitemview {
            width: 120px;
            height: 80px;
            position: relative;
        }
        
        .editslideitemview {
            width: 120px;
            height: 80px;
            position: relative;
        }

        .slideitemimageview {
            width: 120px;
            height: 80px;
            position: relative;
        }

        .slideitemremove {
            position: absolute;
            right: 2px;
            top: 2px;
            transform: translate(50%,-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            width: 16px;
            height: 16px;
            cursor: pointer;
            background-color: #ea5455;
            border-radius: 50%;
        }

        .slideitemremove > i {
            font-size: 10px;
            color: #fff;
        }
        
        .editslideitemremove {
            position: absolute;
            right: 2px;
            top: 2px;
            transform: translate(50%,-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            width: 16px;
            height: 16px;
            cursor: pointer;
            background-color: #ea5455;
            border-radius: 50%;
        }

        .editslideitemremove > i {
            font-size: 10px;
            color: #fff;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            async function slideuploadimageURL(input) {
                if (input.files && input.files[0]) {
                    if(Number(input.files[0].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('.uploadimagelabel').css('display', 'none');
                            $('.slideitemview').css('display', 'block');
                            $('.slideitemview').append(`<div class="slideitemimageview"><input name="sellimagereplaceinputid" id="sellimagereplaceinputid" type="hidden" required><img src="https://7-bids.com/assets/images/loading.gif" id="sliderimageitem" class="sliderimageitem" /><div class="slideitemremove"><i class="fa fa-times"></i></div></div>`);
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
                            document.getElementById("sellimagereplaceinputid").value = responseURL;
                            document.getElementById("sliderimageitem").src = "https://7-bids.com/assets/images/product/" + responseURL;
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

            $(document).on('click', '.deleteOneSlider', function(e) {
                var modal = $('#deleteModal');
                $('input[name="slider_id"]').val($(this).data('id'));
                modal.modal('show');
            });

            $(document).on('click', '.sliderliveBtn', function(e) {
                var modal = $('#liveModal');
                $('input[name="slider_id"]').val($(this).data('id'));
                modal.modal('show');
            });
            
            $(document).on('click', '.sliderEditBtn', function(e) {
                var modal = $('#editSliderModal');
                $('input[name="slider_edit_id"]').val($(this).data('id'));
                $('input[name="edit_main_heading"]').val($(this).data('mainheading'));
                $('textarea[name="edit_sub_text"]').val($(this).data('subtext'));
                $('input[name="edit_eng_main_heading"]').val($(this).data('engmainheading'));
                $('textarea[name="edit_eng_sub_text"]').val($(this).data('engsubtext'));
                $('input[name="edit_slider_link"]').val($(this).data('sliderlink'));
                
                $('.edituploadimagelabel').css('display', 'none');
                $('.editslideitemview').css('display', 'block');
                document.getElementById("edit_url").value = $(this).data('url');
                document.getElementById("editsliderimageitem").src = "https://7-bids.com/assets/images/product/" + $(this).data('url');
                            
                modal.modal('show');
            });

            $(document).on('click', '.sliderpendingBtn', function(e) {
                var modal = $('#pendingModal');
                $('input[name="slider_id"]').val($(this).data('id'));
                modal.modal('show');
            });

            $(document).on('click', '.addSliderbtn', function(e) {
                var modal = $('#addSliderModal');
                modal.modal('show');
            });

            $(".slideuploadimageinput").on('change', function() {
                slideuploadimageURL(this);
            });

            $(document).on('click', '.slideitemremove', function () {
                $(this).closest('.slideitemimageview').remove();
                $('.uploadimagelabel').css('display', 'flex');
                $('.slideitemview').css('display', 'none');
            });
            
        })(jQuery)
    </script>
@endpush