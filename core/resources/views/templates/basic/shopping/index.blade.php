@extends($activeTemplate.'layouts.frontend')

@section('content')

@php

    $paymentname = "";
    if($checkexist) {
        $paymentname = $checkoutprofile[0]->paymentname;
    }
@endphp
<!-- Shopping -->
<section class="pt-5 pb-120">
    
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="step-main">
            <div class="step-method-div">
                <div class="step-method1 step-method "></div>
                <div class="step-method-text1 text--white">@lang('Shopping Bag')</div>
                <div class="step-submethod1"></div>
                <div class="step-method2"></div>
                <div class="step-method-text2 text--white">@lang('Address')</div>
                <div class="step-submethod2"></div>
                <div class="step-method3"></div>
                <div class="step-method-text3 text--white">@lang('Payment')</div>
            </div>
            
            <!--<div class="step-method-div-text">-->
            <!--    <div>@lang('Shopping Cart')</div>-->
            <!--    <div>@lang('Address')</div>-->
            <!--    <div>@lang('Payment')</div>-->
            <!--</div>-->
        </div>
        <!--<div class="step-header-div">-->
        <!--    <div style="display: flex; margin: 10px 0;">-->
        <!--      <div class="step-div step1 step-active">-->
        <!--        <div></div>-->
        <!--        <div class="triangle-div1" style="width: 145px; text-align: center;">@lang('SHOPPING CART')</div>-->
        <!--        <div class="triangle-left"></div>-->
        <!--      </div>-->
        <!--      <div class="step-div step2">-->
        <!--        <div class="triangle-right"></div>-->
        <!--        <div class="triangle-div1">@lang('ADDRESS')</div>-->
        <!--        <div class="triangle-left"></div>-->
        <!--      </div>-->
        <!--    </div>-->
        <!--    <div style="display: flex; margin: 10px 0;">-->
        <!--      <div class="step-div step3">-->
        <!--        <div class="triangle-right"></div>-->
        <!--        <div class="triangle-div1">@lang('PAYMENT')</div>-->
        <!--        <div class="triangle-left"></div>-->
        <!--      </div>-->
        <!--    </div>-->
        <!--</div>-->
        <div class="shopping-btn-div btn-div2">
            <button class="cmn--btn btn--sm backStepBtn" style="display: none; margin: 0; font-weight: 100;">
                @lang('BACK')
            </button>
            <a class="cmn--btn btn--sm backlinkmarketplace" href="{{ route('product.all') }}" style="margin: 0; font-weight: 100; border-radius: 5px;">
                @lang('ADD ANOTHER ITEM')
            </a>
            <input type="hidden" class="nextstephidden" name="nextstephidden" value="1">
            @if (auth()->check())
                <button class="cmn--btn btn--sm nextStepBtn" style="margin: 0; font-weight: 100;">
                    @lang('NEXT STEP')
                </button>
            @else
                <button class="cmn--btn btn--sm unauthshippingbuybtn" style="margin: 0; font-weight: 100;">
                    @lang('NEXT STEP')
                </button>
            @endif
        </div>
        <div class="summary-block">
            <div>
                @lang('Your shopping bag contains:') <span style="font-weight: bold;">@php echo count($shoppings) @endphp</span> @lang('item')
            </div>
            @php $totalprice = 0 @endphp
            <table class="shopping-table table cmn--table">
                <thead>
                  <tr>
                    <th scope="col" style="text-transform: none;">@lang('ref')</th>
                    <th scope="col" style="text-transform: none;">@lang('item')</th>
                    <th scope="col" style="width: 15%; text-transform: none;">@lang('weight')</th>
                    <th scope="col" style="width: 15%; text-transform: none;">@lang('seller')</th>
                    <th scope="col" style="width: 15%; text-transform: none;">@lang('price')</th>
                    <th scope="col" style="width: 8%; text-transform: none;">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                    @if(count($shoppings) == 0)
                      <tr>
                         <td colspan="6" style="justify-content: center;">
                            <p style="font-weight: 545; font-size: 19px; padding: 50px 0;">@lang($emptyMessage)</p>
                        </td>
                      </tr>
                    @else
                        @foreach ($shoppings as $shopping)
                          <tr>
                            <td data-label="@lang('ref')">{{ $loop->index + 1 }}</td>
                            <td data-label="@lang('item')" class="shoppingcartimagetd">
                                <div>
                                    <a href="{{ route('product.details', [$shopping->pid, slug($shopping->name)]) }}">
                                        @php
                                            $imagereplaceinputnumber = 0;
                                        @endphp
                                        @if($shopping->product->imagereplaceinput)
                                            @foreach ($shopping->product->imagereplaceinput as $imgri)
                                                @php
                                                    $imagereplaceinputnumber = $imagereplaceinputnumber + 1;
                                                @endphp
                                                @if($imagereplaceinputnumber <= 1)
                                                    <img id="image__{{ $shopping->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$imgri['url'],imagePath()['product']['size'])}}" alt="shopping" style="width: 75px; height: 75px; max-width: 150px !important;">
                                                @endif
                                            @endforeach
                                        @else
                                            <img id="image__{{ $shopping->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$shopping->image,imagePath()['product']['size'])}}" alt="shopping" style="width: 75px; height: 75px; max-width: 150px !important;">
                                        @endif
                                    </a>
                                    <span>{{ $shopping->product->name }}</span>
                                </div>
                            </td>
                            <td data-label="@lang('weight')">
                                @if ($shopping->product->specification)
                                    @foreach ($shopping->product->specification as $spec)
                                        @if($spec['name'] == "Weight")
                                            @if($spec['value'] != null)
                                                @php
                                                    echo $spec['value'];
                                                @endphp
                                            @else
                                                @php
                                                    echo "-";
                                                @endphp
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    @php echo "-"; @endphp
                                @endif
                            </td>
                            <td data-label="@lang('seller')">
                                
                                @if($shopping->admin_id)
                                    @php
                                        echo ($shopping->admin->username === 'admin'?'7-BIDS':$shopping->admin->username);
                                    @endphp
                                @endif
                                @if($shopping->merchant_id)
                                    @php
                                        echo $shopping->merchant->username;
                                    @endphp
                                @endif
                            </td>
                            <td data-label="@lang('price')">{{ $general->cur_sym }} {{ number_format($shopping->price, 0,'','.') }}</td>
                            <td data-label="&nbsp;">
                              <button class="deleteShopBtn" style="background: transparent !important; padding: 0; color: white; ouline: none; border: none;" data-shopid="{{ $shopping->id }}">
                                  <i class="las la-trash-alt" style="font-size: 16px;"></i>
                              </button>
                            </td>
                          </tr>
                          @php $totalprice += $shopping->price @endphp
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if(count($shoppings) > 0)
            <div class="shopping-footer">
              <div class="shopping-footer-blk1">
                <div style="display: flex; align-items: center;">
                  <div class="input-group input--group" style="display: flex; align-items: center;">
                    <span style="margin-right: 5px; background-color: transparent;">@lang('Voucher'): </span>
                    <input type="text" class="form-control form--control form--control-2" value="">
                    <button type="submit" class="cmn--btn shoppingapplybtn" style="font-weight: 100; min-width: 100px; margin-left: 15px; border-radius: 5px;">@lang('APPLY')</button>
                  </div>
                </div>
              </div>
              <div style="display: flex; flex-direction: column; background-color: transparent !important;">
                  @if($groupshoppings->count() == 1)
                    <div class="shopping-footer-blk2" style="background-color: transparent; display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;">
                        <span style="background-color: transparent !important; margin-right: 5px; width: 200px;">@lang('Shipping Costs'):</span>
                        <select class="form-control form--control-2 shippingselectbtn" style="height: 40px; -webkit-appearance: auto; appearance: auto; width: unset;">
                            <option value="0">@lang('Select')</option>
                            @foreach($shippings as $shipping)
                                <option value="{{ $shipping->shipping_amount }}">{{ $shipping->shipping_text }} - {{ $shipping->shipping_amount }} Euro</option>
                            @endforeach
                            <option value="0">@lang('collection by the buyer')</option>
                        </select>
                    </div>
                  @else
                    @foreach ($groupshoppings as $shopping)
                      <div class="shopping-footer-blk2" style="background-color: transparent; display: flex; justify-content: center; align-items: center; margin-bottom: 1rem;">
                        <span style="background-color: transparent !important; margin-right: 5px; width: 200px;">@lang('Shipping Costs Seller') {{ $loop->iteration }}:</span>
                        <select class="form-control form--control-2 shippingselectbtn" style="height: 40px; -webkit-appearance: auto; appearance: auto; width: unset;">
                            <option value="0">@lang('Select')</option>
                            <option value="10">@lang('up to 20 kg within Germany - 10 Euro')</option>
                            <option value="20">@lang('up to 10 kg within the EU - 20 Euro')</option>
                            <option value="30">@lang('up to 20 kg within the EU - 30 Euro')</option>
                            <option value="30">@lang('up to 2 kg outside the EU - 30 Euro')</option>
                            <option value="50">@lang('up to 5 kg outside the EU - 50 Euro')</option>
                            <option value="70">@lang('up to 10 kg outside the EU - 70 Euro')</option>
                            <option value="120">@lang('up to 20 kg outside the EU - 120 Euro')</option>
                            <option value="0">@lang('collection by the buyer')</option>
                        </select>
                      </div>
                    @endforeach
                  @endif
                      
              </div>
            </div>
            <div class="shopping-footer">
              <div class="shopping-footer-blk1"></div>
              <div class="shopping-footer-blk3" style="background-color: transparent; display: flex; justify-content: center; align-items: center; margin-top: -1rem;">
                <span style="background-color: transparent !important;">
                    @lang('Total Amount'):
                </span>
                <input type="text" readonly value="{{ $general->cur_sym }} {{ number_format($totalprice, 0,'','.') }}" class="form-control form--control-2 totalpricespan" style="width: 318px; background: transparent !important; height: 40px; color: #a4bdce; border: unset;" />
              </div>
            </div>
            @endif
            <div class="shopping-btn-div btn-div2">
                <button class="cmn--btn btn--sm backStepBtn" style="display: none; margin: 0; font-weight: 100;">
                    @lang('BACK')
                </button>
                <a class="cmn--btn btn--sm backlinkmarketplace" href="{{ route('product.all') }}" style="margin: 0; font-weight: 100; border-radius: 5px;">
                    @lang('ADD ANOTHER ITEM')
                </a>
                <input type="hidden" class="nextstephidden" name="nextstephidden" value="1">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <button class="cmn--btn btn--sm nextStepBtn" style="margin: 0; margin-right: 10px; font-weight: 100;">
                        @lang('GUEST ORDER')
                    </button>
                    <span>@lang('or')</span>
                    @if (auth()->check())
                        <button class="cmn--btn btn--sm nextStepBtn" style="margin: 0; margin-left: 10px; font-weight: 100;">
                            @lang('NEXT STEP')
                        </button>
                    @else
                        <button class="cmn--btn btn--sm unauthshippingbuybtn" style="margin: 0; margin-left: 10px; font-weight: 100;">
                            @lang('NEXT STEP')
                        </button>
                    @endif
                </div>
            </div>
            <div class="shopping-btn-div btn-div2">
              <span></span>
              @if (auth()->check())
                <button class="cmn--btn btn--sm expressshippingbuybtn" style="margin: 0; font-weight: 100; cursor: pointer;">@lang('EXPRESS CHECK OUT')</button>
              @else
                <button class="cmn--btn btn--sm unauthshippingbuybtn" style="margin: 0; font-weight: 100; cursor: pointer;">@lang('EXPRESS CHECK OUT')</button>
              @endif
            </div>
        </div>
        <div class="address-block row">
            <div class="form--group col-md-12">
                <label class="form--label-2" for="company" style="display: flex; text-transform: none;">
                    @lang('Delivery to a company address?')
                    <span class="dropdown-icon-show-c-address"><i class="fa fa-angle-down"></i></span>
                </label>
                <input type="text" class="form-control form--control-2" name="company" id="company" style="color: gray !important;" placeholder="Company Name">
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="first-name">@lang('First Name') <span style="color: red;">*</span></label>
                <input type="text" class="form-control form--control-2" name="firstname" id="first-name" value="{{ auth()->check() ? auth()->user()->firstname : ''}}" required>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="last-name">@lang('Last Name') <span style="color: red;">*</span></label>
                <input type="text" class="form-control form--control-2" name="lastname" id="last-name" value="{{ auth()->check() ? auth()->user()->lastname : ''}}" required>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="address">@lang('Address') <span style="color: red;">*</span></label>
                <div class="selectdivblock">
                    <input type="text" class="form-control form--control-2" name="address" id="address" value="{{ auth()->check() ? auth()->user()->address->address : ''}}" required>
                    <textarea class="form-control form--control-2" name="largeaddress" id="largeaddress">{{ auth()->check() ? auth()->user()->address->address : ''}}</textarea>
                    <label class="toggle-address-icon" style="border: 5px solid transparent; position: absolute; top: 0; right: 0; display: flex; justify-content: center; align-items: center; width: 40px; height: 50px; cursor: pointer;">
                        <i class="fa fa-angle-down" style="transition: 0.3s all ease-in-out;"></i>
                    </label>
                </div>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="last-name">@lang('City') <span style="color: red;">*</span></label>
                <input type="text" class="form-control form--control-2" name="city" id="city" value="{{ auth()->check() ? auth()->user()->address->city : ''}}" required>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="postal-code">@lang('Postal Code') <span style="color: red;">*</span></label>
                <input type="text" class="form-control form--control-2" name="postalcode" id="postal-code" value="{{ auth()->check() ? auth()->user()->address->zip : ''}}" required>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="country">@lang('Country') <span style="color: red;">*</span></label>
                <div class="selectdivblock">
                    <select name="country" id="country" class="form-control form--control-2 valid formselecttag" aria-invalid="false"  required>
                        <option value=""></option>
                        @foreach($countries as $key => $country)
                            <option value="{{ $key }}">
                                {{ __($country->country) }}
                            </option>
                        @endforeach
                    </select>
                    <label style="border: 5px solid transparent; position: absolute; top: 0; right: 0; z-index: -1; display: flex; justify-content: center; align-items: center; width: 40px; height: 50px;">
                        <i class="fa fa-angle-down"></i>
                    </label>
                </div>
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="tel">@lang('Telephone')</label>
                <input type="text" class="form-control form--control-2" name="tel" id="tel" value="{{ auth()->check() ? auth()->user()->mobile : ''}}">
            </div>
            <div class="form--group col-md-6">
                <label class="form--label-2" for="email">@lang('Email') <span style="color: red;">*</span></label>
                <input type="text" class="form-control form--control-2" name="email" id="email" value="{{ auth()->check() ? auth()->user()->email : ''}}" required>
            </div>
            <div class="form--group col-md-12">
                <label class="form--label-2" for="specialinstruc" style="display: flex;">
                    @lang('Special Instruction')
                    <span class="dropdown-icon-show"><i class="fa fa-angle-down"></i></span>
                </label>
                <textarea class="form-control form--control-2" name="specialinstruc" id="specialinstruc"></textarea>
            </div>
            <div class="shopping-btn-div btn-div2">
                <button class="cmn--btn btn--sm backStepBtn" style="margin: 0; font-weight: 100;">
                    @lang('BACK')
                </button>
                <button class="cmn--btn btn--sm nextStepBtn" style="margin: 0; font-weight: 100;">
                    @lang('NEXT STEP')
                </button>
            </div>
        </div>
        <div class="payment-block">
            <div style="font-weight: bold;">@lang('Please select the payment method')</div>
            <ul class="payment-ul">
                @forelse($paymentmethods as $method)
                    <li class="paymentstepallitems payment-step{{ $loop->iteration }}" data-method="{{ $method->name }}">
                        <span><span class="payment-circle"></span>{{ $method->name }}</span>
                        <image src="{{ getImage(imagePath()['product']['path'].'/'.$method->icon) }}" style="width: 50px; height: 30px;" />
                    </li>
                @empty
                    <div style="background-color: transparent !important;">
                        @lang('No Payment Method')
                    </div>
                @endforelse
            </ul>
            <div class="shopping-btn-div btn-div2" style="margin-top: 2rem;">
                <button class="cmn--btn btn--sm backStepBtn" style="margin: 0; font-weight: 100;">
                    @lang('BACK')
                </button>
                @if (auth()->check())
                <button class="cmn--btn btn--sm shippingbuybtn" style="margin: 0; font-weight: bold;">
                    @lang('BUY')
                </button>
                @else
                <button class="cmn--btn btn--sm unauthshippingbuybtn" style="margin: 0; font-weight: bold;">
                    @lang('BUY')
                </button>
                @endif
            </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Shopping -->

<div class="modal fade" id="shopModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.shopping-cart.delete') }}" method="POST">
                @csrf
                <input type="hidden" class="shop_id" name="shop_id" required>
                <div class="modal-body">
                    <h6 class="message"></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base shopdelbtn">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="buyconfirmModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="height: 100px; align-items: baseline;">
                <h5 class="modal-title"></h5>
                <button class="btn text--danger modal-close closebuyconfirmbtnfunc" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="buy_con_section1">
                    <!--<div>-->
                    <!--    <div>{{ $groupshoppings->count() }}</div>-->
                    <!--</div>-->
                    <img src="https://7-bids.com/assets/images/logoIcon/logo.png" width="150px" />
                </div>
                <div class="buy_con_section2">
                    <div>Thank You</div>
                    <div>for your purchase!</div>
                    <div>Soon you will receive an email with the summary of your order and the payment details.</div>
                </div>
                <div class="buy_con_section3">
                    <a href="{{ route('user.winning.history') }}" class="btn btn--success">CHECK ORDER STATUS</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .buy_con_section1 {
        display: flex;
        justify-content: center;
        align-items: center;
        position: absolute;
        flex-direction: column;
        top: -50%;
        right: 50%;
        transform: translate(50%, 65px);
    }
    
    .buy_con_section1 > div {
        position: relative;
        width: 150px;
    }
    
    .buy_con_section1 > div > div {
        position: absolute;
        right: 0;
        top: 25px;
        background: red;
        color: #fff;
        width: 22px;
        height: 22px;
        border-radius: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 12px;
    }
    
    .buy_con_section2 {
        margin-top: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        width: 100%;
    }
    
    .buy_con_section2 > div:nth-child(1) {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
    }
    
    .buy_con_section2 > div:nth-child(2) {
        text-align: center;
        margin-top: 10px;
        font-size: 24px;
        font-weight: bold;
    }
    
    .buy_con_section2 > div:nth-child(3) {
        width: 50%;
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .buy_con_section3 {
        margin: 25px 0 10px 0;
        text-align: center;
    }
    
    .summary-block {
        display: block;
    }
    
    .address-block {
        display: none;
    }
    
    .shipping-block {
        display: none;
    }
    
    .payment-block {
        display: none;
    }
    
    .step-header-div {
        display: flex;
    }
    
    .step-div {
        display: flex;
        flex-direction: row;
        cursor: pointer;
    }
    
    .triangle-blank {
        height: 50px;
        width: 15px;
        background-color: #002533;
    }
    
    .triangle-div1 {
        display: flex;
        align-items: center;
        justify-content: center;
    	width: 95px;
        height: 50px;
        font-weight: bolder;
        color: white;
        text-transform: uppercase;
        background-color: #002533;
        transition: .3s all ease-in-out;
    }
    
    .triangle-right {
    	width: 0;
    	height: 0;
    	border-top: 25px solid #002533;
    	border-left: 25px solid transparent;
    	border-bottom: 25px solid #002533;
    	transition: .3s all ease-in-out;
    }
    
    .triangle-left {
    	width: 0;
    	height: 0;
    	border-top: 25px solid transparent;
    	border-left: 25px solid #002533;
    	border-bottom: 25px solid transparent;
    	transition: .3s all ease-in-out;
    }
    
    .step-active {
    }
    
    .step-active > div:nth-child(1) {
        border-top-color: #336699;
        border-bottom-color: #336699;
    }
    
    .step-active > div:nth-child(2) {
        background-color: #336699;
    }
    
    .step-active > div:nth-child(3) {
        border-left-color: #336699;
    }
    
    @media (max-width: 768px) {
        .step-header-div {
            flex-direction: column;
            align-items: center;
        }
    }
    
    .payment-ul li {
        display: flex;
        justify-content: space-between;
        padding: 10px 0 10px 10px;
        cursor: pointer;
        margin: 5px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .payment-ul li > span {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .payment-ul li > span > .payment-circle {
        background-color: transparent;
        width: 20px;
        height: 20px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 10px;
        background-color: transparent;
        border: 5px solid transparent;
        outline: 1px solid #0475FF;
    }
    
    .payment-ul li.liactive > span {
        font-weight: bold;
    }
    
    .payment-ul li.liactive > span > .payment-circle {
        background-color: #0475FF;
        border: 5px solid white;
        outline: 1px solid #0475FF;
    }
    
    .formselecttag > option {
        background: #001631;
    }
     
    .selectdivblock {
        position: relative;
        z-index: 1;
        background: #001631;
        border-radius: 5px;
    }
    
    .selectdivblock > select {
        background-color: transparent !important;
    }
    
    .dropdown-icon-show {
        border: 1px solid;
        border-radius: 5px;
        width: 25px;
        height: 28px;
        display: flex;
        text-align: center;
        margin-left: 10px;
        vertical-align: middle;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    
    .dropdown-icon-show > i {
        transition: 0.3s all ease-in-out;
    }
    
    .dropdown-icon-show-address {
        border: 1px solid;
        border-radius: 5px;
        width: 25px;
        height: 28px;
        display: flex;
        text-align: center;
        margin-left: 10px;
        vertical-align: middle;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    
    .dropdown-icon-show-address > i {
        transition: 0.3s all ease-in-out;
    }
    
    .dropdown-icon-show-c-address {
        border: 1px solid;
        border-radius: 5px;
        width: 25px;
        height: 28px;
        display: flex;
        text-align: center;
        margin-left: 10px;
        vertical-align: middle;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }
    
    .dropdown-icon-show-c-address > i {
        transition: 0.3s all ease-in-out;
    }
    
    .shopping-table tbody tr td {
        vertical-align: middle;
    }
    
    .step-method-div {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 0 45px 0;
        width: 70%;
    }
    
    .step-method-text1 {
        position: absolute;
        top: 40px;
        font-size: 16px;
        left: 5px;
        transform: translate(-50%, 0);
        color: #fff;
    }
    
    .step-method-text2 {
        position: absolute;
        top: 40px;
        font-size: 16px;
        left: 50%;
        transform: translate(-50%, 0);
        color: #fff;
    }
    
    .step-method-text3 {
        position: absolute;
        top: 40px;
        font-size: 16px;
        right: 10px;
        transform: translate(50%, 0);
        color: #fff;
    }
    
    .step-method-div-text {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 0 0 30px 0;
        width: 80%;
    }
    
    .step-method-div-text > div {
        font-size: 24px;
        flex: 1;
        color: #a4bdce;
        display: flex;
        align-items: center;
    }
    
    .step-method-div-text > div:nth-child(1) {
        justify-content: flex-start;
    }
    
    .step-method-div-text > div:nth-child(2) {
        justify-content: center;
    }
    
    .step-method-div-text > div:nth-child(3) {
        justify-content: flex-end;
    }
    
    .step-method1 {
        height: 25px;
        width: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        background-color: gray;
        color: #002533;
        border-radius: 50px;
        cursor: pointer;
    }
    
    .step-method2 {
        height: 25px;
        width: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        background-color: gray;
        color: #002533;
        border-radius: 50px;
        cursor: pointer;
    }
    
    .step-method3 {
        height: 25px;
        width: 25px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        background-color: gray;
        color: #002533;
        border-radius: 50px;
        cursor: pointer;
    }
    
    .step-submethod1 {
        height: 5px;
        background-color: gray;
        font-weight: bold;
        flex: 1;
    }
    
    .step-submethod2 {
        height: 5px;
        background-color: gray;
        font-weight: bold;
        flex: 1;
    }
    
    .step-method {
        background-color: #336699;
        color: #336699;
    }
    
    .step-method-success {
        
    }
    
    .step-submethod {
        background-color: #336699;
    }
    
    .step-main {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    
    @media (max-width: 768px) {
        .step-method-text1 {
            font-size: 14px;
        }
        
        .step-method-text2 {
            font-size: 14px;
        }
        
        .step-method-text3 {
            font-size: 14px;
        }
    }
    
    @media (max-width: 1200px) {
        .shoppingcartimagetd > div > a {
            display: none;
            margin-right: 10px;
        }
        
        .shoppingcartimagetd.active > div > a {
            display: block;
        }
    }
    
    @media (max-width:996px) {
        .shopping-footer {
            flex-direction: column;
        }
        
        .shopping-footer > .shopping-footer-blk1 > div > div {
            justify-content: center;
            margin-top: 10px !important;
        }
        
        .shopping-footer > .shopping-footer-blk1 > div > div > span {
            width: 100%;
        }
        
        .shopping-footer > .shopping-footer-blk1 > div > div > input {
            width: 50%;
            margin-top: 10px !important;
        }
        
        .shopping-footer > .shopping-footer-blk1 > div > div > button {
            margin: 0 !important;
            margin-top: 10px !important;
            margin-left: 15px !important;
        }
        
        .shopping-footer .shopping-footer-blk2 {
            flex-direction: column;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk2 {
            flex-direction: column;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk2:nth-child(2) {
            flex-direction: column !important;
            align-items: flex-start !important;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk2 > span {
            margin: 0 !important;
            margin-top: 8px !important;
            width: 100% !important;
            text-align: left;
        }
        
        .shopping-footer .shopping-footer-blk2:nth-child(2) > span {
            margin: 0 !important;
            margin-top: 8px !important;
            width: unset !important;
            margin-top: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk2 > select {
            width: 100% !important;
            margin-top: 5px !important;
            background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%), linear-gradient(to right, transparent, transparent);
            background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), 100% 0;
            background-size: 5px 5px, 5px 5px, 2.5em 2.5em;
            background-repeat: no-repeat;
            appearance: none !important;
            font-size: 14px;
        }
        
        .shopping-footer .shopping-footer-blk2:nth-child(2) input {
            width: unset !important;
            text-align: center;
            width: 135px !important;
            margin-top: 0px !important;
        }
        
        
        
        .shopping-footer .shopping-footer-blk3 {
            flex-direction: column;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk3 {
            flex-direction: column;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk3:nth-child(2) {
            flex-direction: row !important;
            justify-content: flex-start !important;
            margin: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk3 > span {
            margin: 0 !important;
            margin-top: 8px !important;
            width: 100% !important;
            text-align: left;
        }
        
        .shopping-footer .shopping-footer-blk3:nth-child(2) > span {
            margin: 0 !important;
            margin-top: 8px !important;
            width: unset !important;
            margin-top: 0 !important;
        }
        
        .shopping-footer .shopping-footer-blk3 > select {
            width: 100% !important;
            margin-top: 5px !important;
            background-image: linear-gradient(45deg, transparent 50%, white 50%), linear-gradient(135deg, white 50%, transparent 50%), linear-gradient(to right, transparent, transparent);
            background-position: calc(100% - 20px) calc(1em + 2px), calc(100% - 15px) calc(1em + 2px), 100% 0;
            background-size: 5px 5px, 5px 5px, 2.5em 2.5em;
            background-repeat: no-repeat;
            appearance: none !important;
            font-size: 14px;
        }
        
        .shopping-footer .shopping-footer-blk3:nth-child(2) input {
            width: unset !important;
            text-align: left;
            width: 135px !important;
            margin-top: 0px !important;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        var authentificationFlag = "<?php echo auth()->check() ? 'exist' : 'empty'; ?>";
        
        $('.closebuyconfirmbtnfunc').on('click', function() {
            location.href = "/";
        });
        
        var shoppinguser = <?php echo auth()->check() ? auth()->user() : "{}" ?>;
        console.log(shoppinguser.address.address);
        $('#largeaddress').on('change', function() {
            $('#address').val($(this).val());
        });
        
        $('#address').on('change', function() {
            $('#largeaddress').val($(this).val());
        });
        
        $.get("https://ipinfo.io", function(response) {
            const regionNames = new Intl.DisplayNames(
              ['en'], {type: 'region'}
            );
            $('select[name=country]').val(response.country);
        }, "json");
        
        var ipaddress = "{{ getenv('REMOTE_ADDR') }}";
        const shippings = <?php echo $shoppings; ?>;
        var savewinnerurl = "{{ route('user.shopping-cart.savewinner') }}";
        var savewinnertoken = "{{ csrf_token() }}";
        
        $('.unauthshippingbuybtn').on('click', function() {
            iziToast['warning']({
                message: "Log In is required.",
                position: "topRight"
            });
        });
        
        $('.shoppingcartimagetd').on('click', function() {
            $(this).find('a').toggle();
        });
        
        $('.expressshippingbuybtn').on('click', async function() {
            if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                iziToast['warning']({
                    message: "@lang('Please select shipping costs.')",
                    position: "topRight"
                });
                $('.shippingselectbtn').focus();
            } else {
                var modalshipbtn = $('#buyconfirmModal');
                var checkexist = "{{ $checkexist }}";
                var paymentname = "{{ $paymentname }}";
                if(checkexist) {
                    if(shippings.length > 0) {
                        for(var i = 0; i < shippings.length; i ++) {
                            var formData = new FormData();
                            formData.append("_token", savewinnertoken);
                            formData.append("pid", shippings[i].product_id);
                            formData.append("id", shippings[i].id);
                            formData.append("paymentmethod", paymentname);
                            formData.append("ipaddress", ipaddress);
                            formData.append('shippingcosts', Number($('.shippingselectbtn').val()).toFixed(0));
                            formData.append('product_price', Number(shippings[i].price).toFixed(0));
                            formData.append('address', shoppinguser.address.address);
                            formData.append('totalprice', Number(Number($('.shippingselectbtn').val()) + Number(shippings[i].price)).toFixed(0));
                            await $.ajax({
                              method: 'post',
                              processData: false,
                              contentType: false,
                              cache: false,
                              data: formData,
                              enctype: 'multipart/form-data',
                              url: savewinnerurl,
                              success: function (response) {
                                  if(response == "exist") {
                                      iziToast['warning']({
                                        message: "Winner for this product is already selected.",
                                        position: "topRight"
                                      });
                                  } else {
                                      iziToast['success']({
                                        message: "Purchasing successfully completed.",
                                        position: "topRight"
                                      });
                                  }
                              },
                              error: function(data){
                                return;
                              }
                            });
                        }
                    }
                    modalshipbtn.modal('show');
                } else {
                    iziToast['warning']({
                        message: "There is no data you set.",
                        position: "topRight"
                    });
                    modalshipbtn.modal('hide');
                }
            }
        });
        
        $('.shippingbuybtn').on('click', async function() {
            var modalshipbtn = $('#buyconfirmModal');
            var paymentmethodname = $('.paymentstepallitems.liactive').data().method;
            if($('.paymentstepallitems.liactive').length > 0) {
                if(shippings.length > 0) {
                    for(var i = 0; i < shippings.length; i ++) {
                        var formData = new FormData();
                        formData.append("_token", savewinnertoken);
                        formData.append("pid", shippings[i].product_id);
                        formData.append("id", shippings[i].id);
                        formData.append("paymentmethod", paymentmethodname);
                        formData.append("ipaddress", ipaddress);
                        formData.append('shippingcosts', Number($('.shippingselectbtn').val()).toFixed(0));
                        formData.append('product_price', Number(shippings[i].price).toFixed(0));
                        formData.append('address', shoppinguser.address.address);
                        formData.append('totalprice', Number(Number($('.shippingselectbtn').val()) + Number(shippings[i].price)).toFixed(0));
                        await $.ajax({
                          method: 'post',
                          processData: false,
                          contentType: false,
                          cache: false,
                          data: formData,
                          enctype: 'multipart/form-data',
                          url: savewinnerurl,
                          success: function (response) {
                              if(response == "exist") {
                                  iziToast['warning']({
                                    message: "Winner for this product is already selected.",
                                    position: "topRight"
                                  });
                              } else {
                                  iziToast['success']({
                                    message: shippings[i].name + " Product as Winner Selected Success!",
                                    position: "topRight"
                                  });
                              }
                          },
                          error: function(data){
                            return;
                          }
                        });
                    }
                }
                modalshipbtn.modal('show');
            } else {
                iziToast['warning']({
                    message: "Please select payment method.",
                    position: "topRight"
                });
                modalshipbtn.modal('hide');
            }
            
        });
        
        $('.shippingselectbtn').on('change', function() {
            var totalprice = Number("{{$totalprice}}");
            $('.shippingselectbtn').each(function(){totalprice += Number($(this).val());});
            $('.totalpricespan').val('{{ $general->cur_sym }} ' + Number(totalprice).toFixed(0));
        });
        
        $('.deleteShopBtn').on('click', function() {
            var modal = $('#shopModal');
            var shopid = $(this).data('shopid');
            modal.find('.message').html('@lang("Are you sure to delete?")');
            modal.find('.shop_id').val(shopid);
            $('.shopdelbtn').click();
        });
        
        $('.onchangeflatrate').on('change', function() {
            var radiovalue = $('input[name="flatrate"]:checked').val();
            if(radiovalue == "flatrate1") {
                $('.label_flatrate1').css('font-weight', "bold");
                $('.label_flatrate2').css('font-weight', "100");
                $('.label_flatrate3').css('font-weight', "100");
                $('.label_flatrate4').css('font-weight', "100");
                $('.label_flatrate5').css('font-weight', "100");
            } else if(radiovalue == "flatrate2") {
                $('.label_flatrate1').css('font-weight', "100");
                $('.label_flatrate2').css('font-weight', "bold");
                $('.label_flatrate3').css('font-weight', "100");
                $('.label_flatrate4').css('font-weight', "100");
                $('.label_flatrate5').css('font-weight', "100");
            } else if(radiovalue == "flatrate3") {
                $('.label_flatrate1').css('font-weight', "100");
                $('.label_flatrate2').css('font-weight', "100");
                $('.label_flatrate3').css('font-weight', "bold");
                $('.label_flatrate4').css('font-weight', "100");
                $('.label_flatrate5').css('font-weight', "100");
            } else if(radiovalue == "flatrate4") {
                $('.label_flatrate1').css('font-weight', "100");
                $('.label_flatrate2').css('font-weight', "100");
                $('.label_flatrate3').css('font-weight', "100");
                $('.label_flatrate4').css('font-weight', "bold");
                $('.label_flatrate5').css('font-weight', "100");
            } else if(radiovalue == "flatrate5") {
                $('.label_flatrate1').css('font-weight', "100");
                $('.label_flatrate2').css('font-weight', "100");
                $('.label_flatrate3').css('font-weight', "100");
                $('.label_flatrate4').css('font-weight', "100");
                $('.label_flatrate5').css('font-weight', "bold");
            }
        });
        
        var addressflag = true;
        $('#largeaddress').hide();
        
        $('.toggle-address-icon').on('click', function() {
           if(addressflag) {
               $('#largeaddress').show();
               $('#largeaddress').val();
               $('#address').hide();
               $('#address').val();
               $('.toggle-address-icon i').css('transform', 'rotate(180deg)');
               addressflag = false;
           }
           else {
               $('#largeaddress').hide();
               $('#largeaddress').val();
               $('#address').show();
               $('#address').val();
               $('.toggle-address-icon i').css('transform', 'rotate(0deg)');
               addressflag = true;
           }
        });
        
        var companyflag = true;
        $('#company').hide();
        
        $('.dropdown-icon-show-c-address').on('click', function() {
            if(companyflag) {
                $('#company').show();
                $('.dropdown-icon-show-c-address i').css('transform', 'rotate(180deg)');
                companyflag = false;
            }
            else {
                $('#company').hide();
                $('.dropdown-icon-show-c-address i').css('transform', 'rotate(0deg)');
                companyflag = true;
            }
        });
        
        var speciconflag = true;
        $('#specialinstruc').hide();
        
        $('.dropdown-icon-show').on('click', function() {
            if(speciconflag) {
                $('#specialinstruc').show();
                $('.dropdown-icon-show i').css('transform', 'rotate(180deg)');
                speciconflag = false;
            }
            else {
                $('#specialinstruc').hide();
                $('.dropdown-icon-show i').css('transform', 'rotate(0deg)');
                speciconflag = true;
            }
        });
        
        $('.paymentstepallitems').on('click', function() {
            $('.paymentstepallitems').removeClass('liactive');
            $(this).addClass('liactive');
        });
        
        $('.step-method1').on('click', function() {
            $('.step-method1').addClass('step-method');
            $('.step-method1').removeClass('step-method-success');
            $('.step-submethod1').removeClass('step-submethod');
            $('.step-method2').removeClass('step-method');
            $('.step-method2').removeClass('step-method-success');
            $('.step-submethod2').removeClass('step-submethod');
            $('.step-method3').removeClass('step-method');
            $('.step-method3').removeClass('step-method-success');
            $('.summary-block').css('display', 'block');
            $('.address-block').css('display', 'none');
            $('.payment-block').css('display', 'none');
            $('.nextstephidden').val(1);
            $('.backlinkmarketplace').css('display', "block");
            $('.backStepBtn').css('display', "none");
            $('.nextStepBtn').css('display', 'block');
        });
        
        $('.step-method2').on('click', function() {
            if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                $('.step-method1').addClass('step-method');
                $('.step-method1').removeClass('step-method-success');
                $('.step-submethod1').removeClass('step-submethod');
                $('.step-method2').removeClass('step-method');
                $('.step-method2').removeClass('step-method-success');
                $('.step-submethod2').removeClass('step-submethod');
                $('.step-method3').removeClass('step-method');
                $('.step-method3').removeClass('step-method-success');
                
                $('.summary-block').css('display', 'block');
                $('.address-block').css('display', 'none');
                $('.payment-block').css('display', 'none');
                $('.nextstephidden').val(1);
                iziToast['warning']({
                    message: "@lang('Please select shipping costs.')",
                    position: "topRight"
                });
                $('.shippingselectbtn').focus();
                $('.backlinkmarketplace').css('display', "block");
                $('.backStepBtn').css('display', "none");
                $('.nextStepBtn').css('display', 'block');
            } else {
                $('.step-method1').addClass('step-method');
                $('.step-method1').addClass('step-method-success');
                $('.step-submethod1').addClass('step-submethod');
                $('.step-method2').addClass('step-method');
                $('.step-method2').removeClass('step-method-success');
                $('.step-submethod2').removeClass('step-submethod');
                $('.step-method3').removeClass('step-method');
                $('.step-method3').removeClass('step-method-success');
                
                $('.summary-block').css('display', 'none');
                $('.address-block').css('display', 'flex');
                $('.payment-block').css('display', 'none');
                $('.nextstephidden').val(2);
                $('.backlinkmarketplace').css('display', "none");
                $('.backStepBtn').css('display', "block");
                $('.nextStepBtn').css('display', 'block');
            }
        });
        
        $('.step-method3').on('click', function() {
            if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                $('.step-method1').addClass('step-method');
                $('.step-method1').removeClass('step-method-success');
                $('.step-submethod1').removeClass('step-submethod');
                $('.step-method2').removeClass('step-method');
                $('.step-method2').removeClass('step-method-success');
                $('.step-submethod2').removeClass('step-submethod');
                $('.step-method3').removeClass('step-method');
                $('.step-method3').removeClass('step-method-success');
                
                $('.summary-block').css('display', 'block');
                $('.address-block').css('display', 'none');
                $('.payment-block').css('display', 'none');
                $('.nextstephidden').val(1);
                iziToast['warning']({
                    message: "@lang('Please select shipping costs.')",
                    position: "topRight"
                });
                $('.shippingselectbtn').focus();
                $('.backlinkmarketplace').css('display', "block");
                $('.backStepBtn').css('display', "none");
                $('.nextStepBtn').css('display', 'block');
            } else {
                if($('#first-name').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#first-name').focus();
                    iziToast['warning']({
                        message: "Please fill in First Name field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#last-name').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#last-name').focus();
                    iziToast['warning']({
                        message: "Please fill in Last Name field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#address').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#address').focus();
                    iziToast['warning']({
                        message: "Please fill in Address field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#city').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#city').focus();
                    iziToast['warning']({
                        message: "Please fill in City field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#postal-code').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#postal-code').focus();
                    iziToast['warning']({
                        message: "Please fill in Postal Code field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#country').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#country').focus();
                    iziToast['warning']({
                        message: "Please fill in Country field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else if($('#email').val().trim() == "") {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.nextstephidden').val(2);
                    $('#email').focus();
                    iziToast['warning']({
                        message: "Please fill in Email field.",
                        position: "topRight"
                    });
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                } else {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').addClass('step-method-success');
                    $('.step-submethod2').addClass('step-submethod');
                    $('.step-method3').addClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                    
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'none');
                    $('.payment-block').css('display', 'block');
                    $('.nextstephidden').val(3);
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'none');
                }
            }
        });
        
        $('.backStepBtn').on('click', function() {
            var nextstep = $('.nextstephidden').val();
            
            if(Number(nextstep) <= 1) {
                $('.summary-block').css('display', 'block');
                $('.address-block').css('display', 'none');
                $('.payment-block').css('display', 'none');
                $('.backlinkmarketplace').css('display', "block");
                $('.backStepBtn').css('display', "none");
                $('.nextStepBtn').css('display', 'block');
            } else if(Number(nextstep) == 2) {
                $('.summary-block').css('display', 'block');
                $('.address-block').css('display', 'none');
                $('.payment-block').css('display', 'none');
                $('.backlinkmarketplace').css('display', "block");
                $('.backStepBtn').css('display', "none");
                $('.nextStepBtn').css('display', 'block');
            } else if(Number(nextstep) == 3) {
                $('.summary-block').css('display', 'none');
                $('.address-block').css('display', 'flex');
                $('.payment-block').css('display', 'none');
                $('.backlinkmarketplace').css('display', "none");
                $('.backStepBtn').css('display', "block");
                $('.nextStepBtn').css('display', 'block');
            }
            
            
            if(Number(nextstep) <= 1) {
                $('.nextstephidden').val(1);
            }
            else {
                $('.nextstephidden').val(Number(Number(nextstep) - 1).toFixed(0));
                if(Number(nextstep) <= 1) {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').removeClass('step-method-success');
                    $('.step-submethod1').removeClass('step-submethod');
                    $('.step-method2').removeClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                } else if(Number(nextstep) == 2) {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').removeClass('step-method-success');
                    $('.step-submethod1').removeClass('step-submethod');
                    $('.step-method2').removeClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                } else if(Number(nextstep) == 3) {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                }
            }
        });
        
        $('.nextStepBtn').on('click', function() {
            var nextstep = $('.nextstephidden').val();
            
            if(Number(nextstep) >= 3) {
                $('.summary-block').css('display', 'none');
                $('.address-block').css('display', 'none');
                $('.payment-block').css('display', 'block');
                $('.backlinkmarketplace').css('display', "none");
                $('.backStepBtn').css('display', "block");
                $('.nextStepBtn').css('display', 'none');
                var len = 0;
                var paymentstepallitems = document.getElementsByClassName("paymentstepallitems");
                for (var i = 0; i < paymentstepallitems.length; i++) {
                    if (paymentstepallitems[i].className.indexOf("liactive") < 0) {
                        len ++;
                    }
                }
                if(len == 4) {
                    iziToast['warning']({
                        message: "Please select payment method.",
                        position: "topRight"
                    });
                }
            } else if(Number(nextstep) == 1) {
                if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                    $('.summary-block').css('display', 'block');
                    $('.address-block').css('display', 'none');
                    $('.payment-block').css('display', 'none');
                    $('.backlinkmarketplace').css('display', "block");
                    $('.backStepBtn').css('display', "none");
                    $('.nextStepBtn').css('display', 'block');
                } else {
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'flex');
                    $('.payment-block').css('display', 'none');
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'block');
                }
            } else if(Number(nextstep) == 2) {
                if($('#first-name').val().trim() == "") {
                } else if($('#last-name').val().trim() == "") {
                } else if($('#address').val().trim() == "") {
                } else if($('#city').val().trim() == "") {
                } else if($('#postal-code').val().trim() == "") {
                } else if($('#country').val().trim() == "") {
                } else if($('#email').val().trim() == "") {
                } else {
                    $('.summary-block').css('display', 'none');
                    $('.address-block').css('display', 'none');
                    $('.payment-block').css('display', 'block');
                    $('.backlinkmarketplace').css('display', "none");
                    $('.backStepBtn').css('display', "block");
                    $('.nextStepBtn').css('display', 'none');
                }
            }
            
            if(Number(nextstep) == 2) {
                if($('#first-name').val().trim() == "") {
                } else if($('#last-name').val().trim() == "") {
                } else if($('#address').val().trim() == "") {
                } else if($('#city').val().trim() == "") {
                } else if($('#postal-code').val().trim() == "") {
                } else if($('#country').val().trim() == "") {
                } else if($('#email').val().trim() == "") {
                } else {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').addClass('step-method-success');
                    $('.step-submethod2').addClass('step-submethod');
                    $('.step-method3').addClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                }
            } else if(Number(nextstep) == 1) {
                if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                } else {
                    $('.step-method1').addClass('step-method');
                    $('.step-method1').addClass('step-method-success');
                    $('.step-submethod1').addClass('step-submethod');
                    $('.step-method2').addClass('step-method');
                    $('.step-method2').removeClass('step-method-success');
                    $('.step-submethod2').removeClass('step-submethod');
                    $('.step-method3').removeClass('step-method');
                    $('.step-method3').removeClass('step-method-success');
                }
            } else {
                $('.step-method1').addClass('step-method');
                $('.step-method1').addClass('step-method-success');
                $('.step-submethod1').addClass('step-submethod');
                $('.step-method2').addClass('step-method');
                $('.step-method2').addClass('step-method-success');
                $('.step-submethod2').addClass('step-submethod');
                $('.step-method3').addClass('step-method');
                $('.step-method3').removeClass('step-method-success');
            }
            
            if(Number(nextstep) >= 3) {
                $('.nextstephidden').val(3);
            } else {
                if(Number(nextstep) == 2) {
                    if($('#first-name').val().trim() == "") {
                        $('#first-name').focus();
                        iziToast['warning']({
                            message: "Please fill in First Name field.",
                            position: "topRight"
                        });
                    } else if($('#last-name').val().trim() == "") {
                        $('#last-name').focus();
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
                    } else if($('#postal-code').val().trim() == "") {
                        $('#postal-code').focus();
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
                    } else if($('#email').val().trim() == "") {
                        $('#email').focus();
                        iziToast['warning']({
                            message: "Please fill in Email field.",
                            position: "topRight"
                        });
                    } else {
                        $('.nextstephidden').val(Number(Number(nextstep) + 1).toFixed(0));
                    }
                } else if(Number(nextstep) == 1) {
                    if($('select.shippingselectbtn > option:first-child:selected').length > 0) {
                        iziToast['warning']({
                            message: "@lang('Please select shipping costs.')",
                            position: "topRight"
                        });
                        $('.shippingselectbtn').focus();
                        $('.nextstephidden').val(1);
                    } else {
                        $('.nextstephidden').val(Number(Number(nextstep) + 1).toFixed(0));
                    }
                } else {
                    $('.nextstephidden').val(Number(Number(nextstep) + 1).toFixed(0));    
                }
            }
        });
    })(jQuery);
</script>
@endpush