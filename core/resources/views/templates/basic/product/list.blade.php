@extends($activeTemplate.'layouts.frontend')
@section('content')
<!-- Product -->
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="position-fixed position-absolute start-50 translate-middle-x d-lg-none" style="bottom: 63px; z-index: 999; background: none;">
            <div style="background: transparent !important;">
                <a href="#" class="cmn--btn filter-btn-sticky">Filter</a>
            </div>
        </div>

        @livewire('product.product-list')
    </div>
</section>
<!-- Product -->

@endsection
@push('style')
    <style>
        .ui-datepicker .ui-datepicker-prev,
        .ui-datepicker .ui-datepicker-next {
            color: #111;
            background-color: #fff;
            z-index: 11;
        }

        .ui-datepicker-prev {
            position: relative;
        }

        .ui-datepicker-prev::before {
            position: absolute;
            content: "\f104";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 900;
        }

        .ui-datepicker-next::before {
            position: absolute;
            content: "\f105";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 900;
        }

        .price-range {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
        }

        .price-range label {
            margin: 0;
            font-weight: 500;
            color: #fff !important;
        }

        .price-range input {
            height: unset;
            width: unset;
            background: transparent;
            border: none;
            text-align: right;
            font-weight: 500;
            color: #fff !important;
            padding-right: 0;
        }



        .widget .ui-state-default {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: block;
            border: none;
            background-color: $base-color !important;
            box-shadow: 0 9px 20px 0 rgba(22, 26, 57, 0.36);
            outline: none;
            cursor: pointer;
            top: -9px;
            position: absolute;
            z-index: 1;
        }

        .widget .ui-state-default::after {
            position: absolute;
            content: "";
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: $base-color;
            top: 3px;
            left: 3px;
            display: block;
        }

        .widget .ui-widget.ui-widget-content {
            position: relative;
            height: 3px;
            border: none;
            margin-bottom: 25px;
        }

        .widget .ui-widget.ui-widget-content::after {
            position: absolute;
            content: "";
            top: 0;
            left: 0;
            height: 3px;
            background: rgba($base-color, 0.3);
            width: calc(100%);
        }

        .delete-filter-list {
            font-size: 20px;
            line-height: 1;
            position: absolute;
            right: 20px;
            top: 30px;
            cursor: pointer;
        }

        .delete-filter-list > i {
            color: #fff;
        }
    </style>
@endpush
@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue.'css/jquery-ui.min.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue.'js/jquery-ui.min.js') }}"></script>
@endpush
