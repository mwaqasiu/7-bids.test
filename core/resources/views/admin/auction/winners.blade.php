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
                            <th>@lang('Product Name')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Winning Date')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($winners as $winner)
                            <tr>
                                <td data-label="@lang('S.N')">
                                    @if($winner->auction->admin_id)
                                        {{ substr($winner->auction->created_at, 0, 4) }}-0-A-{{ $winner->auction->id }}
                                    @else
                                        {{ substr($winner->auction->created_at, 0, 4) }}-{{ $winner->auction->merchant_id }}-A-{{ $winner->auction->id }}
                                    @endif
                                </td>
                                <td data-label="@lang('Product Name')"><a href="{{ route('auction.details', [$winner->auction->id, slug($winner->auction->name)]) }}" target="_blank">{{ __($winner->auction->name) }}</a></td>
                                <td data-label="@lang('Price')">
                                    {{ $general->cur_sym }}{{ showAmount($winner->auction->price, 0) }}
                                </td>
                                <td data-label="@lang('Winning Date')" title="{{ showDateTime($winner->created_at) }}">
                                    {{ abs(round((strtotime(date("Y-m-d")) - strtotime($winner->created_at)) / 86400)) }} days ago
                                </td>
                                <td data-label="@lang('Status')">
                                    <input type="hidden" value='{{ csrf_token() }}' id="status_token_hidden" >
                                    <input type="hidden" value='{{ route("admin.auction.onestatusimage") }}' id="statusurl_hidden" >
                                    @if($winner->product_delivered == 0)
                                        <input type="file" style="padding: 0; width: 0;" name="pendingimage{{$winner->id}}" class="pendingpicupload" data-winid="{{ $winner->id }}" id="pendingimage{{$winner->id}}" accept=".png, .jpg, .jpeg, .bmp" required/>
                                        <label class="labeluploadcursor" data-toggle="tooltip" data-original-title="@lang('Upload Image')" for="pendingimage{{$winner->id}}">
                                            <span class="text--small badge font-weight-normal badge--danger">@lang('Pending')</span>
                                        </label>
                                    @elseif($winner->product_delivered == 1)
                                        <input type="file" style="padding: 0; width: 0;" name="paidimage{{$winner->id}}" class="paidpicupload" data-winid="{{ $winner->id }}" id="paidimage{{$winner->id}}" accept=".png, .jpg, .jpeg, .bmp" required/>
                                        <label class="labeluploadcursor" data-toggle="tooltip" data-original-title="@lang('Upload Image')" for="paidimage{{$winner->id}}">
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Paid')</span>
                                        </label>
                                    @elseif($winner->product_delivered == 2)
                                        <input type="file" style="padding: 0; width: 0;" name="pickedimage{{$winner->id}}" class="pickedpicupload" data-winid="{{ $winner->id }}" id="pickedimage{{$winner->id}}" accept=".png, .jpg, .jpeg, .bmp" required/>
                                        <label class="labeluploadcursor" data-toggle="tooltip" data-original-title="@lang('Upload Image')" for="pickedimage{{$winner->id}}">
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Picked')</span>
                                        </label>
                                    @elseif($winner->product_delivered == 3)
                                        <input type="file" style="padding: 0; width: 0;" name="packedimage{{$winner->id}}" class="packedpicupload" data-winid="{{ $winner->id }}" id="packedimage{{$winner->id}}" accept=".png, .jpg, .jpeg, .bmp" required/>
                                        <label class="labeluploadcursor" data-toggle="tooltip" data-original-title="@lang('Upload Image')" for="packedimage{{$winner->id}}">
                                            <span class="text--small badge font-weight-normal badge--warning">@lang('Packed')</span>
                                        </label>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--success genshipurlbtn" data-user_id="{{$winner->user->id}}">@lang('Shipped')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    @if($winner->checkout_id != 0)
                                        <button type="button" class="icon-btn btn--primary shippingfastbtn" 
                                            data-firstname="{{$winner->checkout->firstname}}" 
                                            data-lastname="{{$winner->checkout->lastname}}" 
                                            data-address="{{$winner->checkout->address}}" 
                                            data-city="{{$winner->checkout->city}}" 
                                            data-postalcode="{{$winner->checkout->postalcode}}" 
                                            data-country="{{Locale::getDisplayRegion('-'.$winner->checkout->country, 'en')}}" 
                                            data-tel="{{$winner->checkout->tel}}" 
                                            data-paymentmethod="{{ $winner->checkout->paymentname }}"
                                            data-email="{{$winner->checkout->email}}"
                                            style="width: 30px; padding: 3px 0;" >
                                            <i class="fas fa-shipping-fast"></i>
                                        </button>
                                    @endif
                                    @if($winner->pickup_date != Null || $winner->pickup_date != "")
                                        <button type="button" class="icon-btn btn--primary pickdatefastbtn" style="width: 30px; padding: 3px 0;" data-pickdate="{{ $winner->pickup_date }}">
                                            <i class="fas fa-walking"></i>
                                        </button>
                                    @endif
                                    @if($winner->combine_shipping_flag != 0)
                                        <button type="button" style="width: 30px; padding: 3px 0;" class="icon-btn btn--primary">
                                            <i class="las la-hourglass-half"></i>
                                        </button>
                                    @endif
                                    @if($winner->product_delivered == -1)
                                        <button type="button" class="icon-btn btn--danger productPaidBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPickedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPackedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productTransitedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @elseif($winner->product_delivered == 0)
                                        <button type="button" class="icon-btn btn--danger productPaidBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPickedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPackedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productTransitedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @elseif($winner->product_delivered == 1)
                                        <button type="button" class="icon-btn btn--success productPaidBtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPickedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPackedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productTransitedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @elseif($winner->product_delivered == 2)
                                        <button type="button" class="icon-btn btn--success productPaidBtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productPickedBtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productPackedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productTransitedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @elseif($winner->product_delivered == 3)
                                        <button type="button" class="icon-btn btn--success productPaidBtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productPickedBtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productPackedBtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--danger productTransitedBtn productchangestatusbtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @elseif($winner->product_delivered == 4)
                                        <button type="button" class="icon-btn btn--success productPaidBtn" data-toggle="tooltip" data-original-title="@lang('Paid')" data-id="{{ $winner->id }}">
                                            <i class="las la-dollar-sign text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productPickedBtn" data-toggle="tooltip" data-original-title="@lang('Picked')" data-id="{{ $winner->id }}">
                                            <i class="las la-hand-paper text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productPackedBtn" data-toggle="tooltip" data-original-title="@lang('Packed')" data-id="{{ $winner->id }}">
                                            <i class="las la-box-open text--shadow"></i>
                                        </button>
                                        <button type="button" class="icon-btn btn--success productTransitedBtn" data-toggle="tooltip" data-original-title="@lang('Shipped')" data-id="{{ $winner->id }}">
                                            <i class="las la-truck text--shadow"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="icon-btn bid-details" data-toggle="tooltip" data-original-title="@lang('Details')"
                                            data-user="{{ $winner->user }}">
                                        <i class="las la-desktop text--shadow"></i>
                                    </button>
                                    <button type="button" class="icon-btn btn--danger winner-del-btn" data-toggle="tooltip" data-original-title="@lang('Delete')" data-id="{{ $winner->id }}">
                                        <i class="las la-times text--shadow"></i>
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
            @if ($winners->hasPages())    
                <div class="card-footer py-4">
                    {{ paginateLinks($winners) }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- User information modal --}}
<div id="bidModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('User Information')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Name'):
                            <span class="user-name"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Mobile'):
                            <span class="user-mobile"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email'):
                            <span class="user-email"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address'):
                            <span class="user-address"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('State'):
                            <span class="user-state"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Zip Code'):
                            <span class="user-zip"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City'):
                            <span class="user-city"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country'):
                            <span class="user-country"></span>
                        </li>
                    </ul>
               </div>
               <input type="hidden" name="bid_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

{{-- Product paid Confirmation --}}
<div id="productPaidModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Product Paid Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.paid')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Is the product paid')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block productPaidModalSubmit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Product picked Confirmation --}}
<div id="productPickedModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Product Picked Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.picked')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Is the product picked')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block productPicked productPickedModalSubmit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Product Packed Confirmation --}}
<div id="productPackedModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Product Packed Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.packed')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Is the product packed')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block productPackedModalSubmit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Product Transited Confirmation --}}
<div id="productTransitedModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Product Transited Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.transited')}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Is the product transited')</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block productTransitedModalSubmit">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- DELETE MODAL --}}
<div id="winnerdeleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Delete Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.winnerdelete')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('delete')</span> @lang('this item') <span class="font-weight-bold withdraw-user"></span>?</p>
                </div>
                <input type="hidden" class="winner_item_id" id="winner_item_id" name="winner_item_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Generated shipping url modal --}}
<div id="productShipurlModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Generate the Track URL')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.auction.generateshipurl')}}" method="POST">
                @csrf
                <div class="modal-body">
                   <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Tracking Number')
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <input type="text" size="23" class="form-control" name="paketid" required id="paketid" style="height: 40px; padding: 5px; border: 1px solid #000; width: 100%;" />
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Carrier')
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <select name="carrier" id="carrier" size="1" class="form-control" style="height: 40px; padding: 5px; width: 100%; border: 1px solid #000;">
                                    <option value="tnt">TNT</option>
                                    <option value="hermes">Hermes</option>
                                    <option value="hermesuk">Hermes UK</option>
                                    <option value="gls">GLS</option>
                                    <option value="dpd">DPD</option>
                                    <option value="dhl" selected>DHL</option>
                                    <option value="dhlexpress">DHL Express</option>
                                    <option value="ups">UPS</option>
                                    <option value="deutschepost">Deutsche Post</option>
                                    <option value="postat">Österreichische Post</option>
                                    <option value="postch">Schweizerische Post</option>
                                    <option value="ausland">Sendung aus dem Ausland</option>
                                </select>
                            </li>
                        </ul>
                   </div>
                </div>
                <input type="hidden" name="uid" required>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary btn-block">@lang('Track')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Shipping Detail --}}
<div id="shippingdetailModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Shipping Details')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('First Name'):
                            <span class="copytoclipboardbtn firstname" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Last Name'):
                            <span class="copytoclipboardbtn lastname" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Address')
                            <span class="copytoclipboardbtn address" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('City'):
                            <span class="copytoclipboardbtn city" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Postal Code'):
                            <span class="copytoclipboardbtn postalcode" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Country'):
                            <span class="copytoclipboardbtn country" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Telephone'):
                            <span class="copytoclipboardbtn tel" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Email'):
                            <span class="copytoclipboardbtn email" title="Copy"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Payment method'):
                            <span class="copytoclipboardbtn paymentmethod" title="Copy"></span>
                        </li>
                    </ul>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

{{-- Picked Up Detail --}}
<div id="pickdateModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Picked Up Date')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date'):
                            <span class="pickdate"></span>
                        </li>
                    </ul>
               </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .genshipurlbtn {
        cursor: pointer;
    }
    
    .list-group-item {
        border: 0px;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    
    .productchangestatusbtn:hover {
        background-color: #28c76f !important;
    }
    
    .copytoclipboardbtn  {
        cursor: pointer;
    }
    
    .switch {
      position: relative;
      display: inline-block;
      width: 50px;
      height: 28px;
    }
    
    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }
    
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }
    
    input:checked + .slider {
      background-color: #2196F3;
    }
    
    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }
    
    input:checked + .slider:before {
      -webkit-transform: translateX(22px);
      -ms-transform: translateX(22px);
      transform: translateX(22px);
    }
    
    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }
    
    .slider.round:before {
      border-radius: 50%;
    }
</style>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            
            $('.copytoclipboardbtn').on('click', function() {
                // Get the text field
                var copyText = $(this);
                
                // Select the text field
                copyText.select();
                
                // Copy the text inside the text field
                navigator.clipboard.writeText(copyText.text());
                
                iziToast['success']({
                    message: "Copied: " + copyText.text(),
                    position: "topRight"
                });
            });
            
            $('.winner-del-btn').click(function() {
                var modal = $('#winnerdeleteModal');
                modal.find('.winner_item_id').val($(this).data('id'));
                modal.modal('show');
            });
            
            $('.bid-details').click(function(){
                var modal = $('#bidModal');
                var user  = $(this).data().user;
                modal.find('.user-name').text(user.firstname+' '+user.lastname);
                modal.find('.user-mobile').text(user.mobile);
                modal.find('.user-email').text(user.email);
                modal.find('.user-address').text(user.address.address);
                modal.find('.user-state').text(user.address.state);
                modal.find('.user-zip').text(user.address.zip);
                modal.find('.user-city').text(user.address.city);
                modal.find('.user-country').text(user.address.country);
                modal.modal('show');
            });

            $('.productPaidBtn').click(function(){
                var modal = $('#productPaidModal');
                modal.find('[name=id]').val($(this).data('id'));
                $('.productPaidModalSubmit').click();
            });
            
            $('.productPickedBtn').click(function(){
                var modal = $('#productPickedModal');
                modal.find('[name=id]').val($(this).data('id'));
                $('.productPickedModalSubmit').click();
            });
            
            $('.productPackedBtn').click(function(){
                var modal = $('#productPackedModal');
                modal.find('[name=id]').val($(this).data('id'));
                $('.productPackedModalSubmit').click();
            });
            
            $('.productTransitedBtn').click(function(){
                var modal = $('#productTransitedModal');
                modal.find('[name=id]').val($(this).data('id'));
                $('.productTransitedModalSubmit').click();
            });
            
            $('#bidModal').on('hidden.bs.modal', function () {
                $('#bidModal form')[0].reset();
            });
            
            $('.genshipurlbtn').on('click', function() {
                var modal = $('#productShipurlModal');
                modal.find('[name=uid]').val($(this).data('user_id'));
                modal.modal('show');
            });
            
            $('.shippingfastbtn').on('click', function() {
                var modal = $('#shippingdetailModal');
                var data = $(this).data();
                modal.find('.firstname').text(data.firstname);
                modal.find('.lastname').text(data.lastname);
                modal.find('.address').text(data.address);
                modal.find('.city').text(data.city);
                modal.find('.postalcode').text(data.postalcode);
                modal.find('.country').text(data.country);
                modal.find('.tel').text(data.tel);
                modal.find('.email').text(data.email);
                modal.find('.paymentmethod').text(data.paymentmethod);
                modal.modal('show');
            });
            
            
            $('.pickdatefastbtn').on('click', function() {
                var modal = $('#pickdateModal');
                var data = $(this).data();
                modal.find('.pickdate').text(data.pickdate);
                modal.modal('show');
            });
            
        })(jQuery);
    </script>
@endpush
