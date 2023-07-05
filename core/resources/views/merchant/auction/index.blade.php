@extends('merchant.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--md  table-responsive">
                    <table class="table table--light style--two" id="sortPricetable">
                        <thead>
                        <tr>
                            <th class="th-header-price">
                                @lang('S.N.')
                                <div>
                                    <i class="las la-angle-up" onclick="sortnoup()"></i>
                                    <i class="las la-angle-down" onclick="sortnodown()"></i>
                                </div>
                            </th>
                            <th>@lang('Image')</th>
                            <th>@lang('Item')</th>
                            <th class="th-header-price">
                                @lang('Highest Bid')
                                <div>
                                    <i class="las la-angle-up" onclick="sortpriceup()"></i>
                                    <i class="las la-angle-down" onclick="sortpricedown()"></i>
                                </div>
                            </th>
                            <th>@lang('Bid(s)')</th>
                            @if(request()->routeIs('merchant.product.auction.index'))
                                <th class="th-header-price">
                                    @lang('Remaining Time')
                                    <div>
                                        <i class="las la-angle-up" onclick="sortremainup()"></i>
                                        <i class="las la-angle-down" onclick="sortremaindown()"></i>
                                    </div>
                                </th>
                            @endif
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($auctions as $auction)
                        <tr>
                            <td data-label="@lang('S.N.')">{{ substr($auction->created_at, 0, 4) }}-{{ $auction->merchant_id }}-A-{{ $auction->id }}</td>
                            <td data-label="@lang('Image')">
                                <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}" target="_blank">
                                    <img style="width: 30px;" src="{{ getImage(imagePath()['product']['path'].'/'.$auction->image) }}">
                                </a>
                            </td>
                            <td data-label="@lang('Item')">{{ __($auction->name) }}</td>
                            <td data-label="@lang('Highest Bid')">{{ $general->cur_sym }}{{ showAmount($auction->price) }}</td>
                            <td data-label="@lang('Bid(s)')">{{ $auction->total_bid }}</td>

                            @if(request()->routeIs('merchant.product.auction.index'))
                            <td data-label="@lang('Remaining Time')">
                                @if($auction->status == 0 && $auction->expired_at > now())
                                    <ul class="countdown" data-date="{{ showDateTime($auction->expired_at, 'm/d/Y H:i:s') }}">
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
                                @elseif($auction->status == 1 && $auction->started_at < now() && $auction->expired_at > now())
                                    <ul class="countdown" data-date="{{ showDateTime($auction->expired_at, 'm/d/Y H:i:s') }}">
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
                                @elseif($auction->status == 1 && $auction->started_at > now())
                                    <ul class="countdown" data-date="{{ showDateTime($auction->expired_at, 'm/d/Y H:i:s') }}">
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
                                    <a href="{{ route('merchant.product.auction.startagain', $auction->id) }}" class="icon-btn btn--info mr-1" data-toggle="tooltip" data-original-title="@lang('Start once again')">
                                        <i class="las la-history text--shadow"></i>
                                    </a>
                                @endif
                                <a href="{{ route('merchant.product.auction.edit', $auction->id) }}" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Edit')">
                                    <i class="las la-pen text--shadow"></i>
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
            <form action="{{route('merchant.product.auction.approve')}}" method="POST">
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
            <form action="{{route('merchant.product.auction.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
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
    <a class="btn btn--primary text--small" href="{{ route('merchant.product.auction.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
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
        
        .countdown {
            margin: -5px;
            display: flex;
            justify-content: space-between;
        }
        
        .countdown > li {
            margin: 2px;
        }
        
        .th-header-price {
            display: flex;
            justify-content: center;
            align-items: flex-end;
        }
        
        .th-header-price:nth-child(1) {
            justify-content: flex-start;
        }
        
        .th-header-price > div {
            display: inline-grid;
            margin-left: 10px;
        }
        
        .th-header-price > div > i {
            cursor: pointer;
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
        function sortremainup() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[5];
                    b = rows[c + 1].getElementsByTagName("TD")[5];
                    if(a.innerHTML > b.innerHTML) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
        function sortremaindown() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[5];
                    b = rows[c + 1].getElementsByTagName("TD")[5];
                    if(a.innerHTML < b.innerHTML) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
    
        function sortnoup() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[0];
                    b = rows[c + 1].getElementsByTagName("TD")[0];
                    if(a.innerHTML > b.innerHTML) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
        function sortnodown() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[0];
                    b = rows[c + 1].getElementsByTagName("TD")[0];
                    if(a.innerHTML < b.innerHTML) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
    
        function sortpriceup() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[3];
                    b = rows[c + 1].getElementsByTagName("TD")[3];
                    if(Number(a.innerHTML.substr(1)) > Number(b.innerHTML.substr(1))) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
        function sortpricedown() {
            var tables, rows, sorting, c, a, b, tblsort;
            tables = document.getElementById("sortPricetable");
            sorting = true;
            while(sorting) {
                sorting = false;
                rows = tables.rows;
                for(c = 1; c < (rows.length - 1); c ++) {
                    tblsort = false;
                    a = rows[c].getElementsByTagName("TD")[3];
                    b = rows[c + 1].getElementsByTagName("TD")[3];
                    if(Number(a.innerHTML.substr(1)) < Number(b.innerHTML.substr(1))) {
                        tblsort = true;
                        break;
                    }
                }
                if(tblsort) {
                    rows[c].parentNode.insertBefore(rows[c + 1], rows[c]);
                    sorting = true;
                }
            }
        }
        
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
