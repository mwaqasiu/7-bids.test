@extends($activeTemplate.'userlayouts.userapp')

@section('panel')

@php
    $paidimagenum = 0;
    $pickedimagenum = 0;
    $packedimagenum = 0;
    $auctionpaidimagenum = 0;
    $auctionpickedimagenum = 0;
    $auctionpackedimagenum = 0;
    $firstpaidimageurl = "";
    $firstpickedimageurl = "";
    $firstpackedimageurl = "";
    $auctionfirstpaidimageurl = "";
    $auctionfirstpickedimageurl = "";
    $auctionfirstpackedimageurl = "";
@endphp

<div class="card b-radius--10 mt-4">
    <div class="card-body p-0">
        <div class="table-responsive--md  table-responsive">
            <table class="table table--light style--two">
                <thead>
                <tr>
                    <th>@lang('Auction Lot')</th>
                    <th>@lang('Winning Bid')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Purchase Date')</th>
                    <th>@lang('Further Actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($winningbids as $bid)
                    <tr>
                        <td data-label="@lang('Auction Lot')" class="tdfordesktop">{{ __($bid->auction->name) }}</td>
                        <td data-label="{{ __($bid->auction->name) }}" class="tdformobile">&nbsp;</td>
                        <td data-label="@lang('Winning Bid')">
                            {{ $general->cur_sym }} {{ showAmount($bid->auction->price, 0) }}
                        </td>
                        <td data-label="@lang('Status')">
                            @if($bid->product_delivered == -1)
                                <div class="initial-status-main">
                                    @if($bid->combine_shipping_flag == 0)
                                        @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                            @if($bid->checkout == null)
                                                <span title="@lang('Shipping')" class="{{ $bid->checkout_id == 0 ? 'fastshippingstatus' : 'fastshippingstatus active' }}" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </span>
                                            @else
                                                <span title="@lang('Shipping')" class="{{ $bid->checkout_id == 0 ? 'fastshippingstatus' : 'fastshippingstatus active' }}" data-paymentname="{{ $bid->checkout->paymentname }}" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Shipping')" class="unactive" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                            <i class="fas fa-shipping-fast"></i>
                                        </span>
                                        @endif
                                    @else
                                        <span title="@lang('Shipping')" class="unactive" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                            <i class="fas fa-shipping-fast"></i>
                                        </span>
                                    @endif
                                    @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                        @if($bid->combine_shipping_flag == 0)
                                            @if($bid->checkout == null)
                                                <span title="@lang('Pick-up')" class="fastpickupdatestatus" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-walking"></i>
                                                </span>
                                            @else
                                                <span title="@lang('Pick-up')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-walking"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Pick-up')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                <i class="fas fa-walking"></i>
                                            </span>
                                        @endif
                                    @else
                                        <span title="@lang('Pick-up')" class="fastpickupdatestatus active" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                            <i class="fas fa-walking"></i>
                                        </span>
                                    @endif
                                    @if($bid->combine_shipping_flag == 0)
                                        @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                            @if($bid->checkout == null)
                                                <span title="@lang('Combined Shipping')" class="fastcombinedshippingstatus" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="las la-hourglass-half"></i>
                                                </span>
                                            @else
                                                <span title="@lang('Combined Shipping')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="las la-hourglass-half"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Combined Shipping')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                <i class="las la-hourglass-half"></i>
                                            </span>
                                        @endif
                                    @else
                                        <span title="@lang('Combined Shipping')" class="fastcombinedshippingstatus active" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                            <i class="las la-hourglass-half"></i>
                                        </span>
                                    @endif
                                </div>
                            @elseif($bid->product_delivered == 0)
                                @if($bid->pending_imageurl != "" || $bid->paid_imageurl != "" || $bid->picked_imageurl != "" || $bid->packed_imageurl != "")
                                    <span class="text--small badge font-weight-normal badge--danger" title="@lang('Pending')">@lang('Waiting for Payment')</span>
                                @else
                                    <div class="initial-status-main">
                                        @if($bid->combine_shipping_flag == 0)
                                            @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                                @if($bid->checkout == null)
                                                    <span title="@lang('Shipping')" class="{{ $bid->checkout_id == 0 ? 'fastshippingstatus' : 'fastshippingstatus active' }}" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="fas fa-shipping-fast"></i>
                                                    </span>
                                                @else
                                                    <span title="@lang('Shipping')" class="{{ $bid->checkout_id == 0 ? 'fastshippingstatus' : 'fastshippingstatus active' }}" data-paymentname="{{ $bid->checkout->paymentname }}" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="fas fa-shipping-fast"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span title="@lang('Shipping')" class="unactive" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-shipping-fast"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Shipping')" class="unactive" data-paymentname="" data-checkoutid="{{ $bid->checkout_id }}" data-winnerid="{{ $bid->id }}">
                                                <i class="fas fa-shipping-fast"></i>
                                            </span>
                                        @endif
                                        @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                            @if($bid->combine_shipping_flag == 0)
                                                @if($bid->checkout == null)
                                                    <span title="@lang('Pick-up')" class="fastpickupdatestatus" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="fas fa-walking"></i>
                                                    </span>
                                                @else
                                                    <span title="@lang('Pick-up')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="fas fa-walking"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span title="@lang('Pick-up')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="fas fa-walking"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Pick-up')" class="fastpickupdatestatus active" data-pickupdate="{{ $bid->pickup_date }}" data-winnerid="{{ $bid->id }}">
                                                <i class="fas fa-walking"></i>
                                            </span>
                                        @endif
                                        @if($bid->combine_shipping_flag == 0)
                                            @if($bid->pickup_date == Null || $bid->pickup_date == "")
                                                @if($bid->checkout == null)
                                                    <span title="@lang('Combined Shipping')" class="fastcombinedshippingstatus" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="las la-hourglass-half"></i>
                                                    </span>
                                                @else
                                                    <span title="@lang('Combined Shipping')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                        <i class="las la-hourglass-half"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span title="@lang('Combined Shipping')" class="unactive" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                    <i class="las la-hourglass-half"></i>
                                                </span>
                                            @endif
                                        @else
                                            <span title="@lang('Combined Shipping')" class="fastcombinedshippingstatus active" data-pickupdate="{{ $bid->pickup_date }}" data-shippingflag="{{ $bid->combine_shipping_flag }}" data-winnerid="{{ $bid->id }}">
                                                <i class="las la-hourglass-half"></i>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            @elseif($bid->product_delivered == 1)
                                <div class="status-main">
                                    <div class="paidstatus" title="@lang('View Invoice')" data-bidid="{{ $bid->id }}">@lang('Paid')</div>
                                    <div class="status-stick"></div>
                                    <div class="pickedstatusinactive" title="@lang('View Progress')" data-bidid="{{ $bid->id }}">@lang('Picked')</div>
                                    <div class="status-stick"></div>
                                    <div class="packedstatusinactive" title="@lang('Packing Report')" data-bidid="{{ $bid->id }}">@lang('Packed')</div>
                                    <div class="status-stick"></div>
                                    <div class="transitstatusinactive" title="@lang('Track & Trace')">@lang('Shipped')</div>
                                </div>
                            @elseif($bid->product_delivered == 2)
                                <div class="status-main">
                                    <div class="paidstatus" title="@lang('View Invoice')" data-bidid="{{ $bid->id }}">@lang('Paid')</div>
                                    <div class="status-stick"></div>
                                    <div class="pickedstatus" title="@lang('View Progress')" data-bidid="{{ $bid->id }}">@lang('Picked')</div>
                                    <div class="status-stick"></div>
                                    <div class="packedstatusinactive" title="@lang('Packing Report')" data-bidid="{{ $bid->id }}">@lang('Packed')</div>
                                    <div class="status-stick"></div>
                                    <div class="transitstatusinactive" title="@lang('Track & Trace')">@lang('Shipped')</div>
                                </div>
                            @elseif($bid->product_delivered == 3)
                                <div class="status-main">
                                    <div class="paidstatus" title="@lang('View Invoice')" data-bidid="{{ $bid->id }}">@lang('Paid')</div>
                                    <div class="status-stick"></div>
                                    <div class="pickedstatus" title="@lang('View Progress')" data-bidid="{{ $bid->id }}">@lang('Picked')</div>
                                    <div class="status-stick"></div>
                                    <div class="packedstatus" title="@lang('Packing Report')" data-bidid="{{ $bid->id }}">@lang('Packed')</div>
                                    <div class="status-stick"></div>
                                    <div class="transitstatusinactive" title="@lang('Track & Trace')">@lang('Shipped')</div>
                                </div>
                            @elseif($bid->product_delivered == 4)
                                <div class="status-main">
                                    <div class="paidstatus" title="@lang('View Invoice')" data-bidid="{{ $bid->id }}">@lang('Paid')</div>
                                    <div class="status-stick"></div>
                                    <div class="pickedstatus" title="@lang('View Progress')" data-bidid="{{ $bid->id }}">@lang('Picked')</div>
                                    <div class="status-stick"></div>
                                    <div class="packedstatus" title="@lang('Packing Report')" data-bidid="{{ $bid->id }}">@lang('Packed')</div>
                                    <div class="status-stick"></div>
                                    @if($url == "")
                                        <div class="transitstatus" title="@lang('Track & Trace')">@lang('Shipped')</div>
                                    @else
                                        <div class="transitstatus" title="@lang('Track & Trace')"><a href="{{ $url }}" class="getformatag" target="_blank">@lang('Shipped')</a></span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td data-label="@lang('Purchase Date')">
                            {{ showDateTime($bid->created_at) }} <br>
                            <span class="subpurchasedatespan">{{ diffForHumans($bid->created_at) }}</span>
                        </td>
                        <td data-label="@lang('Further Actions')" style="display: flex; justify-content: flex-end;">
                            @if($bid->product_delivered == 0)
                                @if(trim($bid->pending_imageurl) == "")
                                    <button style="width: 28px; margin-right: 5px;" data-existimage="" data-bidid="{{ $bid->id }}" class="icon-btn generalsysviewbtn" title="@lang('View Invoice')">
                                        €
                                    </button>
                                @else
                                    <button style="width: 28px; margin-right: 5px; background-color: #f3e03b !important; color: #646464 !important;" data-existimage="exist" data-bidid="{{ $bid->id }}" class="icon-btn generalsysviewbtn" title="@lang('View Invoice')">
                                        €
                                    </button>
                                @endif
                            @else
                                @if(trim($bid->pending_imageurl) == "")
                                    <button style="width: 28px; margin-right: 5px;" data-existimage="" data-bidid="{{ $bid->id }}" class="icon-btn generalsysviewbtn" title="@lang('View Invoice')">
                                        €
                                    </button>
                                @else
                                    <button style="width: 28px; margin-right: 5px;" data-existimage="exist" data-bidid="{{ $bid->id }}" class="icon-btn generalsysviewbtn" title="@lang('View Invoice')">
                                        €
                                    </button>
                                @endif
                            @endif
                            
                            <a href="{{ route('auction.details', [$bid->auction->id, slug($bid->auction->name)]) }}" target="__blank"
                                class="icon-btn bid-details bid-details-tag" style="width: 28px; margin-right: 5px;" title="@lang('View Auction Lot')">
                                <i class="las la-eye text--shadow"></i>
                            </a>
                            @if($bid->product_delivered == 4)
                                <button style="width: 28px;" data-delid="{{ $bid->id }}" title="@lang('Delete')" class="icon-btn btn--danger biddelbtn biddelbtntag" >
                                    <i class="las la-trash text--shadow"></i>
                                </button>
                            @else
                                <button style="width: 28px;" data-delid="{{ $bid->id }}" title="@lang('Delete')" class="icon-btn btn--danger biddelbtntag" disabled>
                                    <i class="las la-trash text--shadow"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-muted text-center table-empty-td" style="padding-left: 0 !important; padding-right: 0 !important;" colspan="6">{{ __($emptyMessage) }}</td>
                    </tr>
                @endforelse
                </tbody>
            </table><!-- table end -->
        </div>
    </div>
</div>

<!-- High Modal Image -->
<div class="modal fade" id="highImgModal" style="background: #000; border-radius: 10px; border: 2px solid rgba(255,255,255,0.3);">
    <div class="modal-dialog modal_image_dialog_big" style="max-width: 80%; width: fit-content; height: calc(100vh - 4px); display: flex; justify-content: center; align-items: center; margin: auto; background: none;" role="document">
        <div class="modal-content modal_image_content_big" style="flex-direction: row; background-color: rgba(0, 0, 0, 0.8); /* box-shadow: 0px 0px 2px 2px rgba(255, 255, 255, 0.3); */">
            <div class="modal_total_div" style="background: transparent;">
                <div class="img_zoom_sec_blk" id="img_zoom_sec_blk" style="background: transparent;">
                    <div class="img_zoom_sec" id="img_zoom_sec"></div>
                    <div class="img_zoom_sec_title">
                        <div>@lang('Your invoice will be available at this place.')</div>
                        <div>@lang('Please try again later.')</div>
                    </div>
                </div>
                <div class="img_scale_slider" style="background: transparent;">
                  <input type="range" min="1" max="10" value="1" class="slider" id="myRange">
                  <p style="margin-top: 10px; background: transparent;">@lang('Zoom'): <span id="demo" style="background: transparent;"></span></p>
                </div>
            </div>
            <div class="modal_space_list"></div>
            <div class="modal_img_list" style="background: transparent; position: relative;">
                <div class="selected_img_download" style="background: transparent;">
                    <a class="a_download_img" target="_blank" download rel="nofollow" title="@lang('Download File')" href="#">
                        <i class="fas fa-download" style="background: transparent;"></i>
                    </a>
                </div>
                <button class="btn text--danger close high_img_modal_close" data-dismiss="modal" aria-label="Close" title="@lang('Close Window')">
                    <span aria-hidden="true" style="font-size: 20px; background: transparent; text-shadow: none; font-weight: 400; padding: 1px 4px; z-index: 100;">&times;</span>
                </button>
                <div class="paidsubimagelist" style="background: transparent;">
                    @forelse($winningbids as $winner)
                        @if($winner->paid_imageurl != "")
                            @php
                                $auctionpaidimagenum ++;
                            @endphp
                            @php
                                $paidresult = explode( ',', $winner->paid_imageurl );
                                $auctionfirstpaidimageurl = getImage(imagePath()['product']['path'].'/'.$paidresult[0],imagePath()['product']['size']);    
                            @endphp
                            @foreach($paidresult as $result)
                                @if($result != "")
                                    <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                        <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @empty
                    @endforelse
                    @if($auctionpaidimagenum == 0)
                        <div style="display: inline-block; padding: 10px; background: transparent;">
                            @lang('No Image Data')
                        </div>
                    @endif
                </div>
                <div class="pickedsubimagelist" style="background: transparent;">
                    @forelse($winningbids as $winner)
                        @if($winner->picked_imageurl != "")
                            @php
                                $auctionpickedimagenum ++;
                            @endphp
                            @php
                                $pickresult = explode( ',', $winner->picked_imageurl );
                                $auctionfirstpickedimageurl = getImage(imagePath()['product']['path'].'/'.$pickresult[0],imagePath()['product']['size']);
                            @endphp
                            @foreach($pickresult as $result)
                                @if($result != "")
                                    <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                        <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @empty
                    @endforelse
                    @if($auctionpickedimagenum == 0)
                        <div style="display: inline-block; padding: 10px; background: transparent;">
                            @lang('No Image Data')
                        </div>
                    @endif
                </div>
                <div class="packedsubimagelist" style="background: transparent;">
                    @forelse($winningbids as $winner)
                        @if($winner->packed_imageurl != "")
                            @php
                                $auctionpackedimagenum ++;
                            @endphp
                            @php
                                $auctionpackresult = explode(',', $winner->packed_imageurl );
                                $auctionfirstpackedimageurl = getImage(imagePath()['product']['path'].'/'.$auctionpackresult[0], imagePath()['product']['size']);
                            @endphp
                            @foreach($auctionpackresult as $result)
                                @if($result != "")
                                    <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                        <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @empty
                    @endforelse
                    @if($auctionpackedimagenum == 0)
                        <div style="display: inline-block; padding: 10px; background: transparent;">
                            @lang('No Image Data')
                        </div>
                    @endif
                </div>
                <div class="generalsysviewlist" style="background: transparent;">
                    
                </div>
            </div>
        </div>
    </div>
    <img id="img_hidden_size" style="display: none;">
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
            <form action="{{ route('user.winningbid.delete') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this bid') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" id="delbid_id" name="delbid_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary deletebtnsubmit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- fast shipping status MODAL --}}
<div id="fastshippingModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Need Shipping')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.winningbid.updatecheckout') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group mb-4">
                            <span title="@lang('Shipping Costs')" class="input-group-text bg--base text-white currencyicon">
                                <i class="fas fa-shipping-fast"></i>
                            </span>
                            <select name="checkout_id" class="form-control" id="checkout_id" required>
                                <option value="">@lang('Please choose or add the shipping address')</option>
                                @foreach($checkdata as $data)
                                    <option value="{{ $data->id }}">{{ $data->firstname }} {{ $data->lastname }}, {{ $data->address }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="input-group">
                            <select name="paymentmethod_id" class="form-control" id="paymentmethod_id" required>
                                <option value="">@lang('Please choose the payment method')</option>
                                @foreach($paymentmethods as $data)
                                    <option value="{{ $data->name }}">{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="" name="auctionwinner_id" class="auctionwinner_id updatecheckoutauctionwinner_id" required />
                </div>
                <div class="modal-footer" style="justify-content: space-between;">
                    <button type="button" class="btn btn--primary" id="fastaddshippingbtn">@lang('Add New')</button>
                    <button type="button" class="btn btn--danger" id="unfastaddshippingsubmitbtn">@lang('Unmark')</button>
                    <button type="submit" class="btn btn--success" id="fastaddshippingsubmitbtn">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- fast shipping status MODAL --}}
<div id="fastaddshippingModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Shipping address')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('First Name') <span style="color: red !important;">*</span></label>
                            <input class="form-control" type="text" name="firstname" id="firstname" value="" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Last Name') <span style="color: red !important;">*</span></label>
                            <input class="form-control" type="text" name="lastname" id="lastname" value="" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Address') <span style="color: red !important;">*</span></label>
                            <input class="form-control" type="text" name="address" id="address" value="" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('City') <span style="color: red !important;">*</span></label>
                            <input class="form-control" type="text" name="city" id="city" value="" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Postal Code') <span style="color: red !important;">*</span></label>
                            <input class="form-control" type="text" name="postalcode" id="postalcode" value="" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Country') <span style="color: red !important;">*</span></label>
                            <select class="form-control" aria-invalid="false" name="country" id="country" style="background-color: transparent;" required>
                                <option value=""></option>
                                @foreach($countries as $key => $country)
                                    <option value="{{ $key }}">
                                        {{ __($country->country) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Tel.')</label>
                            <input class="form-control" type="text" name="tel" id="tel" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label font-weight-bold">@lang('Email')</label>
                            <input class="form-control" type="text" name="email" id="email" value="">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn--primary shippingaddressaddbtn">@lang('Add')</button>
            </div>
        </div>
    </div>
</div>

{{-- fast pick up status MODAL --}}
<div id="fastpickupdateModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Pick up')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.winningbid.updatepickupdate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p style="margin-bottom: 10px;">@lang('On the day of pick up you need to show the printed invoice otherwise we can not hand over the goods.')</p>
                    <div class="form-group">
                        <input type="text" name="started_at" placeholder="@lang('Choose the date and time when you come to pick up')" id="startDateTime" data-position="bottom left" class="form-control border-radius-5" value="" required />
                    </div>
                    <input type="hidden" value="" name="auctionwinner_id" class="auctionwinner_id pickauctionwinner_id" required />
                </div>
                <div class="modal-footer" style="display: flex; justify-content: space-between;">
                    <button type="button" class="btn btn--danger unmarkpickupdatebtn">@lang('Delete Pick-Up Date')</button>
                    <button type="submit" class="btn btn--success markpickupdatebutton">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="fastpi" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('user.winningbid.unmarkpickupdate') }}" method="POST">
                    @csrf
                    <input type="hidden" value="" name="auctionwinner_id" id="updateauctionwinner_id" required />
                    <button type="submit" class="updatepickauctionbtn">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="aaa" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('user.winningbid.unupdatecheckout') }}" method="POST">
                    @csrf
                    <input type="hidden" value="" name="auctionwinner_id" id="checkupdateauctionwinner_id" required />
                    <button type="submit" class="checkupdatepickauctionbtn">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- fast combined shipping status MODAL --}}
<div id="fastcombinedshippingModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Waiting for combined shipping')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.winningbid.updatecombineshipping') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('You want combine shipping with a future item? We can wait until you choose "Shipping" or "Pick up" within the next 14 days.')</p>
                    <input type="hidden" value="" name="auctionwinner_id" class="auctionwinner_id combineauctionwinner_id" required />
                </div>
                <div class="modal-footer" style="justify-content: space-between;">
                    <button type="button" class="btn btn--danger unmarkcombineshippingbtn">@lang('Unmark')</button>
                    <button type="submit" class="btn btn--success markcombineshippingbutton">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="fastcom" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{ route('user.winningbid.unmarkcombineshipping') }}" method="POST">
                    @csrf
                    <input type="hidden" value="" name="auctionwinner_id" id="unmarkcombineauctionwinner_id" required />
                    <button type="submit" class="updateunmarkcombineshippingbtn">@lang('Submit')</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- currency shipping costs MODAL --}}
<div id="currencyiconModal" class="modal fade" tabindex="-1" role="dialog" style="top: 240px;">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Shipping Costs')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @foreach($shippings as $shipping)
                    <p>{{ $shipping->shipping_text }} - {{ $shipping->shipping_amount }} Euro</p>
                @endforeach
            </div>
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
    .datepickers-container {
        z-index: 10000000;
    }
    
    button[disabled=disabled], button:disabled {
        background-color: #ea5455 !important;
    }
    
    .packedspantext {
        background-color: rgba(162, 227, 191, 0.1);
        color: white;
        border: 1px solid brown;
        border-radius: 999px;
        padding: 2px 15px;
    }
    
    .status-main {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .status-main > .paidstatus {
        border: 1px solid #00f700 !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #fff;
        background-color: transparent;
        cursor: pointer;
        text-align: center;
    }
    
    .status-main > .pickedstatus {
        border: 1px solid #0000ff !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #fff;
        background: transparent;
        cursor: pointer;
        text-align: center;
    }
    
    .status-main > .packedstatus {
        border: 1px solid #ff00ff !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #fff;
        background: transparent;
        cursor: pointer;
        text-align: center;
    }
    
    .status-main > .transitstatus {
        border: 1px solid #ff8800 !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #fff;
        background: transparent;
        text-align: center;
    }
    
    .status-main > .pickedstatusinactive {
        border: 1px solid #818479 !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #000 !important;
        background: #b5b8b1;
        text-align: center;
    }
    
    .status-main > .packedstatusinactive {
        border: 1px solid #818479 !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #000 !important;
        background: #b5b8b1;
        text-align: center;
    }
    
    .status-main > .transitstatusinactive {
        border: 1px solid #818479 !important;
        border-radius: 30px;
        width: 90px;
        min-width: 90px;
        color: #000 !important;
        background: #b5b8b1;
        text-align: center;
    }
    
    .status-main > .status-stick {
        max-width: 19px;
        min-width: 15px;
        height: 1px;
        background: #838486 !important;
        width: 15px;
        flex: 1;
    }
    
    .tdformobile {
        display: none;
    }
    
    @media (max-width: 991px) {
        .status-main {
            justify-content: flex-end;
        }
        
        .subpurchasedatespan {
            display: none;
        }
        
        .table-empty-td {
            display: flex !important;
            justify-content: center !important;
            border-bottom-color: transparent !important;
        }
        
        .generalsysviewbtn {
            width: 20px !important;
            padding: 0 !important;
            height: 20px;
        }
        
        .bid-details-tag {
            width: 20px !important;
            padding: 0px 3px !important;
            height: 20px;
        }
        
        .biddelbtntag {
            width: 20px !important;
            padding: 0 !important;
            height: 20px !important;
        }
        
        .table-responsive--md tbody tr {
            display: block;
            margin-bottom: 50px;
        }
        
        table.table--light.style--two tbody td:last-child {
            border-bottom: 1px solid #e8e8e8;
        }
        
        table.table--light.style--two tbody td:first-child {
            border-top: unset;
        }
        
        .tdformobile {
            display: block !important;
        }
        
        .tdfordesktop {
            display: none !important;
        }
    }
    
    @media (max-width: 610px) {
        .status-main > .paidstatus {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .pickedstatus {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .packedstatus {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .transitstatus {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .paidstatusinactive {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .pickedstatusinactive {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .packedstatusinactive {
            width: 70px;
            min-width: 70px;
        }
        
        .status-main > .transitstatusinactive {
            width: 70px;
            min-width: 70px;
        }
    }
    
    @media (max-width: 470px) {
        .status-main > .paidstatus {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .pickedstatus {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .packedstatus {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .transitstatus {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .transitstatus > a {
            font-size: 9px;
        }
        
        .status-main > .paidstatusinactive {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .pickedstatusinactive {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .packedstatusinactive {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
        
        .status-main > .transitstatusinactive {
            width: 55px;
            min-width: 55px;
            font-size: 9px;
            padding: 3px 0px;
        }
    }
    
    .high_img_modal_close {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
        margin: 0 0 20px 0;
        padding: 0;
        width: 100%;
        opacity: 1 !important;
        margin-right: -8px;
    }
    
    .high_img_modal_close > span {
        color: #ea5455 !important;
    }
    
    .high_img_modal_close:hover {
        opacity: 1 !important;
    }
    
    .product_detail_image_view {
        cursor: pointer;
    }
    
    .modal_img_view {
        height: calc(100vh - 3.5rem);
    }
    
    .modal_img_list {
        width: 160px;
        padding: 0px 10px 0 10px;
        text-align: center;
    }
    
    .img_zoom_sec_blk {
        width: 800px;
        height: 600px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }
    
    .img_zoom_sec_title {
        position: absolute;
        background: transparent !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .img_zoom_sec_title > div {
        background: transparent !important;
        color: #636363 !important;
    }
    
    .modal_space_list {
        width: 3vw;
        background-color: transparent;
    }
    
    .img_zoom_sec {
        background: transparent;
        --x: 0px;
        --y: 0px;
        background-image: url('');
        background-position: var(--x) var(--y);
        background-repeat: no-repeat;
    }
    
    @media (max-width: 1210px) {
        .img_zoom_sec_blk {
            width: 600px;
            height: 450px;
        }
    }
    
    @media (max-width: 991px) {
        .initial-status-main {
            justify-content: flex-end !important;
        }
    }
    
    @media (max-width: 978px) {
        .img_zoom_sec_blk {
            width: 400px;
            height: 300px;
        }
    }
    
    @media (max-width: 768px) {
        .img_zoom_sec_blk {
            width: 500px;
            height: 375px;
        }
        
        .modal_image_content_big {
            flex-direction: column !important;
        }
        
        .modal_img_list {
            width: 100%;
            padding: 5px 0;
        }
        
        .modal_total_div {
            margin-top: 30px;
        }
        
        .modal_space_list {
            display: none;
            width: 0px;
        }
        
        .high_img_modal_close {
            position: absolute;
            top: 0px;
            right: 15px;
            transform: translate(50%);
            width: fit-content;
            flex-direction: row;
        }
    }
    
    #paymentmethod_id {
        text-align: center;
    }
    
    @media (max-width: 640px) {
        .img_zoom_sec_blk {
            width: 80vw;
            height: calc((80vw / 4) * 3);
        }
    }
    
    @media (max-width: 575px) {
        .modal_image_dialog_big {
            max-width: 100% !important;
            width: calc(100% - 1rem) !important;
        }
        
        .img_zoom_sec_blk {
            width: calc(100vw - 1rem - 2px);
            height: calc(((100vw - 1rem - 2px) / 4) * 3);
        }
        
        .selected_img_download {
            top: -12px !important;
        }
        
        #checkout_id {
            font-size: 11px !important;
        }
        
        #paymentmethod_id {
            font-size: 11px !important;
        }
        
        #startDateTime {
            font-size: 11px !important;
        }
    }
    
    @media (max-width: 400px) {
        .img_zoom_sec_blk {
            width: calc(100vw - 1rem - 2px);
            height: calc(((100vw - 1rem - 2px) / 4) * 3);
        }
    }
    
    .img_scale_slider {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        position: relative;
    }
    
    .img_scale_slider > .slider {
        -webkit-appearance: none;
        width: 50%;
        height: 10px !important;
        border-radius: 5px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }
    
    .img_scale_slider > .slider:hover {
        opacity: 1;
    }
    
    .img_scale_slider > .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border: 0;
        background: linear-gradient(to left, #000, #fff);
        border-radius: 50%;
        cursor: pointer;
    }
    
    .img_scale_slider > .slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border: 0;
        background: linear-gradient(to left, #000, #fff);
        border-radius: 50%;
        cursor: pointer;
    }
    
    .selected_img_download {
        display: flex;
        flex-direction: column;
        position: absolute;
        right: 3px;
        bottom: 3px;
    }
    
    .selected_img_download > span {
        color: #ea5455;
    }
    
    .selected_img_download > a {
        color: #ea5455 !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .selected_img_download > a > i {
        color: #ea5455 !important;
    }
    
    .initial-status-main {
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: transparent;
    }
    
    .initial-status-main > span {
        width: 26px;
        height: 26px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid;
        margin: 0 5px;
        cursor: pointer;
    }
    
    .initial-status-main > span.unactive {
        cursor: unset !important;
    }
    
    .initial-status-main > span.active {
        background-color: #fff100;
    }
    
    .initial-status-main > span > i {
        background-color: transparent;
    }
    
    .initial-status-main > span.active > i {
        color: #3c3b3b !important;
    }
    
    .currencyicon {
        width: 40px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        background: transparent;
        border-style: solid none solid solid;
        border-color: #ced4da !important;
        border-width: 1px !important;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
</style>
@endpush

@push('script')
<script>
    // Create start date
    var start = new Date(),
            prevDay,
            startHours = 0;

        // 09:00 AM
        start.setHours(0);
        start.setMinutes(0);

        // If today is Saturday or Sunday set 10:00 AM
        if ([6, 0].indexOf(start.getDay()) != -1) {
            start.setHours(10);
            startHours = 10
        }
    // date and time picker 
    $('#startDateTime').datepicker({
        timepicker: true,
        language: 'en',
        dateFormat: 'dd-mm-yyyy',
        startDate: start,
        minHours: startHours,
        maxHours: 23,
        onSelect: function (fd, d, picker) {
            // Do nothing if selection was cleared
            if (!d) return;

            var day = d.getDay();

            // Trigger only if date is changed
            if (prevDay != undefined && prevDay == day) return;
            prevDay = day;

            // If chosen day is Saturday or Sunday when set
            // hour value for weekends, else restore defaults
            if (day == 6 || day == 0) {
                picker.update({
                    minHours: 0,
                    maxHours: 23
                })
            } else {
                picker.update({
                    minHours: 0,
                    maxHours: 23
                })
            }
        }
    });
    
    const el = document.querySelector("#img_zoom_sec");
    var slider = document.getElementById("myRange");
    var output = document.getElementById("demo");
    output.innerHTML = slider.value;
    
    var multi_num = 0;
    
    el.addEventListener("wheel", function(e) {
        if(e.deltaY < 0) {
            if(Number(slider.value) >= 10)
            {
                output.innerHTML = 10;
                multi_num = 9;
                slider.value = 10;
                el.style.backgroundSize = (el.clientWidth * 10) + "px " + (el.clientHeight * 10) + "px";
                el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
                el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
            }
            else {
                multi_num = slider.value;
                slider.value = Number(slider.value) + 1;
                output.innerHTML = slider.value;
                el.style.backgroundSize = (el.clientWidth * Number(slider.value)) + "px " + (el.clientHeight * Number(slider.value)) + "px";
                el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
                el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
            }
        } else {
            if(Number(slider.value) <= 1)
            {
                output.innerHTML = 1;
                multi_num = 0;
                slider.value = 1;
                el.style.backgroundSize = (el.clientWidth * 1) + "px " + (el.clientHeight * 1) + "px";
                el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
                el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
            }
            else {
                multi_num = Number(slider.value) - 2;
                slider.value = Number(slider.value) - 1;
                output.innerHTML = slider.value;
                el.style.backgroundSize = (el.clientWidth * Number(slider.value)) + "px " + (el.clientHeight * Number(slider.value)) + "px";
                el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
                el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
            }
        }
    });
    
    slider.oninput = function() {
        output.innerHTML = this.value;
        multi_num = this.value - 1;
        el.style.backgroundSize = (el.clientWidth * this.value) + "px " + (el.clientHeight * this.value) + "px";
    }

    el.addEventListener("mousemove", (e) => {
      el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
      el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
    });
    
    el.addEventListener("touchmove", (e) => {
      el.style.setProperty('--x', -(multi_num * e.offsetX) + "px");
      el.style.setProperty('--y', -(multi_num * e.offsetY) + "px");
    });
    
    (function ($) {
        "use strict";
        
        $(".searchSel").on("change", function() {
            if($(this).val() == "120") {
                window.location.href = "{{ route('user.winningbid.history') }}";
            } else {
                window.location.href = "{{ route('user.winningbid.history') }}/search/"+$(this).val();
            }
        });
        
        $('.currencyicon').on('click', function() {
            var modal = $('#currencyiconModal');
            modal.modal('show');
        });
        
        $('.unmarkpickupdatebtn').on('click', function() {
            $('#updateauctionwinner_id').val($('.pickauctionwinner_id').val());
            $('.updatepickauctionbtn').click();
        });
        
        $('#unfastaddshippingsubmitbtn').on('click', function() {
            $('#checkupdateauctionwinner_id').val($('.updatecheckoutauctionwinner_id').val());
            $('.checkupdatepickauctionbtn').click();
        });
        
        $('.unmarkcombineshippingbtn').on('click', function() {
            $('#unmarkcombineauctionwinner_id').val($('.combineauctionwinner_id').val());
            $('.updateunmarkcombineshippingbtn').click();
        });
        
        $('.shippingaddressaddbtn').on('click', async function() {
            var csrftoken = "{{ csrf_token() }}";
            var shippingaddurl = "{{ route('user.winningbid.addshippingnew') }}";
            if($('#firstname').val().trim() == "") {
                $('#firstname').focus();
                iziToast['warning']({
                    message: "Please fill in First Name field.",
                    position: "topRight"
                });
            } else if($('#lastname').val().trim() == "") {
                $('#lastname').focus();
                iziToast['warning']({
                    message: "Please fill in Last Name field.",
                    position: "topRight"
                });
            } else if($('#address').val().trim() == "") {
                $('#address').focus();
                iziToast['warning']({
                    message: "Please fill in Address field.",
                    position: "topRight"
                });
            } else if($('#city').val().trim() == "") {
                $('#city').focus();
                iziToast['warning']({
                    message: "Please fill in City field.",
                    position: "topRight"
                });
            } else if($('#postalcode').val().trim() == "") {
                $('#postalcode').focus();
                iziToast['warning']({
                    message: "Please fill in Postal Code field.",
                    position: "topRight"
                });
            } else if($('#country').val().trim() == "") {
                $('#country').focus();
                iziToast['warning']({
                    message: "Please fill in Country field.",
                    position: "topRight"
                });
            } else {
                var formData = new FormData();
                formData.append("_token", csrftoken);
                formData.append("firstname", $('#firstname').val());
                formData.append("lastname", $('#lastname').val());
                formData.append("address", $('#address').val());
                formData.append("city", $('#city').val());
                formData.append("tel", $('#tel').val());
                formData.append("postalcode", $('#postalcode').val());
                formData.append("country", $('#country').val());
                formData.append("email", $('#email').val());
                
                await $.ajax({
                    method: 'post',
                    processData: false,
                    contentType: false,
                    cache: false,
                    data: formData,
                    enctype: 'multipart/form-data',
                    url: shippingaddurl,
                    success: function (response) {
                        console.log(response);
                        var modal = $('#fastaddshippingModal');
                        modal.modal('hide');
                        var checkout_id = $('#checkout_id');
                        checkout_id.append('<option value="' + response.id + '">' + response.firstname + " " + response.lastname + ", " + response.address + '</option>');
                    },
                    error: function(data){
                        iziToast['error']({
                            message: "You have error.",
                            position: "topRight"
                        });
                        return;
                    }
                });
            }
        });
        
        $('.generalsysviewbtn').on('click', function() {
            var data = $(this).data();
            var firstgetimagesrc = "{{ getImage(imagePath()['product']['path'], imagePath()['pendinginvoice']['size']) }}";
            if(data.existimage == "") {
                $('.generalsysviewlist').empty();
                $('.generalsysviewlist').append(`<div style="display: inline-block; padding: 10px; background: transparent;">@lang('No Image Data')</div>`);
            } else {
                var winningbidsdataary = @php echo $winningbids; @endphp;
                var gryfindorFilter = winningbidsdataary.filter(function (item) {
                	return item.id == data.bidid;
                });
                $('.generalsysviewlist').empty();
                if(!gryfindorFilter[0].pending_imageurl) {
                    $('.generalsysviewlist').append(`<div style="display: inline-block; padding: 10px; background: transparent;">@lang('No Image Data')</div>`);
                } else {
                    var subary1 = gryfindorFilter[0].pending_imageurl.split(",");
                    firstgetimagesrc = "https://7-bids.com/assets/images/product/" + subary1[0];
                    subary1.forEach(function (item, index) {
                    	if(item != "") {
                    	    var getimagesrc1 = "https://7-bids.com/assets/images/product/" + item;
                    	    $('.generalsysviewlist').append(`<div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;"><img id="image_replace_id" src="` + getimagesrc1 + `"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" ></div>`);
                    	}
                    });
                }
            }
            
            var modal = $('#highImgModal');
            $('.paidsubimagelist').hide();
            $('.pickedsubimagelist').hide();
            $('.packedsubimagelist').hide();
            $('.generalsysviewlist').show();
            if(firstgetimagesrc.indexOf("2400x1800") > -1) {
                $('.img_zoom_sec_title').show();
            } else {
                $('.img_zoom_sec_title').hide();
            }
            $('.img_zoom_sec').css('background-image', 'url('+ firstgetimagesrc +')');
            $('.a_download_img').attr('href', firstgetimagesrc);
            var img_w;
            var img_h;
            const img = new Image();
            img.addEventListener("load", () => {
                img_w = img.naturalWidth;
                img_h = img.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            img.src = firstgetimagesrc;
            modal.modal('show');
        });
        
        $('.biddelbtn').on('click', function() {
            var modal = $('#deleteModal');
            var data = $(this).data();
            modal.find('input[name=delbid_id]').val(data.delid);
            $('.deletebtnsubmit').click();
        });
        
        $('.fastshippingstatus').click(function() {
            var modal = $('#fastshippingModal');
            var data = $(this).data();
            if(data.checkoutid == 0) {
                $('#fastaddshippingsubmitbtn').prop('disabled', false);
                $('#unfastaddshippingsubmitbtn').prop('disabled', true);
                $('#fastaddshippingbtn').css('display', "block");
                modal.find('#checkout_id').val("");
                modal.find('#checkout_id').prop('disabled', false);
                modal.find('#paymentmethod_id').val("");
                modal.find('#paymentmethod_id').prop('disabled', false);
                $('#fastaddshippingsubmitbtn').css('display', 'block');
                modal.find('.modal-footer').css('justify-content', 'space-between');
            } else {
                $('#fastaddshippingsubmitbtn').prop('disabled', true);
                $('#unfastaddshippingsubmitbtn').prop('disabled', false);
                modal.find('#checkout_id').val(data.checkoutid);
                modal.find('#checkout_id').prop('disabled', true);
                modal.find('#paymentmethod_id').val(data.paymentname);
                modal.find('#paymentmethod_id').prop('disabled', true);
                $('#fastaddshippingbtn').css('display', "none");
                $('#fastaddshippingsubmitbtn').css('display', 'none');
                modal.find('.modal-footer').css('justify-content', 'center');
            }
            modal.find('.auctionwinner_id').val(data.winnerid);
            modal.modal('show');
        });
        
        $('#fastaddshippingbtn').click(function() {
            var modal = $('#fastaddshippingModal');
            modal.modal('show');
        });
        
        $('.fastpickupdatestatus').click(function() {
            var modal = $('#fastpickupdateModal');
            var data = $(this).data();
            if(data.pickupdate == "" || data.pickupdate == null) {
                $('.unmarkpickupdatebtn').prop('disabled', true);
                $('.markpickupdatebutton').prop('disabled', false);
                $('.markpickupdatebutton').css('display', 'block');
                modal.find('#startDateTime').val('');
                modal.find('#startDateTime').prop('disabled', false);
                modal.find('.modal-footer').css('justify-content', 'space-between');
            } else {
                $('.unmarkpickupdatebtn').prop('disabled', false);
                $('.markpickupdatebutton').prop('disabled', true);
                modal.find('#startDateTime').val(data.pickupdate);
                modal.find('#startDateTime').prop('disabled', true);
                modal.find('.modal-footer').css('justify-content', 'center');
                $('.markpickupdatebutton').css('display', 'none');
            }
            modal.find('.auctionwinner_id').val(data.winnerid);
            modal.modal('show');
        });
        
        $('.fastcombinedshippingstatus').click(function() {
            var modal = $('#fastcombinedshippingModal');
            var data = $(this).data();
            if(data.shippingflag == 0) {
                $('.unmarkcombineshippingbtn').prop('disabled', true);
                $('.markcombineshippingbutton').prop('disabled', false);
                $('.markcombineshippingbutton').css('display', 'block');
                modal.find('.modal-footer').css('justify-content', 'space-between');
            } else {
                $('.unmarkcombineshippingbtn').prop('disabled', false);
                $('.markcombineshippingbutton').prop('disabled', true);
                $('.markcombineshippingbutton').css('display', 'none');
                modal.find('.modal-footer').css('justify-content', 'center');
            }
            modal.find('.auctionwinner_id').val(data.winnerid);
            modal.modal('show');
        });
        
        $('.paidstatus').click(function() {
            var modal = $('#highImgModal');
            var firstgetimagesrc = "";
            var data = $(this).data();
            var winningbidsdataary = @php echo $winningbids; @endphp;
            var gryfindorFilter = winningbidsdataary.filter(function (item) {
            	return item.id == data.bidid;
            });
            $('.paidsubimagelist').empty();
            if(!gryfindorFilter[0].paid_imageurl) {
                $('.paidsubimagelist').append(`<div style="display: inline-block; padding: 10px; background: transparent;">@lang('No Image Data')</div>`);
            } else {
                var subary = gryfindorFilter[0].paid_imageurl.split(",");
                firstgetimagesrc = "https://7-bids.com/assets/images/product/" + subary[0];
                subary.forEach(function (item, index) {
                	if(item != "") {
                	    var getimagesrc = "https://7-bids.com/assets/images/product/" + item;
                	    $('.paidsubimagelist').append(`<div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;"><img id="image_replace_id" src="` + getimagesrc + `"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" ></div>`);
                	}
                });
            }
            
            $('.paidsubimagelist').show();
            $('.pickedsubimagelist').hide();
            $('.packedsubimagelist').hide();
            $('.generalsysviewlist').hide();
            $('.img_zoom_sec_title').hide();
            if(firstgetimagesrc != "") {
                $('.img_zoom_sec').css('background-image', 'url("' + firstgetimagesrc + '")');
            } else {
                $('.img_zoom_sec').css('background-image', 'url("")');
            }
            if(firstgetimagesrc != "") {
                $('.a_download_img').attr('href', firstgetimagesrc);
            } else {
                $('.a_download_img').attr('href', '');
            }
            var img_w;
            var img_h;
            const img = new Image();
            img.addEventListener("load", () => {
                img_w = img.naturalWidth;
                img_h = img.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            if(firstgetimagesrc != "") {
                img.src = firstgetimagesrc;
            } else {
                img.src = "";
            }
            modal.modal('show');
        });
        
        $('.pickedstatus').click(function() {
            var modal = $('#highImgModal');
            var firstgetimagesrc = "";
            var data = $(this).data();
            var winningbidsdataary = @php echo $winningbids; @endphp;
            var gryfindorFilter = winningbidsdataary.filter(function (item) {
            	return item.id == data.bidid;
            });
            $('.pickedsubimagelist').empty();
            if(!gryfindorFilter[0].picked_imageurl) {
                $('.pickedsubimagelist').append(`<div style="display: inline-block; padding: 10px; background: transparent;">@lang('No Image Data')</div>`);
            } else {
                var subary = gryfindorFilter[0].picked_imageurl.split(",");
                firstgetimagesrc = "https://7-bids.com/assets/images/product/" + subary[0];
                subary.forEach(function (item, index) {
                	if(item != "") {
                	    var getimagesrc = "https://7-bids.com/assets/images/product/" + item;
                	    $('.pickedsubimagelist').append(`<div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;"><img id="image_replace_id" src="` + getimagesrc + `"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" ></div>`);
                	}
                });
            }
            
            $('.paidsubimagelist').hide();
            $('.pickedsubimagelist').show();
            $('.packedsubimagelist').hide();
            $('.generalsysviewlist').hide();
            $('.img_zoom_sec_title').hide();
            if(firstgetimagesrc != "") {
                $('.img_zoom_sec').css('background-image', 'url("' + firstgetimagesrc + '")');
            } else {
                $('.img_zoom_sec').css('background-image', 'url("")');
            }
            if(firstgetimagesrc != "") {
                $('.a_download_img').attr('href', firstgetimagesrc);
            } else {
                $('.a_download_img').attr('href', '');
            }
            var img_w;
            var img_h;
            const img = new Image();
            img.addEventListener("load", () => {
                img_w = img.naturalWidth;
                img_h = img.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            if(firstgetimagesrc != "") {
                img.src = firstgetimagesrc;
            } else {
                img.src = "";
            }
            modal.modal('show');
        });
        
        $('.packedstatus').click(function() {
            var modal = $('#highImgModal');
            var firstgetimagesrc = "";
            var data = $(this).data();
            var winningbidsdataary = @php echo $winningbids; @endphp;
            var gryfindorFilter = winningbidsdataary.filter(function (item) {
            	return item.id == data.bidid;
            });
            $('.packedsubimagelist').empty();
            if(!gryfindorFilter[0].packed_imageurl) {
                $('.packedsubimagelist').append(`<div style="display: inline-block; padding: 10px; background: transparent;">@lang('No Image Data')</div>`);
            } else {
                var subary = gryfindorFilter[0].packed_imageurl.split(",");
                firstgetimagesrc = "https://7-bids.com/assets/images/product/" + subary[0];
                subary.forEach(function (item, index) {
                	if(item != "") {
                	    var getimagesrc = "https://7-bids.com/assets/images/product/" + item;
                	    $('.packedsubimagelist').append(`<div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;"><img id="image_replace_id" src="` + getimagesrc + `"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" ></div>`);
                	}
                });
            }
            
            $('.paidsubimagelist').hide();
            $('.pickedsubimagelist').hide();
            $('.packedsubimagelist').show();
            $('.generalsysviewlist').hide();
            $('.img_zoom_sec_title').hide();
            if(firstgetimagesrc != "") {
                $('.img_zoom_sec').css('background-image', 'url("' + firstgetimagesrc + '")');
            } else {
                $('.img_zoom_sec').css('background-image', 'url("")');
            }
            if(firstgetimagesrc != "") {
                $('.a_download_img').attr('href', firstgetimagesrc);
            } else {
                $('.a_download_img').attr('href', '');
            }
            var img_w;
            var img_h;
            const img = new Image();
            img.addEventListener("load", () => {
                img_w = img.naturalWidth;
                img_h = img.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            if(firstgetimagesrc != "") {
                img.src = firstgetimagesrc;
            } else {
                img.src = "";
            }
            modal.modal('show');
        });
        
        $(document).on('click', '.replace-image', function () {
            $('.product_detail_image_view').attr('src', $(this).attr("src"));
        });
        
        $(document).on('click', '.replace-modal-image', function() {
            var imgsrc = $(this).attr("src");
            var imgmodal = $('#highImgModal');
            imgmodal.find('.img_zoom_sec').css('background-image', 'url('+imgsrc+')');
            $('.a_download_img').attr('href', imgsrc);
            var img_w;
            var img_h;
            const img1 = new Image();
            img1.addEventListener("load", () => {
                img_w = img1.naturalWidth;
                img_h = img1.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            img1.src = imgsrc;
        });
        
        var izsb = document.getElementById('img_zoom_sec_blk');
        
        $('.product_detail_image_view').on('click', async function() {
            var modalimgsrc = $(this).attr('src');
            var imgmodal = $('#highImgModal');
            imgmodal.find('.img_zoom_sec').css('background-image', 'url('+modalimgsrc+')');
            imgmodal.modal('show');
            $('.a_download_img').attr('href', modalimgsrc);
            var img_w;
            var img_h;
            const img = new Image();
            img.addEventListener("load", () => {
                img_w = img.naturalWidth;
                img_h = img.naturalHeight;
                var img_cw = $('#img_zoom_sec_blk').width();
                var img_ch = $('#img_zoom_sec_blk').height();
                if(img_w == img_h) {
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
                else if(img_w > img_h) {
                    if((img_w / img_h) > (4/3)) {
                        el.style.width = "100%";
                        el.style.height = img_h / (img_w / img_cw) + 'px';
                        el.style.backgroundSize = img_cw + "px " + (img_h / (img_w / img_cw)) + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                    else {
                        el.style.height = "100%";
                        el.style.width = img_w / (img_h / img_ch) + 'px';
                        el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                        el.style.setProperty('--x', "0px");
                        el.style.setProperty('--y', "0px");
                        slider.value = 1;
                        multi_num = 0;
                        output.innerHTML = 1;
                    }
                }
                else if(img_w < img_h){
                    el.style.height = "100%";
                    el.style.width = img_w / (img_h / img_ch) + 'px';
                    el.style.backgroundSize = img_w / (img_h / img_ch) + "px " + img_ch + "px";
                    el.style.setProperty('--x', "0px");
                    el.style.setProperty('--y', "0px");
                    slider.value = 1;
                    multi_num = 0;
                    output.innerHTML = 1;
                }
            });
            img.src = modalimgsrc;
        });
    })(jQuery);
</script>
@endpush
