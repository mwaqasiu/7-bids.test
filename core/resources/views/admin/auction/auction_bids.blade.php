@extends('admin.layouts.app')
@section('panel')
<div class="card b-radius--10 ">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                <tr>
                    <th>@lang('S.N.')</th>
                    <th>@lang('User Name')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Bid Time')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bids as $bid)
                <tr>
                    <td data-label="@lang('S.N')">{{ $bids->firstItem() + $loop->index }}</td>
                    <td data-label="@lang('User Name')">{{ __($bid->user->fullname) }} <br>
                        @php $bidId = $bid->auctionwinner ? $bid->auctionwinner->auctionbid_id : ''; @endphp
                        @if ($bid->id == $bidId)
                            <span class="badge badge--success">@lang('Winner')</span>
                        @endif
                    </td>
                    <td data-label="@lang('Amount')">{{ $general->cur_sym }}{{ showAmount($bid->amount) }}</td>
                    <td data-label="@lang('Bid Time')">{{ showDateTime($bid->created_at) }} <br> {{ diffForHumans($bid->created_at) }}</td>
                    <td data-label="@lang('Action')">
                        <button type="button" class="icon-btn bid-details" data-toggle="tooltip" data-original-title="@lang('Details')"
                            data-bid_id="{{ $bid->id }}"
                            data-auction_name="{{ __($bid->auction->name) }}"
                            data-auction_price="{{ $general->cur_sym }}{{ showAmount($bid->auction->price) }}"
                            data-user_name="{{ __($bid->user->fullname) }}"
                            data-date_time="{{ showDateTime($bid->created_at) }}"
                            data-amount="{{ $general->cur_sym }}{{ showAmount($bid->amount) }}"
                            >
                            <i class="las la-thumbs-up text--shadow"></i>
                        </button>
                        <a href="{{ route('admin.product.auction.decline', $bid->id) }}" class="icon-btn btn--danger" data-toggle="tooltip" data-original-title="@lang('Decline Winner')">
                            <i class="las la-thumbs-down text--shadow"></i>
                        </a>
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

{{-- BId modal --}}
<div id="bidModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Bid Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.auction.bid.winner') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Product Name'):
                            <span class="auction-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Product Price'):
                            <span class="auction-price"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Bid User Name'):
                            <span class="bid-user-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Bid Amount')
                            <span class="bid-amount"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Bid Time')
                            <span class="bid-date-time"></span>
                        </li>
                    </ul>
                   <input type="hidden" name="bid_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block makingwinnerbtn">@lang('Make Winner')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.product.auction.index') }}" class="btn btn-sm btn--primary box--shadow1 text--small"><i class="la la-fw la-backward"></i> @lang('Go Back') </a>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.bid-details').click(function(){
                var modal = $('#bidModal');
                var data = $(this).data();
                modal.find('.auction-name').text(data.auction_name);
                modal.find('.auction-price').text(data.auction_price);
                modal.find('.bid-user-name').text(data.user_name);
                modal.find('.bid-date-time').text(data.date_time);
                modal.find('.bid-amount').text(data.amount);
                modal.find('input[name=bid_id]').val(data.bid_id);
                $('.makingwinnerbtn').click();
            })

            $('#bidModal').on('hidden.bs.modal', function () {
                $('#bidModal form')[0].reset();
            });


        })(jQuery);
    </script>
@endpush
