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
    <div class="monitor-container mt-100">
        @foreach($winners as $winner)
            <div class="monitor-total-main">
                <div class="monitor-product-desktop-name">{{ $winner->product->name }}</div>
                <div class="monitor-main">
                    <div class="monitor-product-name">{{ $winner->product->name }}</div>
                    @if($winner->product_delivered == 0)
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 1)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 2)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step "></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 3)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3 packedactive">@lang('WITH CARE')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 4)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3 packedactive">@lang('WITH CARE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        @if($url == "")
                            <div class="monitor-step-bottom-text4">@lang('SHIPMENT TRACKING')</div>
                        @else
                            <div class="monitor-step-bottom-text4"><a href="{{ $url }}" class="getformatag" target="_blank">@lang('SHIPMENT TRACKING')</a></span>
                        @endif
                    @endif
                    <div class="monitor-product-x">
                        <span>x</span>
                    </div>
                </div>
            </div>
        @endforeach
        
        @foreach($auctionwinners as $winner)
            <div class="monitor-total-main">
                <div class="monitor-product-desktop-name">{{ $winner->auction->name }}</div>
                <div class="monitor-main">
                    <div class="monitor-product-name">{{ $winner->auction->name }}</div>
                    @if($winner->product_delivered == 0)
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 1)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 2)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step "></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3"></div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 3)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3 packedactive">@lang('WITH CARE')</div>
                        <div class="monitor-substep"></div>
                        <div class="monitor-step"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        <div class="monitor-step-bottom-text4"></div>
                    @elseif($winner->product_delivered == 4)
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text1">@lang('PAID')</div>
                        <div class="monitor-step-bottom-text1 paidactive">@lang('INVOICE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text2">@lang('PICKED')</div>
                        <div class="monitor-step-bottom-text2 pickedactive">@lang('REACH PACKING DEPT')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text3">@lang('PACKED')</div>
                        <div class="monitor-step-bottom-text3 packedactive">@lang('WITH CARE')</div>
                        <div class="monitor-substep substepactive"></div>
                        <div class="monitor-step stepactive"></div>
                        <div class="monitor-step-top-text4">@lang('IN TRANSIT')</div>
                        @if($url == "")
                            <div class="monitor-step-bottom-text4">@lang('SHIPMENT TRACKING')</div>
                        @else
                            <div class="monitor-step-bottom-text4"><a href="{{ $url }}" class="getformatag" target="_blank">@lang('SHIPMENT TRACKING')</a></span>
                        @endif
                    @endif
                    <div class="monitor-product-x">
                        <span>x</span>
                    </div>
                </div>
            </div>
        @endforeach
            
        <!-- Product -->
        <div class="modal fade" id="resultModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Result')</h5>
                    </div>
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
                        </div>
                        <div class="img_scale_slider" style="background: transparent;">
                          <input type="range" min="1" max="10" value="1" class="slider" id="myRange">
                          <p style="margin-top: 10px; background: transparent;">Zoom: <span id="demo" style="background: transparent;"></span></p>
                        </div>
                    </div>
                    <div class="modal_space_list"></div>
                    <div class="modal_img_list" style="background: transparent;">
                        <button class="btn text--danger close high_img_modal_close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true" style="font-size: 30px; color: #ea5455 !important; background: transparent; text-shadow: none; font-weight: 400;">&times;</span>
                            <span aria-hidden="true" style="font-size: 16px; color: #ea5455 !important; background: transparent; text-shadow: none; font-weight: 400;">Close Window</span>
                        </button>
                        <div class="paidsubimagelist" style="background: transparent;">
                            @foreach($winners as $winner)
                                @if($winner->paid_imageurl != "")
                                    @php
                                        $paidimagenum ++;
                                    @endphp
                                    @php
                                        $paidresult = explode( ',', $winner->paid_imageurl );
                                        if($loop->iteration == 1) {
                                            $firstpaidimageurl = getImage(imagePath()['product']['path'].'/'.$paidresult[0],imagePath()['product']['size']);    
                                        }
                                    @endphp
                                    @foreach($paidresult as $result)
                                        @if($result != "")
                                            <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                                <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            @foreach($auctionwinners as $winner)
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
                            @endforeach
                            @if($paidimagenum == 0 && $auctionpaidimagenum == 0)
                                <div style="display: inline-block; padding: 10px; background: transparent;">
                                    {{ $emptyMessage }}
                                </div>
                            @endif
                        </div>
                        <div class="pickedsubimagelist" style="background: transparent;">
                            @foreach($winners as $winner)
                                @if($winner->picked_imageurl != "")
                                    @php
                                        $pickedimagenum ++;
                                    @endphp
                                    @php
                                        $pickresult = explode( ',', $winner->picked_imageurl );
                                        if($loop->iteration == 1) {
                                            $firstpickedimageurl = getImage(imagePath()['product']['path'].'/'.$pickresult[0],imagePath()['product']['size']);
                                        }
                                    @endphp
                                    @foreach($pickresult as $result)
                                        @if($result != "")
                                            <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                                <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            @foreach($auctionwinners as $winner)
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
                            @endforeach
                            @if($pickedimagenum == 0 && $auctionpickedimagenum == 0)
                                <div style="display: inline-block; padding: 10px; background: transparent;">
                                    {{ $emptyMessage }}
                                </div>
                            @endif
                        </div>
                        <div class="packedsubimagelist" style="background: transparent;">
                            @foreach($winners as $winner)
                                @if($winner->packed_imageurl != "")
                                    @php
                                        $packedimagenum ++;
                                    @endphp
                                    @php
                                        $packresult = explode(',', $winner->packed_imageurl );
                                        if($loop->iteration == 1) {
                                            $firstpackedimageurl = getImage(imagePath()['product']['path'].'/'.$packresult[0], imagePath()['product']['size']);
                                        }
                                    @endphp
                                    @foreach($packresult as $result)
                                        @if($result != "")
                                            <div class="modal_img_data_item" style="text-align: center;position: relative; width: 60px; height: 45px; margin: 2px;display: inline-block;">
                                                <img id="image_replace_id{{ $loop->iteration }}" src="{{getImage(imagePath()['product']['path'].'/'.$result,imagePath()['product']['size'])}}"  class="replace-modal-image" style="width: 60px; height: 45px; cursor: pointer;" >
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                            @foreach($auctionwinners as $winner)
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
                            @endforeach
                            @if($packedimagenum == 0 && $auctionpackedimagenum == 0)
                                <div style="display: inline-block; padding: 10px; background: transparent;">
                                    {{ $emptyMessage }}
                                </div>
                            @endif
                        </div>
                        <div class="selected_img_download" style="background: transparent;">
                            <a class="a_download_img" target="_blank" download rel="nofollow" title="Download photo" href="#">
                                <span style="font-size: 16px; color: #ea5455 !important; background: transparent;">Download Photo</span>
                                <i class="fas fa-download" style="background: transparent;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <img id="img_hidden_size" style="display: none;">
        </div>
        
    </div>
@endsection

@push('style')
    <style>
        .monitor-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .monitor-iframe {
            display: none;
            position: absolute;
            width: 240px;
            left: 0;
            text-align: center;
            top: 80px;
        }
        
        .monitor-product-name {
            justify-content: flex-end;
            align-items: center;
            display: flex;
            padding: 0 20px;
            width: 110px;
            font-weight: bold;
            display: none;
        }
        
        .monitor-section-right {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 3px;
        }
        
        .monitor-section-right > i {
            font-size: 32px;
        }
        
        .monitor-product-x {
            justify-content: center;
            align-items: center;
            display: flex;
            padding: 0 25px;
            cursor: pointer;
        }
        
        .monitor-product-x > span {
            font-size: 30px;
            font-weight: bold;
            color: red !important;
        }
        
        .monitor-total-main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }
        
        .monitor-product-desktop-name {
            text-align: center;
            margin-bottom: 25px;
            font-size: 16px;
            font-weight: bold;
            border: 1px solid #fff;
            border-style: solid none;
            padding: 10px 0;
            width: 100%;
        }
        
        .monitor-main {
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            margin-bottom: 25px;
            width: 90%;
            height: 90px;
        }
        
        .monitor-section {
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            background-color: rgb(15, 15, 15);
            border: 1px solid #333;
            padding: 10px 20px;
            width: 230px;
        }
        
        .transitcomplete {
            position: relative;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            background-color: rgb(15, 15, 15);
            border: 1px solid #333;
            padding: 10px 20px;
            cursor: pointer;
            width: 230px;
        }
        
        .transitcomplete > div:nth-child(1) > span {
            font-size: 30px;
            font-weight: bolder;
        }
        
        .transitcomplete > div:nth-child(1) > i {
            color: #24bb48 !important;
            font-size: 28px;
        }
        
        .transitcomplete > div:nth-child(2) {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .transitcomplete > div:nth-child(2) > p {
            margin: 0 15px;
            padding: 0;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
        }
        
        .transitcomplete.active {
            background-color: #336699;
        }
        
        
        .monitor-section > div:nth-child(1) > span {
            font-size: 20px;
            font-weight: bolder;
        }
        
        .monitor-section > div:nth-child(1) > i {
            color: #24bb48 !important;
            font-size: 28px;
        }
        
        .monitor-section > div:nth-child(2) {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .monitor-section > div:nth-child(3) {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translate(100%, -50%);
            z-index: 1;
            width: 0;
            height: 0;
            border-top: 15px solid transparent;
            border-left: 15px solid #333;
            border-bottom: 15px solid transparent;
        }
        
        .monitor-section > div:nth-child(4) {
            position: absolute;
            top: 50%;
            right: 0;
            transform: translate(100%, -50%);
            z-index: 2;
            width: 0;
            height: 0;
            border-top: 13px solid transparent;
            border-left: 13px solid rgb(15, 15, 15);
            border-bottom: 13px solid transparent;
        }
        
        .monitor-section > div:nth-child(2) > p {
            margin: 0 15px;
            padding: 0;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 20px;
        }
        
        .monitor-section.active {
            background-color: #336699;
        }
        
        .monitor-section.active > div:nth-child(4) {
            border-left: 13px solid #336699;
        }
        
        @media (max-width: 1400px) {
            /*.monitor-main {*/
            /*    flex-direction: column;*/
            /*}*/
            
            /*.monitor-section-right > i {*/
            /*    transform: rotate(90deg);*/
            /*}*/
            
            /*.monitor-product-desktop-name {*/
            /*    display: none;*/
            /*}*/
            
            /*.monitor-product-name {*/
            /*    display: block;*/
            /*    justify-content: center;*/
            /*    margin-bottom: 50px;*/
            /*    align-items: center;*/
            /*    text-align: center;*/
            /*    border: 1px solid #fff;*/
            /*    border-style: solid none solid none;*/
            /*    width: 100%;*/
            /*    padding: 10px;*/
            /*}*/
        }
        
        p, li, span {
            color: #fff;
        }
        
        .monitor-iframe-close {
            position: absolute;
            right: 10px;
            background: transparent;
            cursor: pointer;
            top: 0;
        }
        
        .monitor-iframe-close > i {
            background: transparent;
            color: red !important;
        }
        
        .iframe-tag {
            display: none;
        }
        
        .getformatag {
            color: #28ff0e !important;
            font-size: 16px;
            cursor: pointer;
            text-transform: uppercase;
        }
        
        .getformatag:hover {
            text-decoration: underline;
        }
        
        .paidactive {
            cursor: pointer;
            color: #28ff0e !important;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .pickedactive {
            cursor: pointer;
            color: #28ff0e !important;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .packedactive {
            cursor: pointer;
            color: #28ff0e !important;
            font-size: 16px;
            text-transform: uppercase;
        }
        
        .paidactive:hover {
            text-decoration: underline;
        }
        
        .pickedactive:hover {
            text-decoration: underline;
        }
        
        .packedactive:hover {
            text-decoration: underline;
        }
        
        .high_img_modal_close {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0 0 20px 0;
            padding: 0;
            width: 100%;
            opacity: 1 !important;
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
        
        .modal_space_list {
            width: 3vw;
            background-color: transparent;
        }
        
        .img_zoom_sec {
            background: transparent;
            --x: 0px;
            --y: 0px;
            background-image: url('');
            /*background-size: 800px 600px;*/
            background-position: var(--x) var(--y);
            background-repeat: no-repeat;
        }
        
        @media (max-width: 1210px) {
            .img_zoom_sec_blk {
                width: 600px;
                height: 450px;
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
                top: 0;
                right: 50%;
                transform: translate(50%);
                width: fit-content;
                flex-direction: row;
            }
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
            margin-top: 15px;
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
        
        .monitor-section {
            background-color: #ddd !important;
        }
        
        .monitor-section > div:nth-child(4) {
            border-left: 13px solid #ddd !important;
        }
        
        .monitor-section.active > div:nth-child(4) {
            border-left: 13px solid #336699 !important;
        }
        
        .monitor-section.active {
            background-color: #336699 !important;
        }
        
        .noactiveptext {
            color: #000 !important;
        }
        
        .monitor-step {
            height: 25px;
            width: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            background-color: gray;
            border-radius: 50px;
        }
        
        .stepactive {
            background-color: #336699;
        }
        
        .monitor-substep {
            height: 5px;
            background-color: gray;
            font-weight: bold;
            flex: 1;
        }
        
        .substepactive {
            background-color: #336699;
        }
        
        .monitor-step-top-text1 {
            position: absolute;
            top: -5px;
            font-size: 20px;
            left: 10px;
            transform: translate(-50%, 0);
            font-weight: bold;
        }
        
        .monitor-step-bottom-text1 {
            position: absolute;
            top: 70px;
            font-size: 16px;
            left: 10px;
            transform: translate(-50%, 0);
            color: #28ff0e !important;
        }
        
        .monitor-step-top-text2 {
            position: absolute;
            top: -5px;
            font-size: 20px;
            left: calc(100% / 3 - 15px);
            transform: translate(-50%, 0);
            font-weight: bold;
        }
        
        .monitor-step-bottom-text2 {
            position: absolute;
            top: 70px;
            font-size: 16px;
            left: calc(100% / 3 - 15px);
            transform: translate(-50%, 0);
            color: #28ff0e !important;
        }
        
        .monitor-step-top-text3 {
            position: absolute;
            top: -5px;
            font-size: 20px;
            left: calc(100% / 3 * 2 - 49px);
            transform: translate(-50%, 0);
            font-weight: bold;
        }
        
        .monitor-step-bottom-text3 {
            position: absolute;
            top: 70px;
            font-size: 16px;
            left: calc(100% / 3 * 2 - 49px);
            transform: translate(-50%, 0);
            color: #28ff0e !important;
        }
        
        .monitor-step-top-text4 {
            position: absolute;
            top: -5px;
            font-size: 20px;
            right: 77px;
            transform: translate(50%, 0);
            font-weight: bold;
        }
        
        .monitor-step-bottom-text4 {
            position: absolute;
            top: 70px;
            font-size: 16px;
            right: 77px;
            transform: translate(50%, 0);
            color: #28ff0e !important;
        }
    </style>
@endpush

@push('script')
<script>
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
        $('.transitcomplete').click(function(){
            $('.monitor-iframe').css('display', 'block');
        });
        
        $(document).on('click', '.monitor-product-x', function () {
            $(this).closest('.monitor-total-main').remove();
        });
        
        $('.getformurlbtn').click(function() {
            var carrier = document.getElementById('formcarrier').value;
            var id = document.getElementById('paketid').value;
            var urls = "https://www.paketda.de/track-carrierdirect.php?id=" + id + "&carrier=" + carrier;
            var modal = $('#resultModal');
            document.getElementById('resulturl').value = urls;
            modal.modal('show');
            $('.getformatag').css('display', 'inline-block');
            $('.getformatag').attr('href', urls);
        });
        
        $('.paidactive').click(function() {
            var modal = $('#highImgModal');
            $('.paidsubimagelist').show();
            $('.pickedsubimagelist').hide();
            $('.packedsubimagelist').hide();
            $('.img_zoom_sec').css('background-image', 'url("{{ $firstpaidimageurl != '' ? $firstpaidimageurl : $auctionfirstpaidimageurl != '' ? $auctionfirstpaidimageurl : '' }}")');
            $('.a_download_img').attr('href', "{{ $firstpaidimageurl != '' ? $firstpaidimageurl : $auctionfirstpaidimageurl != '' ? $auctionfirstpaidimageurl : '' }}");
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
            img.src = "{{ $firstpaidimageurl != '' ? $firstpaidimageurl : $auctionfirstpaidimageurl != '' ? $auctionfirstpaidimageurl : '' }}";
            modal.modal('show');
        });
        
        $('.pickedactive').click(function() {
            var modal = $('#highImgModal');
            $('.paidsubimagelist').hide();
            $('.pickedsubimagelist').show();
            $('.packedsubimagelist').hide();
            $('.img_zoom_sec').css('background-image', 'url("{{ $firstpickedimageurl != '' ? $firstpickedimageurl : $auctionfirstpickedimageurl != '' ? $auctionfirstpickedimageurl : '' }}")');
            $('.a_download_img').attr('href', "{{ $firstpickedimageurl != '' ? $firstpickedimageurl : $auctionfirstpickedimageurl != '' ? $auctionfirstpickedimageurl : '' }}");
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
            img.src = "{{ $firstpickedimageurl != '' ? $firstpickedimageurl : $auctionfirstpickedimageurl != '' ? $auctionfirstpickedimageurl : '' }}";
            modal.modal('show');
        });
        
        $('.packedactive').click(function() {
            var modal = $('#highImgModal');
            $('.paidsubimagelist').hide();
            $('.pickedsubimagelist').hide();
            $('.packedsubimagelist').show();
            $('.img_zoom_sec').css('background-image', 'url("{{ $firstpackedimageurl != '' ? $firstpackedimageurl : $auctionfirstpackedimageurl != '' ? $auctionfirstpackedimageurl : '' }}")');
            $('.a_download_img').attr('href', "{{ $firstpackedimageurl != '' ? $firstpackedimageurl : $auctionfirstpackedimageurl != '' ? $auctionfirstpackedimageurl : '' }}");
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
            img.src = "{{ $firstpackedimageurl != '' ? $firstpackedimageurl : $auctionfirstpackedimageurl != '' ? $auctionfirstpackedimageurl : '' }}";
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
