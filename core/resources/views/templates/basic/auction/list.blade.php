@extends($activeTemplate.'layouts.frontend')

@section('content')
<!-- Product -->
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="position-fixed position-absolute start-50 translate-middle-x d-lg-none" style="bottom: 63px; z-index: 10; background: none;">
            <div style="background: transparent !important;">
                <a href="#" class="cmn--btn filter-btn-sticky">Filter</a>
            </div>
        </div>

        <div class="row flex-wrap-reverse">
            <div class="col-lg-4 col-xl-3">
                <aside class="search-filter">
                    <div class="bg--section pb-5 pb-lg-0">
                        <div class="filter-widget d-block d-lg-none">
                            <span class="close-filter-bar d-lg-none">
                                <i class="las la-times"></i>
                            </span>
                        </div>

                        <div class="filter-widget pt-3 pb-2">
                            <h4 class="title m-0"><i class="las la-random"></i>@lang('Filter')</h4>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('by Category')</h6>
                            <form>
                                @foreach ($categories as $category)
                                    @if($allAuctions->where('category_id', $category->id)->count() != 0)
                                        <div class="form-check form--check">
                                            <input type="checkbox" class="form-check-input category-check" value="{{ $category->id }}" id="cate-{{ $category->id }}" {{ $category->id == request()->category_id ? 'checked':'' }}>
                                            <label  for="cate-{{ $category->id }}"><span>{{ __($category->name) }}</span><span>({{ $allAuctions->where('category_id', $category->id)->count() }})</span></label>
                                        </div>
                                    @endif
                                @endforeach
                            </form>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('by Price')</h6>

                            <div class="widget">
                                <div id="slider-range"></div>
                                <div class="price-range">
                                    <label for="amount">@lang('Price') :</label>
                                    <input type="text" id="amount" readonly>
                                    <input type="hidden" name="min_price" >
                                    <input type="hidden" name="max_price">
                                </div>
                            </div>
                        </div>

                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('by Feature')</h6>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="excellent" type="checkbox" name="radiofeature" id="radio4">
                                <label  for="radio4">@lang('Excellent Condition')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="certificated" type="checkbox" name="radiofeature" id="radio5">
                                <label  for="radio5">@lang('Certificated')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="mentioned" type="checkbox" name="radiofeature" id="radio6">
                                <label  for="radio6">@lang('Mentioned in Literature')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="limited" type="checkbox" name="radiofeature" id="radio7">
                                <label  for="radio7">@lang('Limited Edition')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="noteworthy" type="checkbox" name="radiofeature" id="radio8">
                                <label  for="radio8">@lang('Noteworthy Provenance')</label>
                            </div>
                        </div>

                        <div class="filter-widget">
                            <!--<span class="delete-filter-list delete-filter-time-list">-->
                            <!--    <i class="las la-trash"></i>-->
                            <!--</span>-->
                            <h6 class="sub-title">@lang('by Time')</h6>
                            <div class="form-check form--check">
                                <input class="form-check-input timing" value="arrivals" type="radio" name="radiotime" id="radio3">
                                <label  for="radio3">@lang('Upcoming Lots')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input timing" value="sold" type="radio" name="radiotime" id="radio9">
                                <label  for="radio9">@lang('Sold Items')</label>
                            </div>
                        </div>
                    </div>
                </aside>
                <div class="mini-banner-area mt-4">
                    <div class="mini-banner">
                        @php
                            showAd('370x670');
                        @endphp
                    </div>
                    <div class="mini-banner">
                        @php
                            showAd('370x300');
                        @endphp
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
{{--                    <h6 class="m-0 me-3">@lang('Sort by') <i class="las la-angle-double-down sort-btn-sticky"></i></h6>--}}
                <style>
                    .sort-fields {
                        margin-bottom: 15px !important;
                    }
                    @media (min-width: 768px) {
                        .sort-fields {
                            margin-bottom: -15px !important;
                        }
                    }
                </style>
                    <div class="sort-fields row">
                        <div class="col-md-3 col-6 form-check form--check" style="display: flex;">
                            <input class="form-check-input dateprice" style="display: none;" value="created_at" type="radio" name="dateprice" id="radio1">
                            <label style="margin-right: 10px;">@lang('Date')</label>
                            <div style="display: flex; flex-direction: row;">
                                <select class="date-select-sort form-select">
                                    <option value="asc">@lang('ascending')</option>
                                    <option value="desc">@lang('descending')</option>
                                </select>
                            </div>
                            <input class="form-check-input dateprice" style="display: none;" value="created_at_asc" type="radio" name="dateprice" id="radio10">
                        </div>
                        <div class="col-md-3 col-6 form-check form--check" style="display: flex;">
                            <input class="form-check-input dateprice" style="display: none;" value="price" type="radio" name="dateprice" id="radio2">
                            <label style="margin-right: 10px; margin-left: -1.5rem;">@lang('Price')</label>
                            <div style="display: flex; flex-direction: row;">
                                <select class="price-select-sort form-select">
                                    <option value="asc">@lang('ascending')</option>
                                    <option value="desc">@lang('descending')</option>
                                </select>
                            </div>
                            <input class="form-check-input dateprice" style="display: none;" value="price_desc" type="radio" name="dateprice" id="radio11">
                        </div>
                    </div>
                <div class="search-result" style="background: transparent;">
                    @include($activeTemplate.'auction.filtered', ['auctions'=> $auctions, 'wishlists' => $wishlists, 'winnertext' => $winnertext])
                </div>
            </div>
        </div>
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
            font-family: "Line Awesome Free";
            font-weight: 900;
        }

        .ui-datepicker-next::before {
            position: absolute;
            content: "\f105";
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-family: "Line Awesome Free";
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

        .ui-slider-range {
            height: 3px;
            background: $base-color;
            position: relative;
            z-index: 1;
        }

        .widget .ui-state-default {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: block;
            border: none;
            border-radius: 50%;
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

@push('script')
<script>
    (function ($) {
        "use strict";
        var page = 1;
        var search_key = @json(request()->search_key);
        var sorting =  {
            'excellent': false,
            'certificated': false,
            'mentioned': false,
            'limited': false,
            'noteworthy': false,
        };
        var timing = '';
        var dateprice = '';
        var categories = [];
        var minPrice = parseInt(`{{ $priceAuctions->min('price') }}`);
        var maxPrice = parseInt(`{{ $priceAuctions->max('price') }}`);

        $(document).on('click', '.page-link', function(e){
          e.preventDefault();
          page = $(this).attr('href').match(/page=([0-9]+)/)[1];
          loadSearch();
          $(".close-filter-bar").click();
        });

        $('.sorting').on('click', function(e){
            $('.sorting').each(function(){
                if($(this).val() == "excellent") {
                    if($(this).is(':checked')) {
                        sorting.excellent = true;
                    } else {
                        sorting.excellent = false;
                    }
                } else if($(this).val() == "certificated") {
                    if($(this).is(':checked')) {
                        sorting.certificated = true;
                    } else {
                        sorting.certificated = false;
                    }
                } else if($(this).val() == "mentioned") {
                    if($(this).is(':checked')) {
                        sorting.mentioned = true;
                    } else {
                        sorting.mentioned = false;
                    }
                } else if($(this).val() == "limited") {
                    if($(this).is(':checked')) {
                        sorting.limited = true;
                    } else {
                        sorting.limited = false;
                    }
                } else if($(this).val() == "noteworthy") {
                    if($(this).is(':checked')) {
                        sorting.noteworthy = true;
                    } else {
                        sorting.noteworthy = false;
                    }
                } else {
                    sorting = {
                        'excellent': false,
                        'certificated': false,
                        'mentioned': false,
                        'limited': false,
                        'noteworthy': false,
                    }
                }
            });

            console.log(sorting, "---");
            loadSearch();
            $(".close-filter-bar").click();
        });

        $('.timing').on('click', function(e) {
            timing = e.target.value;

            loadSearch();
            $(".close-filter-bar").click();
        });

        $('.dateprice').on('click', function(e){
            dateprice = e.target.value;
            loadSearch();
            $(".close-filter-bar").click();
        });

        $('.delete-filter-feature-list').on('click', function(e) {
            $('input[name="radiofeature"]').each(function(){
                $(this).prop('checked', false);
            });

            sorting =  {
                'excellent': false,
                'certificated': false,
                'mentioned': false,
                'limited': false,
                'noteworthy': false,
            };

            loadSearch();
            $(".close-filter-bar").click();
        });

        $('.delete-filter-time-list').on('click', function(e) {
            $('input[name="radiotime"]').each(function(){
                $(this).prop('checked', false);
            });

            timing = '';
            sorting =  {
                'excellent': false,
                'certificated': false,
                'mentioned': false,
                'limited': false,
                'noteworthy': false,
            };

            loadSearch();
            $(".close-filter-bar").click();
        });

        $( "#slider-range" ).slider({
            range: true,
            min: minPrice,
            max: maxPrice,
            values: [minPrice, maxPrice],
            slide: function (event, ui) {
                $("#amount").val("€" + ui.values[0] + " - €" + ui.values[1]);
                $('input[name=min_price]').val(ui.values[0]);
                $('input[name=max_price]').val(ui.values[1]);
            },

            change: function () {
                minPrice = $('input[name="min_price"]').val();
                maxPrice = $('input[name="max_price"]').val();

                $('.brand-filter input:checked').each(function () {
                    brand.push(parseInt($(this).attr('value')));
                });

                loadSearch();
                $(".close-filter-bar").click();
            }
        });
        $("#amount" ).val( "€ " + $( "#slider-range" ).slider( "values", 0 ) + " - € " + $( "#slider-range" ).slider( "values", 1 ));

        $('.category-check').click(function(e){
            categories = [];
            var categoryArr = $('.category-check:checked:checked');
                if(e.target.value == 'All'){
                    $('input:checkbox').not(this).prop('checked', false);
                    categories = [];
                    loadSearch();
                    $(".close-filter-bar").click();
                    return 0;
                }else{
                    $('#cate-00').prop('checked', false);
                }

            $.each(categoryArr, function (indexInArray, valueOfElement) {
                categories.push(valueOfElement.value);
            });

            loadSearch();
            $(".close-filter-bar").click();
        });

        $('.date-select-sort').on('change', function() {
            if($(this).val() == "asc") {
                $('#radio1').click();
            } else if($(this).val() == "desc") {
                $('#radio10').click();
            }
        });

        $('.price-select-sort').on('change', function() {
            if($(this).val() == "asc") {
                $('#radio2').click();
            } else if($(this).val() == "desc") {
                $('#radio11').click();
            }
        });

        function loadSearch(){
            // $("#overlay, #overlay2").fadeIn(300);
            $('.preloader').fadeIn(300);

            var url = `{{ route('auction.search.filter') }}`;
            var data = {'sorting': sorting, 'timing': timing, 'dateprice': dateprice, 'minPrice': minPrice, 'maxPrice': maxPrice, 'search_key':search_key, 'categories': categories, 'page': page }

            $.ajax({
                type: "GET",
                url: url,
                data: data,
                success: function (response) {
                    $('.search-result').html(response);
                    // $("#overlay, #overlay2").fadeOut(300);
                    $('.preloader').fadeOut(300);
                    runCountDown();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("Status: " + textStatus); alert("Error: " + errorThrown);
                }

            });
        }

        function runCountDown() {
            $('.countdown').each(function(){
            var date = $(this).data('date');
              $(this).countdown({
                date: date,
                offset: +6,
                day: 'Day',
                days: 'Days'
              });
           });
        }
    })(jQuery);
</script>
@endpush
