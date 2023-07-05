@extends($activeTemplate.'layouts.frontend')

@section('content')
<!-- Product -->
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="mb-4 d-lg-none">
            <div class="filter-btn ms-auto">
                <i class="las la-filter"></i>
            </div>
        </div>
        <div class="row flex-wrap-reverse">
            <div class="col-lg-4 col-xl-3">
                <aside class="search-filter">
                    <div class="bg--section pb-5 pb-lg-0">
                        <div class="filter-widget">
                            <span class="close-filter-bar d-lg-none">
                                <i class="las la-times"></i>
                            </span>
                            <h6 class="sub-title">@lang('Sort by')</h6>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="created_at" type="radio" name="radio" id="radio1" checked>
                                <label  for="radio1">@lang('Date')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="price" type="radio" name="radio" id="radio2">
                                <label  for="radio2">@lang('Price')</label>
                            </div>
                        </div>
                        
                        <div class="filter-widget pt-3 pb-2">
                            <h4 class="title m-0"><i class="las la-random"></i>@lang('Filters')</h4>
                        </div>
                        
                        <div class="filter-widget">
                            <h6 class="sub-title">@lang('by Category')</h6>
                            <form>
                                @php
                                    $numcount = 0;
                                @endphp
                                @foreach ($categories as $category)
                                    @php
                                        $numcount += $allProducts->where('category_id', $category->id)->count() + $allAuctions->where('category_id', $category->id)->count();
                                    @endphp
                                @endforeach
                                @foreach ($categories as $category)
                                    @if($numcount != 0)
                                        @if($allProducts->where('category_id', $category->id)->count() + $allAuctions->where('category_id', $category->id)->count() != 0)
                                            <div class="form-check form--check">
                                                <input type="checkbox" class="form-check-input category-check" value="{{ $category->id }}" id="cate-{{ $category->id }}" {{ $category->id == request()->category_id ? 'checked':'' }}>
                                                <label  for="cate-{{ $category->id }}"><span>{{ __($category->name) }}</span><span>({{ $allProducts->where('category_id', $category->id)->count() + $allAuctions->where('category_id', $category->id)->count() }})</span></label>
                                            </div>
                                        @endif
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
                                <input class="form-check-input sorting" value="arrivals" type="radio" name="radio" id="radio3">
                                <label  for="radio3">@lang('New Arrivals')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="excellent" type="radio" name="radio" id="radio4">
                                <label  for="radio4">@lang('Excellent Condition')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="certificated" type="radio" name="radio" id="radio5">
                                <label  for="radio5">@lang('Certificated')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="mentioned" type="radio" name="radio" id="radio6">
                                <label  for="radio6">@lang('Mentioned in Literature')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="limited" type="radio" name="radio" id="radio7">
                                <label  for="radio7">@lang('Limited Edition')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="noteworthy" type="radio" name="radio" id="radio8">
                                <label  for="radio8">@lang('Noteworthy Provenance')</label>
                            </div>
                            <div class="form-check form--check">
                                <input class="form-check-input sorting" value="sold" type="radio" name="radio" id="radio9">
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
            <div class="col-lg-8 col-xl-9 search-result">
                @include($activeTemplate.'search.filtered', ['products'=> $products, 'auctions'=>$auctions])
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
            margin-right: 20px;
            margin-bottom: 25px;
        }
        .widget .ui-widget.ui-widget-content::after {
            position: absolute;
            content: "";
            top: 0;
            left: 0;
            height: 3px;
            background: rgba($base-color, 0.3);
            width: calc(100% + 20px);
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
        var sorting = '';
        var categories = [];
        var minPrice = parseInt(`{{ $allProducts->min('price') }}`) > parseInt(`{{ $allAuctions->min('price') }}`) ? parseInt(`{{ $allAuctions->min('price') }}`) : parseInt(`{{ $allProducts->min('price') }}`);
        var maxPrice = parseInt(`{{ $allProducts->max('price') }}`) > parseInt(`{{ $allAuctions->max('price') }}`) ? parseInt(`{{ $allProducts->max('price') }}`) : parseInt(`{{ $allAuctions->max('price') }}`);

        $(document).on('click', '.page-link', function(e){
          e.preventDefault();
          page = $(this).attr('href').match(/page=([0-9]+)/)[1];;
          loadSearch();
          $('.close-filter-bar').click();
        });

        $('.sorting').on('click', function(e){
            sorting = e.target.value;
            loadSearch();
            $('.close-filter-bar').click();
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
                $('.close-filter-bar').click();
            }
        });
        $("#amount" ).val( "€" + $( "#slider-range" ).slider( "values", 0 ) + " - €" + $( "#slider-range" ).slider( "values", 1 ));

        $('.category-check').click(function(e){
            categories = [];
            var categoryArr = $('.category-check:checked:checked');
                if(e.target.value == 'All'){
                    $('input:checkbox').not(this).prop('checked', false);
                    categories = [];
                    loadSearch();
                    $('.close-filter-bar').click();
                    return 0;
                }else{
                    $('#cate-00').prop('checked', false);
                }
          
            $.each(categoryArr, function (indexInArray, valueOfElement) {
                categories.push(valueOfElement.value);
            });
            
            loadSearch();
            $('.close-filter-bar').click();
        });


        function loadSearch(){
            // $("#overlay, #overlay2").fadeIn(300);
            $('.preloader').fadeIn(300);

            var url = `{{ route('product.asearch.filter') }}`;
            var data = {'sorting': sorting, 'minPrice': minPrice, 'maxPrice': maxPrice, 'search_key':search_key, 'categories': categories, 'page': page }

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
