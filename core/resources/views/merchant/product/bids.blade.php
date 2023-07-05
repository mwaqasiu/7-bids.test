@extends('merchant.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Marketplace Item')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Price Offer')</th>
                            <th>@lang('Date')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bids as $bid)
                        <tr>
                            <td data-label="@lang('Marketplace Item')">
                                <a href="{{ route('product.details', [$bid->product->id, slug($bid->product->name)]) }}" target="_blank">
                                    <img style="width: 30px; margin-right: 20px;" src="{{ getImage(imagePath()['product']['path'].'/'.$bid->product->image) }}">
                                    {{ __($bid->product->name) }}
                                </a>
                            </td>
                            <td data-label="@lang('User')">{{ __($bid->user->username) }}</td>
                            <td data-label="@lang('Price Offer')">{{ $general->cur_sym }}{{ showAmount($bid->amount) }}</td>
                            <td data-label="@lang('Date')">{{ showDateTime($bid->created_at) }}</td>
                            <td data-label="@lang('Action')">
                                <a class="icon-btn" style="background-color: #28a745; margin-right: 7px;" href="{{ route('merchant.bid.priceofferwinner', $bid->id) }}" data-toggle="tooltip" data-original-title="@lang('Accept Offer')">
                                    <i class="las la-thumbs-up"></i>
                                </a>
                                <a class="icon-btn" style="background-color: #dc3545; margin-right: 7px;" href="{{ route('merchant.bid.priceofferdecline', $bid->id) }}" data-toggle="tooltip" data-original-title="@lang('Decline Offer')">
                                    <i class="las la-thumbs-down"></i>
                                </a>
                                <button type="button" class="icon-btn bid-details" data-toggle="tooltip" data-original-title="@lang('Details')"
                                    data-bid_id="{{ $bid->id }}"
                                    data-product_name="{{ __($bid->product->name) }}"
                                    data-product_price="{{ $general->cur_sym }}{{ showAmount($bid->product->price) }}"
                                    data-user_name="{{ __($bid->user->username) }}" 
                                    data-country_code="{{ __($bid->user->country_code) }}" 
                                    data-date_time="{{ showDateTime($bid->created_at) }}"
                                    data-amount="{{ $general->cur_sym }}{{ showAmount($bid->amount) }}"
                                    >
                                    <i class="las la-desktop text--shadow"></i>
                                </button>
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
            @if ($bids->hasPages())    
                <div class="card-footer py-4">
                    {{ paginateLinks($bids) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- BId modal --}}
<div id="bidModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('merchant.bid.winner') }}" method="POST">
                @csrf
                <div class="modal-body">
                   <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Item'):
                                <span class="product-name"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Asking Price'):
                                <span class="product-price"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('User'):
                                <span class="bid-user-name"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Price Offer'):
                                <span class="bid-amount"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Time'):
                                <span class="bid-date-time"></span>
                            </li>
                        </ul>
                   </div>
                   <input type="hidden" name="bid_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
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
            $('.bid-details').click(function(){
                var modal = $('#bidModal');
                var data = $(this).data();
                modal.find('.product-name').text(data.product_name);
                modal.find('.product-price').text(data.product_price);
                modal.find('.bid-user-name').text(data.user_name + " (" + data.country_code + ")");
                modal.find('.bid-date-time').text(data.date_time);
                modal.find('.bid-amount').text(data.amount);
                modal.find('input[name=bid_id]').val(data.bid_id);
                modal.modal('show');
            })
 
            $('#bidModal').on('hidden.bs.modal', function () {
                $('#bidModal form')[0].reset();
            });


        })(jQuery);
    </script>
@endpush
