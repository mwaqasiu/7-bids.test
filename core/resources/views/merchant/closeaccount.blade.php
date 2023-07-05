@extends('merchant.layouts.app')

@section('panel')
<div class="row" style="margin: 0; margin-bottom: 20px;">
    <h6>@lang('Account balance must be square to delete the user account.')</h6>
</div>

<div class="row mb-none-30">
    <div class="col-xl-3 col-lg-4 col-sm-6 mb-30">
        <div class="dashboard-w1 bg--primary b-radius--10 box-shadow">
            <div class="details">
                <div class="numbers">
                    @if($widget['balance'] < 0)
                        <span class="currency-sign" style="color: #f44336 !important;">{{__($general->cur_sym)}}</span>
                        <span class="amount" style="color: #f44336 !important;">{{showAmount($widget['balance'])}}</span>
                    @else
                        <span class="currency-sign">{{__($general->cur_sym)}}</span>
                        <span class="amount">{{showAmount($widget['balance'])}}</span>
                    @endif
                </div>
                <div class="desciption">
                    <span class="text--small">@lang('Available Balance')</span>
                </div>
                @if($widget['balance'] <= 0)
                    <button class="btn btn-sm text--small bg--white text--black box--shadow3 mt-3 deleteOneAuction" style="background-color: rgba(0, 0, 0, 0.3) !important; color: #fff !important;">@lang('Delete Account')</button>
                @endif
            </div>
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
            <form action="{{route('merchant.closeaccount.close')}}" method="GET">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you really sure you want to ') <span class="font-weight-bold">@lang('delete')</span> @lang('your account') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <div style="padding: 0 1rem 1rem 1rem;">
                    <p>
                        @lang('You understand that by performing this action, you are permanently closing your account.')
                        @lang('This means that any content remaining to this account and all associated records will be deleted from the online platform.')
                        @lang('Deleted accounts can not be reactivated.')
                    </p>
                </div>
                <div class="modal-footer" style="justify-content: space-between;">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('CANCEL')</button>
                    <button type="submit" class="btn btn--primary">@lang('DELETE ACCOUNT')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            $(document).on('click', '.deleteOneAuction', function(e) {
                var modal = $('#deleteModal');
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
