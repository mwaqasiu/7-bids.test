@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Icon')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($paymentmethods as $method)
                            <tr>
                                <td data-label="@lang('S.N')">{{ $paymentmethods->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Icon')">
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.$method->icon) }}" width="50px" height="30px" />
                                </td>
                                <td data-label="@lang('Name')">{{ __($method->name) }}</td>
                                <td data-label="@lang('Status')">
                                    @if($method->status == 1)
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <button type="button" class="icon-btn editPayment" data-id="{{ $method->id }}" data-name="{{ __($method->name) }}" data-filenames="{{ $method->filename }}" data-icon="{{ $method->icon }}" data-status="{{ $method->status }}" data-toggle="tooltip"  data-original-title="@lang('Edit')">
                                        <i class="las la-pen text-shadow"></i>
                                    </button>
                                    <button class="icon-btn btn--danger deletePayment" data-id="{{ $method->id }}"><i class="la la-trash"></i></button>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if($paymentmethods->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($paymentmethods) }}
                </div>
                @endif
            </div>
        </div>
    </div>

{{-- Payment Method modal --}}
<div id="paymentModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.paymentmethod.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Name')<span class="text-danger">*</span></label>
                        <div class="input-group has_append">
                            <input type="text" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Icon')<span class="text-danger">*</span></label>
                        <div class="input-group has_append">
                            <input type="file" class="icon_file" style="display: none;" id="files" accept=".png, .jpg, .jpeg, .bmp" />
                            <input type="hidden" name="imageurl" id="imageurl" value="" required />
                            <input type="text" class="form-control" id="imageicon" value="" name="imageiconname" readonly required />
                            <div class="input-group-append">
                                <label class="btn btn-outline-secondary" style="margin: 0; display: flex; justify-content: center; align-items: center;" for="files">
                                    <i class="las la-plus"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group statusGroup">
                        <label>@lang('Status')</label>
                        <input type="checkbox" data-onstyle="-success" data-offstyle="-danger" data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" data-width="100%" name="status">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block">@lang('Submit')</button>
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
            <form action="{{route('admin.paymentmethod.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this payment method') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" name="paymentmethod_id">
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
    <button type="button" class="btn btn-sm btn--primary box--shadow1 text--small addPayment"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</button>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            var modal   = $('#paymentModal');
            var action  = `{{ route('admin.paymentmethod.store') }}`;
            
            $('.icon_file').on('change', function() {
                iconimageuploadfunc(this);
            });
            
            async function iconimageuploadfunc(input) {
                if (input.files && input.files[0]) {
                    if(Number(input.files[0].size / 1024 / 1024) <= 3) {
                        var reader = new FileReader();
                        
                        reader.onload = function (e) {
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
                            iziToast['success']({
                                message: "Success!",
                                position: "topRight"
                            });
                            document.getElementById('imageicon').value = input.files[0].name;
                            document.getElementById("imageurl").value = responseURL;
                          },
                          error: function(data){
                            return;
                          }
                        });
                    } else {
                        iziToast['error']({
                            message: "Size is larger than 3MB!",
                            position: "topRight"
                        });
                    }
                }
            }

            $('.addPayment').click(function(){
                modal.find('.modal-title').text("@lang('New Payment Method')");
                modal.find('.statusGroup').hide();
                modal.find('form').attr('action', action);
                modal.modal('show');
            });

            modal.on('shown.bs.modal', function (e) {
                $(document).off('focusin.modal');
            });

            $('.editPayment').click(function () {
                var data = $(this).data();
                modal.find('.modal-title').text("@lang('Edit Payment Method')");
                modal.find('.statusGroup').show();
                modal.find('[name=name]').val(data.name);
                modal.find('[id=imageurl]').val(data.icon);
                modal.find('[id=imageicon]').val(data.filenames);

                if(data.status == 1){
                    modal.find('input[name=status]').bootstrapToggle('on');
                }else{
                    modal.find('input[name=status]').bootstrapToggle('off');
                }

                modal.find('form').attr('action', `${action}/${data.id}`);
                modal.modal('show');
            })

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });
            
            $(document).on('click', '.deletePayment', function(e) {
                var modal = $('#deleteModal');
                $('input[name="paymentmethod_id"]').val($(this).data('id'));
                modal.modal('show');
            });

            $('.iconPicker').iconpicker().on('change', function (e) {
                $(this).parent().siblings('.icon').val(`<i class="${e.icon}"></i>`);
            });

        })(jQuery);
    </script>
@endpush
