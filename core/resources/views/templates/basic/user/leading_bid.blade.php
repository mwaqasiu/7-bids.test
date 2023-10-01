@extends($activeTemplate.'userlayouts.userapp')

@section('panel')

@php
    $emptynum = 0;
@endphp

<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two" id="sortPricetable">
                <thead>
                    <tr>
                        <th>
                            <!--&nbsp;-->
                            <!--<div>-->
                            <!--    <i class="las la-angle-up" onclick="sortnoup()"></i>-->
                            <!--    <i class="las la-angle-down" onclick="sortnodown()"></i>-->
                            <!--</div>-->
                            @lang('Auction Lot')
                        </th>
                        <th>@lang('Current Bid')</th>
                        <th>@lang('Your Max. Bid')</th>
                        <th class="th-header-price tablethtd">
                            @lang('Time Remaining')
                            <div>
                                <i class="las la-angle-up" onclick="sortremainup()"></i>
                                <i class="las la-angle-down" onclick="sortremaindown()"></i>
                            </div>
                        </th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($leadingbids as $bid)
                    @if($bid->auction->expired_at > now())
                        @php
                             $emptynum = $emptynum + 1;
                        @endphp
                        <tr>
                            <td data-label="@lang('Image')">
                                <a href="{{ route('auction.details', [$bid->auction->id, slug($bid->auction->name)]) }}" target="_blank">
                                    <img style="width: 30px;" src="{{ getImage(imagePath()['product']['path'].'/'.$bid->auction->image) }}">
                                </a>
                                <span style="margin: 0 10px;">
                                    <!--@if($bid->auction->admin_id)-->
                                    <!--    {{ substr($bid->auction->created_at, 0, 4) }}-0-A-{{ $bid->auction->id }}-->
                                    <!--@else-->
                                    <!--    {{ substr($bid->auction->created_at, 0, 4) }}-{{ $bid->auction->merchant_id }}-A-{{ $bid->auction->id }}-->
                                    <!--@endif-->
                                </span>
                                <span>
                                    {{ __($bid->auction->name) }}
                                </span>
                            </td>
                            <td data-label="@lang('Current Bid')">
                                {{ $general->cur_sym }} {{ showAmount($bid->auction->price, 0) }}</td>
                            <td data-label="@lang('Your Max. Bid')">
                                @if($bid->auction->status == 0 && $bid->auction->expired_at > now())
                                    <button type="button" class="add_bid_btn" data-toggle="tooltip" data-original-title="@lang('Increase Bid')"
                                    data-addbid_id="{{ $bid->auction->id }}"
                                    data-auction_lot="@if($bid->auction->admin_id) {{ substr($bid->auction->created_at, 0, 4) }}-0-A-{{ $bid->auction->id }} @else {{ substr($bid->auction->created_at, 0, 4) }}-{{ $bid->auction->merchant_id }}-A-{{ $bid->auction->id }} @endif"
                                    data-product_name="{{ $bid->auction->name }}"
                                    data-current_bid="{{ showAmount($bid->auction->price, 0) }}"
                                    data-current_amount_bid="{{$bid->maxamount}}"
                                    data-my_bid="{{ showAmount($bid->maxamount, 0) }}"
                                    >
                                        <i class="las la-plus text--shadow"></i>
                                    </button>
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at < now() && $bid->auction->expired_at > now())
                                    <button type="button" class="add_bid_btn" data-toggle="tooltip" data-original-title="@lang('Increase Bid')"
                                    data-addbid_id="{{ $bid->auction->id }}"
                                    data-auction_lot="@if($bid->auction->admin_id) {{ substr($bid->auction->created_at, 0, 4) }}-0-A-{{ $bid->auction->id }} @else {{ substr($bid->auction->created_at, 0, 4) }}-{{ $bid->auction->merchant_id }}-A-{{ $bid->auction->id }} @endif"
                                    data-product_name="{{ $bid->auction->name }}"
                                    data-current_bid="{{ showAmount($bid->auction->price, 0) }}"
                                    data-current_amount_bid="{{$bid->maxamount}}"
                                    data-my_bid="{{ showAmount($bid->maxamount, 0) }}"
                                    >
                                        <i class="las la-plus text--shadow"></i>
                                    </button>
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at > now())
                                    <button type="button" class="add_bid_btn" data-toggle="tooltip" data-original-title="@lang('Increase Bid')"
                                    data-addbid_id="{{ $bid->auction->id }}"
                                    data-auction_lot="@if($bid->auction->admin_id) {{ substr($bid->auction->created_at, 0, 4) }}-0-A-{{ $bid->auction->id }} @else {{ substr($bid->auction->created_at, 0, 4) }}-{{ $bid->auction->merchant_id }}-A-{{ $bid->auction->id }} @endif"
                                    data-product_name="{{ $bid->auction->name }}"
                                    data-current_bid="{{ showAmount($bid->auction->price, 0) }}"
                                    data-current_amount_bid="{{$bid->maxamount}}"
                                    data-my_bid="{{ showAmount($bid->maxamount, 0) }}"
                                    >
                                        <i class="las la-plus text--shadow"></i>
                                    </button>
                                @else
                                @endif
                                {{ $general->cur_sym }} {{ showAmount($bid->maxamount, 0) }}
                            </td>
                            <td data-label="@lang('Time Remaining')" class="tablethtd">
                                <ul class="countdown" data-date="{{ showDateTime($bid->auction->expired_at, 'm/d/Y H:i:s') }}">
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
                            <td data-label="@lang('Status')">
                                @php $bidcounts = 0 @endphp
                                @if($bid->auction->status == 0 && $bid->auction->expired_at > now())
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at < now() && $bid->auction->expired_at > now())
                                    @foreach($outtenbids as $outbid)
                                        @if($outbid->auction_id == $bid->auction->id)
                                            @if((float)$outbid->amount >= (float)$bid->maxamount)
                                                @php $bidcounts ++ @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    
                                    @if($bidcounts > 0)
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Outbidden')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Leading Bid')</span>
                                    @endif
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at > now())
                                    @foreach($outtenbids as $outbid)
                                        @if($outbid->auction_id == $bid->auction->id)
                                            @if((float)$outbid->amount >= (float)$bid->maxamount)
                                                @php $bidcounts ++ @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                    
                                    @if($bidcounts > 0)
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Outbidden')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Leading Bid')</span>
                                    @endif
                                @else
                                    <span class="text--small badge font-weight-normal badge--danger">@lang('Ended')</span>
                                @endif
                            </td>
                            <td style="display: none;">
                                {{ $bid->created_at }}
                            </td>
                            <td data-label="@lang('Action')">
                                <button type="button" class="icon-btn bid-details" data-toggle="tooltip" data-original-title="@lang('Details')"
                                    data-bid_id="{{ $bid->id }}"
                                    data-product_name="{{ __($bid->auction->name) }}"
                                    data-product_price="{{ $general->cur_sym }} {{ showAmount($bid->auction->price) }}"
                                    data-user_name="{{ __($bid->user->fullname) }}"
                                    data-date_time="{{ showDateTime($bid->created_at) }}"
                                    data-amount="{{ $general->cur_sym }} {{ showAmount($bid->amount) }}"
                                    >
                                    <i class="las la-desktop text--shadow"></i>
                                </button>
                                @if($bid->auction->status == 0 && $bid->auction->expired_at > now())
                                    <button class="icon-btn btn--danger" style="margin-left: 5px;" disabled>
                                        <i class="las la-trash text--shadow"></i>
                                    </button>
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at < now() && $bid->auction->expired_at > now())
                                    <button class="icon-btn btn--danger" style="margin-left: 5px;" disabled>
                                        <i class="las la-trash text--shadow"></i>
                                    </button>
                                @elseif($bid->auction->status == 1 && $bid->auction->started_at > now())
                                    <button class="icon-btn btn--danger" style="margin-left: 5px;" disabled>
                                        <i class="las la-trash text--shadow"></i>
                                    </button>
                                @else
                                    <button class="icon-btn btn--danger deleteOneBid" style="margin-left: 5px;" data-id="{{ $bid->id }}">
                                        <i class="la la-trash text--shadow"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                @if($emptynum <= 0)
                    <tr>
                        <td class="text-muted text-center table-empty-td" style="padding-right: 0 !important; padding-left: 0 !important;" colspan="7">{{ __($emptyMessage) }}</td>
                    </tr>
                @endif

                </tbody>
            </table><!-- table end -->
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
            <form action="{{route('user.leadingbid.history.deleteone')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this bid') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" name="delbid_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="bidcalcuModal" style="z-index: 100000;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body" style="position: relative;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 1; font-weight: 100; background-color: transparent !important; border: 0px; color: red !important; font-size: 20px;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <table class="bidcalc_table">
                    <tr style="padding: 8px 0;">
                        <td style="width: 35%; text-align: right;">
                            <span>@lang('Your Bid'):</span>
                        </td>
                        <td style="width: 65%; text-align: right; padding: 8px 0 8px 15px;">
                            <select class="bidcalc_select bid_calc_bid_select">
                                <option value="0">@lang('Select')</option>
                                @for($i = 100; $i <= 500; $i = $i + 50)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 600; $i <= 1000; $i = $i + 100)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 1200; $i <= 5000; $i = $i + 200)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 5500; $i <= 10000; $i = $i + 500)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 11000; $i <= 20000; $i = $i + 1000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 22000; $i <= 50000; $i = $i + 2000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 55000; $i <= 100000; $i = $i + 5000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 110000; $i <= 200000; $i = $i + 10000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 220000; $i <= 300000; $i = $i + 20000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 325000; $i <= 500000; $i = $i + 25000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                                @for($i = 550000; $i <= 1000000; $i = $i + 50000)
                                    <option value={{$i}}>{{number_format($i, 0, ",", ".")}} Euro</option>
                                @endfor
                            </select>
                        </td>
                    </tr>
                    <tr style="padding: 8px 0;">
                        <td style="width: 35%; text-align: right;">
                            <span>@lang('Buyers Premium'): <br /> (@lang('incl. VAT'))</span>
                        </td>
                        <td style="width: 65%; text-align: right; padding: 8px 0 8px 15px;">
                            <span class="bidcalc_pre">0.00 Euro</span>
                        </td>
                    </tr>
                    <!--<tr style="padding: 8px 0;">-->
                    <!--    <td style="width: 35%; text-align: right;">-->
                    <!--        <span>@lang('VAT'):</span>-->
                    <!--    </td>-->
                    <!--    <td style="width: 65%; text-align: right; padding: 8px 0 8px 15px;">-->
                    <!--        <span class="bidcalc_vat">0.00 Euro</span>-->
                    <!--    </td>-->
                    <!--</tr>-->
                    <tr style="padding: 8px 0;">
                        <td style="width: 35%; text-align: right;">
                            <span>@lang('Shipping Costs'):</span>
                        </td>
                        <td style="width: 65%; text-align: right; padding: 8px 0 8px 15px;">
                            <select class="bidcalc_select bid_calc_shipping_select">
                                <option value="0">@lang('Select')</option>
                                @foreach($shippings as $shipping)
                                    <option value="{{ $shipping->shipping_amount }}">{{ $shipping->shipping_text }} - {{ $shipping->shipping_amount }} Euro</option>
                                @endforeach
                                <option value="0">collection by the buyer</option>
                            </select>
                        </td>
                    </tr>
                    <tr style="padding: 8px 0;">
                        <td style="width: 35%; text-align: right;">
                            <span>@lang('Total Amount'):</span>
                        </td>
                        <td style="width: 65%; text-align: right; padding: 8px 0 8px 15px;">
                            <span class="bidcalc_total">{{$general->cur_sym}} 0.00</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Bid Modal --}}
<div id="addbidModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Increase your bid')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 1;">
                    <span aria-hidden="true" style="text-shadow: 0 0 black; font-weight: 400;">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.auctionaddbid') }}" method="POST">
                @csrf
                <div class="modal-body">
                   <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center fontcolorblack">
                                @lang('Auction Lot'):
                                <span class="product-name"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fontcolorblack">
                                @lang('Current bid'):
                                <span class="current-bid"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fontcolorblack">
                                @lang('Your max. bid'):
                                <span class="my-bid"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center fontcolorblack">
                                <span class="newbidamountspan">@lang('New bid amount'):</span>
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <div class="fontcolorwhite" style="background-color: #336699; width: 38px; text-align: center; height: 38px; display: flex; border-top-left-radius: 5px; border-bottom-left-radius: 5px; justify-content: center; align-items: center;">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <select class="addbidamount" name="addbidamount" id="addbidamount" style="line-height: 0; height: 38px; border-radius: unset; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-style: solid solid solid none;">
                                        <option value="">@lang('CHOOSE YOUR BID')</option>
                                    </select>    
                                </div>
                            </li>
                        </ul>
                   </div>
                   <input type="hidden" name="addbid_id">
                </div>
                <div class="modal-footer addbidModalFooter">
                    <div id="button-background">
                        <div class="background-col"></div>
                    	<span class="lockslide-text">@lang('SWIPE TO BID')</span>
                    	<div class="perforatedstick"></div>
                    	<div id="lockslider">
                    		<i class="las la-arrow-right" style="background: transparent !important;"></i>
                    	</div>
                    </div>
                    <button type="submit" class="btn btn--primary addbidsubmitbtn" style="display: none;">@lang('OK')</button>
                </div>
            </form>
        </div>
    </div>
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
                                @lang('Seller'):
                                <span class="bid-user-name"></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Bid Time')
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

@push('breadcrumb-plugins')
    <div>
        <select name="search_keys" class="searchSel">
            <option value="30" @if($skey == "30") selected @endif>@lang('Last 30 days')</option>
            <option value="60" @if($skey == "60") selected @endif>@lang('Last 60 days')</option>
            <option value="90" @if($skey == "90") selected @endif>@lang('Last 90 days')</option>
            <option value="120" @if($skey == "120") selected @endif>@lang('Last 120 days')</option>
        </select>
    </div>
@endpush

@push('style')
<style>
    .countdown {
        margin: 0px;
        display: flex;
        justify-content: space-between;
    }
    
    button[disabled=disabled], button:disabled {
        background-color: #ea5455 !important;
    }
    
    .countdown > li {
        margin: 2px;
    }
    
    .th-header-price {
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }
    
    .th-header-id {
        display: flex;
        justify-content: center;
        align-items: flex-end;
    }
    
    .th-header-id:nth-child(1) {
        justify-content: flex-start;
    }
    
    .th-header-price:nth-child(1) {
        justify-content: flex-start;
    }
    
    .th-header-id > div {
        display: inline-grid;
        margin-right: 10px;
    }
    
    .th-header-price > div {
        display: inline-grid;
        margin-left: 10px;
    }
    
    .th-header-id > div > i {
        cursor: pointer;
    }
    
    .th-header-price > div > i {
        cursor: pointer;
    }
    
    .add_bid_btn > i {
        color: red !important;
    }
    
    #button-background {
    	position: relative;
        background-color: transparent;
        width: 218px;
        height: 38px;
        border-style: solid solid solid none;
        border-color: rgba(255, 255, 255, 0.6);
        border-width: 1px;
        border-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 10px;
        margin-right: 1.5rem;
    }
    
	.unlocked {
		transition: all 0.3s;
        width: 218px !important;
        left: 0 !important;
        height: 38px !important;
        border-radius: 5px !important;
        border: none;
        background-color: #20c997 !important;
	}
    
    #lockslider {
        transition: width 0.3s, border-radius 0.3s, height 0.3s;
        position: absolute;
        background-color: #336699;
        width: 38px;
        height: 38px;
        color: #e8d100;
        font-weight: bolder;
        left: 0;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    #locker {
    	width: 14px;
    	height: 14px;
    }
    
    .perforatedstick {
        border: 1px dashed rgba(255, 255, 255, 0.8);
        position: absolute;
        top: 0;
        right: 38px;
        width: 2px;
        height: 100%;
    }
    
    .lockslide-text {
        color: #fff;
        text-transform: uppercase;
        padding-left: 35px;
        padding-right: 38px;
        font-size: 14px;
        background-color: transparent !important;
    }
    
    .background-col {
        width: 0%;
        position: absolute;
        left: 0;
        height: 38px;
        background-color: #20c997;
        border-radius: 5px;
    }
    
    .fontcolorwhite {
        cursor: pointer;
    }
    
    .fontcolorwhite > i {
        background: transparent;
    }
    
    .bidcalc_table {
        padding: 0;
        width: 100%;
        margin: 5rem 0;
    }
    
    .bidcalc_select {
        background-color: #336699;
        width: 100%;
        height: 35px;
        outline: none;
    }
    
    .tablethtd {
        width: 230px;
    }
    
    .bidcalc_select::-webkit-scrollbar {
        width: 1px;
    }
    
    @media (max-width: 991px) {
        .newbidamountspan {
            display: none !important;
        }
        
        .addbidamount {
            width: 180px !important;
        }
        
        .table-empty-td {
            display: flex !important;
            justify-content: center !important;
            border-bottom-color: transparent !important;
        }
        
        .addbidModalFooter {
            justify-content: flex-start !important;
            margin-left: 1.25rem !important;
            border-top-color: transparent !important;
            margin-top: -1.5rem;
        }
        
        .bidcalc_table > tbody > tr > td:first-child {
            padding: 15px 0 !important;
        }
        
        .bidcalc_table > tbody > tr > td > select {
            height: 50px !important;
        }
        
        .tablethtd {
            width: unset !important;
        }
    }
    
    .high_img_modal_close > span {
        font-size: 20px;
        text-shadow: 0 0 BLACK;
        border: 1px solid red;
        padding: 0px 4px;
        font-weight: 100;
        color: red !important;
        background: transparent !important;
    }
</style>
@endpush

@push('script')
<script>
    // swipe bid function
    var initialMouse = 0;
    var slideMovementTotal = 0;
    var mouseIsDown = false;
    var lockslider = $('#lockslider');
    
    lockslider.on('mousedown touchstart', function(event){
    	mouseIsDown = true;
    	slideMovementTotal = $('#button-background').width() - $(this).width() + 0;
    	initialMouse = event.clientX || event.originalEvent.touches[0].pageX;
    });
    
    $(document.body, '#lockslider').on('mouseup touchend', function (event) {
        var amount = $('#addbidamount').val();
        
        // if(!amount) {
        //     $('.empty-message').show();
        // }
        // else {
            if (!mouseIsDown)
        		return;
        	mouseIsDown = false;
        	var currentMouse = event.clientX || event.changedTouches[0].pageX;
        	var relativeMouse = currentMouse - initialMouse;
        
        	if (relativeMouse < slideMovementTotal) {
        		$('.lockslide-text').fadeTo(300, 1);
        		$('.background-col').css('width', "0%");
        		lockslider.animate({
        			left: "0px"
        		}, 300);
        		return;
        	} else {
        	    if(!amount) {
        	        iziToast['warning']({
                        message: "You must choose your bid before swiping.",
                        position: "topRight"
                    });
        	        $('.lockslide-text').fadeTo(300, 1);
            		$('.background-col').css('width', "0%");
            		lockslider.animate({
            			left: "0px"
            		}, 300);
            		return;
        	    }
        	}
        	
        	if(amount) {
        	    lockslider.addClass('unlocked');
        	}
        	
        	if(amount == "" || amount == null || amount == 0) {
                $('.addbidamount').css("border-color", "red");
            }
            else {
                $('.addbidsubmitbtn').click();
            }
        // }
    });
    
    $(document.body).on('mousemove touchmove', function(event){
        var amount = $('#addbidamount').val();
        
        // if(amount) {
            if (!mouseIsDown)
        		return;
        	
        	var currentMouse = event.clientX || event.originalEvent.touches[0].pageX;
        	var relativeMouse = currentMouse - initialMouse;
        	var slidePercent = 1 - (relativeMouse / slideMovementTotal);
        	
        	if(amount) {
            	if((relativeMouse / slideMovementTotal) * 100 < 100)
            	{
            	    $('.background-col').css('width', (relativeMouse / slideMovementTotal) * 100 + "%");  
            	}
            	else {
            	    $('.background-col').css('width', "100%"); 
            	}
        	}
        	
        	$('.lockslide-text').fadeTo(0, slidePercent);
        
        	if (relativeMouse <= 0) {
        		lockslider.css({'left': '0px'});
        		return;
        	}
        	if (relativeMouse >= slideMovementTotal + 0) {
        		lockslider.css({'left': slideMovementTotal + 'px'});
        		return;
        	}
        	
        	if(!amount) {
        	    if (relativeMouse >= slideMovementTotal - 38) {
            		lockslider.css({'left': (slideMovementTotal - 38) + 'px'});
            		return;
            	}
        	}
        	
        	lockslider.css({'left': relativeMouse - 0});
        // }
    });
    // end swipe bid
    
    function addbidamountfunc() {
        $('.addbidamount').css('border-color', '#fff');
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
    
    (function ($) {
        "use strict";
        $('.bid-details').click(function(){
            var modal = $('#bidModal');
            var data = $(this).data();
            modal.find('.product-name').text(data.product_name);
            modal.find('.bid-user-name').text(data.user_name);
            modal.find('.bid-date-time').text(data.date_time);
            modal.find('input[name=bid_id]').val(data.bid_id);
            modal.modal('show');
        });
        
        $('.add_bid_btn').click(function(){
            var modal = $("#addbidModal");
            var data = $(this).data();
            $('input[name="addbid_id"]').val(data.addbid_id);
            modal.find('.product-name').text(data.product_name);
            modal.find('.current-bid').text("{{ $general->cur_sym }} " + data.current_bid);
            modal.find('.my-bid').text("{{ $general->cur_sym }} " + data.my_bid);
            
            var chooseamountstyleid = document.getElementById('addbidamount');
            while (chooseamountstyleid.length > 0) {
                chooseamountstyleid.remove(0);
            }
            
            $('.addbidamount').append('<option value="">@lang('CHOOSE YOUR BID')</option>');
            
            for(var i = 100; i <= 500; i = i + 50) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + ' Euro</option>');
                }
            }
            for(var i = 600; i < 1000; i = i + 100) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            if(data.current_amount_bid < 1000) {
                $('.addbidamount').append('<option value=1000>' + new Intl.NumberFormat("de-DE").format(1000) + " Euro</option>");
            }
            for(var i = 1200; i <= 5000; i = i + 200) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 5500; i < 10000; i = i + 500) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            if(data.current_amount_bid < 10000) {
                $('.addbidamount').append('<option value=10000>' + new Intl.NumberFormat("de-DE").format(10000) + " Euro</option>");
            }
            for(var i = 11000; i <= 20000; i = i + 1000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 22000; i <= 50000; i = i + 2000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 55000; i < 100000; i = i + 5000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            if(data.current_amount_bid < 100000) {
                $('.addbidamount').append('<option value=100000>' + new Intl.NumberFormat("de-DE").format(100000) + " Euro</option>");
            }
            for(var i = 110000; i <= 200000; i = i + 10000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 220000; i <= 300000; i = i + 20000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 325000; i <= 500000; i = i + 25000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            for(var i = 550000; i <= 1000000; i = i + 50000) {
                if(data.current_amount_bid < i) {
                    $('.addbidamount').append('<option value=' + i + '>' + new Intl.NumberFormat("de-DE").format(i) + " Euro</option>");
                }
            }
            
            modal.modal('show');
            
        });
        
        $('.addbidokbtn').click(function() {
            if($('#addbidamount').val() == "" || $('#addbidamount').val() == null || $('#addbidamount').val() == 0) {
                $('.addbidamount').css("border-color", "red");
            }
            else {
                $('.addbidsubmitbtn').click();
            }
        });
        
        $(".searchSel").on("change", function() {
            if($(this).val() == "120") {
                window.location.href = "{{ route('user.leadingbid.history') }}";
            } else {
                window.location.href = "{{route('user.leadingbid.history')}}/search/"+$(this).val();
            }
        });
        
        $('.fontcolorwhite').on('click', function() {
            var modal = $('#bidcalcuModal');
            modal.modal('show');
        });
        
        $('.bid_calc_bid_select').on('change', function() {
            var totalamount = Number($(this).val()) + Number(Number($(this).val()) * 0.2) + Number(Number($(this).val()) * 0.2 * 0.19) + Number($('.bid_calc_shipping_select').val());
            $('.bidcalc_pre').html("{{ $general->cur_sym }} " + Number(Number(Number($(this).val()) * 0.2) + Number(Number($(this).val()) * 0.2 * 0.19) + Number($('.bid_calc_shipping_select').val())).toFixed(2));
            // $('.bidcalc_vat').html(Number(Number($(this).val()) * 0.2 * 0.19).toFixed(2) + " Euro");
            $('.bidcalc_total').html("{{ $general->cur_sym }} " + totalamount.toFixed(2));
        });
        
        $('.bid_calc_shipping_select').on('change', function() {
            var totalamount = Number($('.bid_calc_bid_select').val()) + Number(Number($('.bid_calc_bid_select').val()) * 0.2) + Number(Number($('.bid_calc_bid_select').val()) * 0.2 * 0.19) + Number($(this).val());
            $('.bidcalc_total').html("{{ $general->cur_sym }} " + totalamount.toFixed(2));
        });
        
        $(document).on('click', '.deleteOneBid', function(e) {
            var modal = $('#deleteModal');
            $('input[name="delbid_id"]').val($(this).data('id'));
            modal.modal('show');
        });

        $('#bidModal').on('hidden.bs.modal', function () {
            $('#bidModal form')[0].reset();
        });


    })(jQuery);
</script>
@endpush
