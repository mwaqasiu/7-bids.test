@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="pt-5 pb-120">
    <div class="container">
        <div class="row" style="margin-bottom: 70px;">
            <div class="col-md-12">
                <div class="section__header" style="max-width: 100%; width: 100%;">
                    <div class="progress progress--bar" style="width: 60%; max-width: 60%; overflow: unset;">
                        <div class="progress-bar bg--base progress-bar-striped progress-bar-animated" style="left: 0; width: {{$charitydata->data_values->curamount/$charitydata->data_values->amount*100}}%"></div>
                        <div class="firstdiv">0</div>
                        <div class="curdiv">{{$charitydata->data_values->curamount}} {{ $general->cur_sym }}</div>
                        <div class="curdivstick"></div>
                        <div class="enddiv">
                            <i class="fas la-hand-holding-heart"></i>
                            {{$charitydata->data_values->amount}} {{ $general->cur_sym }}
                        </div>
                        <div class="enddivstick"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>
                    <img class="floatimg" src="{{getImage(imagePath()['product']['path'].'/'.$charitydata->data_values->url,imagePath()['product']['size'])}}" >
                </div>
                <div class="charitydesc">
                    @if(session()->get('lang') == "de")
                        @php echo $charitydata->data_values->german_description @endphp
                    @else
                        @php echo $charitydata->data_values->description @endphp
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<style>
    .floatimg {
        float: left;
        width: 300px;
        margin-right: 15px;
    }
    
    .firstdiv {
        position: absolute;
        bottom: 0;
        left: 0;
        transform: translateX(-50%);
    }
    
    .curdiv {
        position: absolute;
        top: 15px;
        left: {{$charitydata->data_values->curamount/$charitydata->data_values->amount*100}}%;
        transform: translateX(-50%);
        width: 70px;
    }
    
    .curdivstick {
        position: absolute;
        background-color: #fff;
        top: 5px;
        height: 16px;
        left: {{$charitydata->data_values->curamount/$charitydata->data_values->amount*100}}%;
        transform: translateX(-50%);
        width: 2px;
    }
    
    .enddiv {
        position: absolute;
        bottom: 15px;
        right: 0;
        transform: translateX(50%);
        display: flex;
        flex-direction: column;
    }
    
    .enddivstick {
        background-color: #fff;
        position: absolute;
        bottom: 5px;
        height: 16px;
        right: 0;
        width: 2px;
    }
    
    .charitydesc {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    .charitydesc span {
        color: rgba(255, 255, 255, 0.8) !important;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
    })(jQuery);
</script>
@endpush
