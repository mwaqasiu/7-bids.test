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
                                    @lang('Asked Price')
                                    <div>
                                        <i class="las la-angle-up" onclick="sortpriceup()"></i>
                                        <i class="las la-angle-down" onclick="sortpricedown()"></i>
                                    </div>
                                </th>
                                @if($type != "expired")
                                    <th>@lang('Highest Price Offer')</th>
                                @endif
                                @if(request()->routeIs('merchant.product.index'))
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
                            @if(count($products) == 0 && count($auctions) == 0)
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @else
                                @foreach($products as $product)
                                <tr>
                                    <td data-label="@lang('S.N.')">{{ substr($product->created_at, 0, 4) }}-{{ $product->merchant_id }}-M-{{ $product->id }}</td>
                                    <td data-label="@lang('Image')">
                                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}" target="_blank">
                                            <img style="width: 30px;" src="{{ getImage(imagePath()['product']['path'].'/'.$product->image) }}">
                                        </a>
                                    </td>
                                    <td data-label="@lang('Item')">{{ __($product->name) }}</td>
                                    <td data-label="@lang('Asked Price')">{{ $general->cur_sym }}{{ showAmount($product->price) }}</td>
                                    @if($type != "expired")
                                        <td data-label="@lang('Highest Price Offer')">
                                            @if((float)showAmount($product->bestoffer) <= 0)
                                                No offer yet
                                            @else
                                                <a href="{{ route('merchant.bids') }}" class="ml-1">
                                                    {{ $general->cur_sym }}{{ showAmount($product->bestoffer) }}
                                                </a>
                                            @endif
                                        </td>
                                    @endif
                                    @if(request()->routeIs('merchant.product.index'))
                                        <td data-label="@lang('Remaining Time')">
                                            @if($product->status == 0 && $product->expired_at > now())
                                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
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
                                            @elseif($product->status == 1 && $product->started_at < now() && $product->expired_at > now())
                                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
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
                                            @elseif($product->status == 1 && $product->started_at > now())
                                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
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
                                        @if($product->status == 0 && $product->expired_at > now())
                                        @elseif($product->status == 1 && $product->started_at < now() && $product->expired_at > now())
                                        @elseif($product->status == 1 && $product->started_at > now())
                                        @else
                                            <a href="{{ route('merchant.product.startagain', $product->id) }}" class="icon-btn btn--info mr-1" data-toggle="tooltip" data-original-title="@lang('Start once again')">
                                                <i class="las la-history text--shadow"></i>
                                            </a>
                                        @endif
                                        @if((float)showAmount($product->bestoffer) <= 0)
                                            <a href="{{ route('merchant.product.edit', $product->id) }}" class="icon-btn mr-1" data-toggle="tooltip" data-original-title="@lang('Edit')">
                                                <i class="las la-pen text--shadow"></i>
                                            </a>
                                        @else
                                            <button class="icon-btn mr-1 disabled_edit" data-toggle="tooltip" data-original-title="@lang('Edit')" disabled>
                                                <i class="las la-pen text--shadow"></i>
                                            </button>
                                        @endif
                                        @if($product->status == 0 && $product->expired_at > now())
                                        @elseif($product->status == 1 && $product->started_at < now() && $product->expired_at > now())
                                        @elseif($product->status == 1 && $product->started_at > now())
                                        @else
                                            <!--<button type="button" class="icon-btn btn--danger deleteOneProduct" data-id="{{ $product->id }}" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">-->
                                            <!--    <i class="las la-trash text--shadow"></i>-->
                                            <!--</button>-->
                                            <!--<button type="button" class="icon-btn btn--danger deleteOneProduct1" style="display: none;" data-id="{{ $product->id }}" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">-->
                                            <!--    <i class="las la-trash text--shadow"></i>-->
                                            <!--</button>-->
                                        @endif
                                        @if((float)showAmount($product->bestoffer) <= 0)
                                            <button class="icon-btn btn--danger deleteOneProduct" data-id="{{ $product->id }}">
                                                <i class="la la-trash"></i>
                                            </button>
                                        @else
                                            <button class="icon-btn btn--danger deleteOneProduct" data-id="{{ $product->id }}" disabled>
                                                <i class="la la-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                @foreach($auctions as $auction)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ substr($auction->created_at, 0, 4) }}-{{ $auction->merchant_id }}-A-{{ $auction->id }}</td>
                                    <td data-label="@lang('Image')">
                                        <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}" target="_blank">
                                            <img style="width: 30px;" src="{{ getImage(imagePath()['product']['path'].'/'.$auction->image) }}">
                                        </a>
                                    </td>
                                    <td data-label="@lang('Name')">{{ __($auction->name) }}</td>
                                    <td data-label="@lang('Asked Price')">{{ $general->cur_sym }}{{ showAmount($auction->price) }}</td>
                                    @if($type != "expired")
                                        <td data-label="@lang('Total Bid')">
                                            <a href="{{ route('merchant.product.auction.bids', $auction->id) }}" class="icon-btn btn--info ml-1">
                                                {{ $auction->total_bid }}
                                            </a>
                                        </td>
                                    @endif
                                    @if(request()->routeIs('merchant.product.index'))
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
                                        @if($auction->status == 0 && $auction->expired_at > now())
                                        @elseif($auction->status == 1 && $auction->started_at < now() && $auction->expired_at > now())
                                        @elseif($auction->status == 1 && $auction->started_at > now())
                                        @else
                                            <button type="button" class="icon-btn btn--danger deleteOneAuction" data-id="{{ $auction->id }}" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">
                                                <i class="las la-trash text--shadow"></i>
                                            </button>
                                            <button type="button" class="icon-btn btn--danger deleteOneAuction1" style="display: none;" data-id="{{ $auction->id }}" data-toggle="tooltip" title="" data-original-title="@lang('Delete')">
                                                <i class="las la-trash text--shadow"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($products->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($products) }}
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
            <form action="{{route('merchant.product.approve')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this product') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- APPROVE AUCTION MODAL --}}
<div id="approveAuctionModal" class="modal fade" tabindex="-1" role="dialog">
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
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('this product') <span class="font-weight-bold withdraw-user"></span>?</p>
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
            <form action="{{route('merchant.product.delete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" name="product_id">
                <div class="modal-footer">
                    <div style="flex: 1;">
                        <input type="checkbox" onclick="auction_del_change_func(this);" id="notshowmodaldel_sell" name="notshowmodaldel_sell" />
                        <span>Don't show this message again</span>
                    </div>
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary product_del_btn_con">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DELETE AUCTION MODAL --}}
<div id="deleteAuctionModal" class="modal fade" tabindex="-1" role="dialog">
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
                    <div style="flex: 1;">
                        <input type="checkbox" onclick="auction_del_change_func(this);" id="notshowmodaldel_sell" name="notshowmodaldel_sell" />
                        <span>Don't show this message again</span>
                    </div>
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary auction_del_btn_con">@lang('Yes')</button>
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
            <input type="text" name="search" style="background: #fff;" class="form-control" placeholder="@lang('Search Item')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    @if($type != "expired")
        <a class="btn btn-sm btn--primary text--small" href="{{ route('merchant.product.create') }}"><i class="fa fa-fw fa-plus"></i>@lang('Add New')</a>
    @endif
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
        
        button:disabled {
            background-color: #ea5455 !important;
        }
        
        .disabled_edit:disabled {
            background-color: #9892d7 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        if(localStorage.getItem("delmodalsell") != null && localStorage.getItem("delmodalsell") != "null") {
            document.getElementById("notshowmodaldel_sell").checked = true;
            $('.deleteOneAuction').hide();
            $('.deleteOneAuction1').show();
            $('.deleteOneProduct').hide();
            $('.deleteOneProduct1').show();
        } else {
            document.getElementById("notshowmodaldel_sell").checked = false;
            $('.deleteOneAuction').show();
            $('.deleteOneAuction1').hide();
            $('.deleteOneProduct').show();
            $('.deleteOneProduct1').hide();
        }
    
        function auction_del_change_func(cb) {
            if(cb.checked) {
                localStorage.setItem("delmodalsell", "mode");
            }
            else {
                localStorage.setItem("delmodalsell", null);
            }
        }
        
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
            
            $('.approveAuctionBtn').on('click', function () {
                var modal = $('#approveAuctionModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
            
            $(document).on('click', '.deleteOneProduct', function(e) {
                var modal = $('#deleteModal');
                $('input[name="product_id"]').val($(this).data('id'));
                modal.modal('show');
            });
            
            $(document).on('click', '.deleteOneAuction', function(e) {
                var modal = $('#deleteAuctionModal');
                $('input[name="auction_id"]').val($(this).data('id'));
                modal.modal('show');
            });
            
            $(document).on('click', '.deleteOneAuction1', async function(e) {
                await $('input[name="auction_id"]').val($(this).data('id'));
                await $('.auction_del_btn_con').click();
            });
            
            $(document).on('click', '.deleteOneProduct1', async function(e) {
                await $('input[name="product_id"]').val($(this).data('id'));
                await $('.product_del_btn_con').click();
            });
        })(jQuery);
    </script>
@endpush