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
                            <th>@lang('Image')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Owner')</th>
                            <th>@lang('Starting Price')</th>
                            <th>@lang('Current Bid')</th>
                            <th>@lang('Time Remaining')</th>
                            @if(request()->routeIs('admin.product.auction.index'))
                            <th>@lang('Status')</th>
                            @endif
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($auctions as $auction)
                        <tr>
                            <td data-label="@lang('S.N')">
                                @if($auction->admin_id)
                                    {{ substr($auction->created_at, 0, 4) }}-0-A-{{ $auction->id }}
                                @else
                                    {{ substr($auction->created_at, 0, 4) }}-{{ $auction->merchant_id }}-A-{{ $auction->id }}
                                @endif
                            </td>
                            <td data-label="@lang('Image')">
                                <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}" target="_blank">
                                    <img style="width: 30px;" src="{{ getImage(imagePath()['product']['path'].'/'.$auction->image) }}">
                                </a>
                            </td>
                            <td data-label="@lang('Name')">{{ __($auction->name) }}</td>
                            <td data-label="@lang('Owner')">
                                @if($auction->admin_id)
                                    <span class="badge badge-dot"><i class="bg--success"></i></span>
                                @endif
                                {{ __($auction->merchant ? $auction->merchant->fullname : $auction->admin->name) }}
                            </td>
                            <td data-label="@lang('Starting Price')">{{ $general->cur_sym }}{{ showAmount($auction->price) }}</td>
                            <td data-label="@lang('Current Bid')">
                                @if($auction->total_bid > 0)
                                    <a href="{{ route('admin.product.auction.bids', $auction->id) }}" class="icon-btn" style="background: transparent; color: #5b6e88; padding: 0; margin: 0;">
                                        {{ $general->cur_sym }}{{ showAmount($auction->price) }}
                                    </a>
                                @endif
                            </td>
                            <td data-label="@lang('Time Remaining')">
                                <ul class="countdown" data-date="{{ showDateTime($auction->expired_at, 'm/d/Y H:i:s') }}" style="display: flex; justify-content: space-between; align-items: center; width: 150px;">
                                    <li>
                                        <span class="days">@lang('00')</span>
                                    </li>
                                    <li>
                                        <span class="hours">@lang('00')</span>
                                    </li>
                                    <li>
                                        <span class="minutes">@lang('00')</span>
                                    </li>
                                    <li>
                                        <span class="seconds">@lang('00')</span>
                                    </li>
                                </ul>
                            </td>
                            @if(request()->routeIs('admin.product.auction.index'))
                            <td data-label="@lang('Status')">
                                @if($auction->status == 0 && $auction->expired_at > now())
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                @elseif($auction->status == 1 && $auction->started_at < now() && $auction->expired_at > now())
                                    <span class="text--small badge font-weight-normal badge--success">@lang('Live')</span>
                                @elseif($auction->status == 1 && $auction->started_at > now())
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('Upcoming')</span>
                                @else
                                    <span class="text--small badge font-weight-normal badge--danger">@lang('Expired')</span>
                                @endif
                            </td>
                            @endif

                            <td data-label="@lang('Action')">
                                @if($auction->status == 0 && $auction->expired_at > now())
                                @elseif($auction->status == 1 && $auction->started_at < now() && $auction->expired_at > now())
                                @elseif($auction->status == 1 && $auction->started_at > now())
                                @else
                                    <a href="{{ route('admin.product.auction.startagain', $auction->id) }}" class="icon-btn btn--info mr-1" data-toggle="tooltip" data-original-title="@lang('Start once again')">
                                        <i class="las la-history text--shadow"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.product.auction.edit', $auction->id) }}" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Edit')">
                                    <i class="las la-pen text--shadow"></i>
                                </a>

                                <button type="button" class="icon-btn btn--success approveBtn" data-toggle="tooltip" data-original-title="@lang('Approve')" data-id="{{ $auction->id }}" {{ ($auction->status == 1 || $auction->expired_at < now()) ? 'disabled':'' }}>
                                    <i class="las la-check text--shadow"></i>
                                </button>
                                
                                <button class="icon-btn btn--danger deleteOneAuction" data-id="{{ $auction->id }}">
                                    <i class="la la-trash"></i>
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
            @if ($auctions->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($auctions) }}
                </div>
            @endif
        </div>
    </div>
</div>


{{-- APPROVE MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Approve Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.product.auction.approve')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this auction') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
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
            <form action="{{route('admin.product.auction.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this auction') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" name="auction_id">
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
    <form action="" method="GET" class="header-search-form">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control bg-white text--black" placeholder="@lang('Auction or Merchant')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    <a class="btn btn--primary box--shadow1 text--small" href="{{ route('admin.product.auction.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
</div>

@endpush

@push('style')
    <style>
        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center
        }
        .header-search-wrapper {
            gap: 15px
        }
        
       
        @media (max-width:400px) {
            .header-search-form {
                width: 100%
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
            
            $(document).on('click', '.deleteOneAuction', function(e) {
                var modal = $('#deleteModal');
                $('input[name="auction_id"]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
