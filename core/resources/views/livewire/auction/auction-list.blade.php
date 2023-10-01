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
                    @foreach ($categories as $category)
                        <div class="form-check form--check">
                            <input type="checkbox" wire:model="searchByCategories" class="form-check-input category-check" value="{{ $category->id }}">
                            <label><span>{{ __($category->name) }}</span><span>({{ $category->auctions_count }})</span></label>
                        </div>
                    @endforeach
                </div>

{{--                <div class="filter-widget">--}}
{{--                    <span class="delete-filter-list delete-filter-time-list float-right" wire:click="resetPriceFilers">--}}
{{--                        <i class="las la-trash"></i>--}}
{{--                    </span>--}}
{{--                    <h6 class="sub-title">@lang('by Price')</h6>--}}
{{--                    <div class="widget">--}}
{{--                        <div id="slider-range" wire:ignore></div>--}}
{{--                        <div class="price-range">--}}
{{--                            <label for="amount">@lang('Price') :</label>--}}
{{--                            <input type="text" id="amount" value="{{"€" . $minPrice . " - €" .$maxPrice}}" readonly>--}}
{{--                            <input type="hidden" name="min_price" wire:model="minPrice">--}}
{{--                            <input type="hidden" name="max_price" wire:model="maxPrice">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="filter-widget">
                    <h6 class="sub-title">@lang('by Feature')</h6>
                    <div class="form-check form--check">
                        <input class="form-check-input sorting" value="ExcellentCondition" wire:model="searchByFeature" type="checkbox">
                        <label  for="radio4">@lang('Excellent Condition')</label>
                    </div>
                    <div class="form-check form--check">
                        <input class="form-check-input sorting" value="Certificated" wire:model="searchByFeature" type="checkbox">
                        <label  for="radio5">@lang('Certificated')</label>
                    </div>
                    <div class="form-check form--check">
                        <input class="form-check-input sorting" value="Literature" wire:model="searchByFeature" type="checkbox">
                        <label  for="radio6">@lang('Mentioned in Literature')</label>
                    </div>
                    <div class="form-check form--check">
                        <input class="form-check-input sorting" value="Edition" wire:model="searchByFeature" type="checkbox">
                        <label  for="radio7">@lang('Limited Edition')</label>
                    </div>
                    <div class="form-check form--check">
                        <input class="form-check-input sorting" value="Provenance" wire:model="searchByFeature" type="checkbox">
                        <label  for="radio8">@lang('Noteworthy Provenance')</label>
                    </div>
                </div>

                <div class="filter-widget">
                    <h6 class="sub-title">@lang('by Time')</h6>
                    <div class="form-check form--check">
                        <input class="form-check-input timing" wire:model="searchByNewArrivals" value="newArrivals" type="checkbox">
                        <label for="radio3">@lang('Upcoming Lots')</label>
                    </div>
                    <div class="form-check form--check">
                        <input class="form-check-input timing" wire:model="searchBySold" value="sold" type="checkbox">
                        <label  for="radio9">@lang('Sold Lots')</label>
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
        <div class="sort-fields d-md-flex" style="gap: 1.25rem;">
            <div class="ps-0 form-check form--check mb-2 mb-md-0" style="display: flex;">
                <input class="form-check-input dateprice" style="display: none;" value="created_at" type="radio" name="dateprice" id="radio1">
                <label style="width:5rem">@lang('Date')</label>
                <div style="width: 100%;">
                    <select class="date-select-sort form-select" wire:model="sortByDate">
                        <option value="">...</option>
                        <option value="created_at_asc">@lang('ascending')</option>
                        <option value="created_at_desc">@lang('descending')</option>
                    </select>
                </div>
                <input class="form-check-input dateprice" style="display: none;" value="created_at_asc" type="radio" name="dateprice" id="radio10">
            </div>
            <div class="ps-0 form-check form--check" style="display: flex;">
                <input class="form-check-input dateprice" style="display: none;" value="price" type="radio" name="dateprice" id="radio2">
                <label style="width:5rem">@lang('Price')</label>
                <div style="width: 100%;">
                    <select class="price-select-sort form-select" wire:model="sortByPrice">
                        <option value="">...</option>
                        <option value="price_asc">@lang('ascending')</option>
                        <option value="price_desc">@lang('descending')</option>
                    </select>
                </div>
                <input class="form-check-input dateprice" style="display: none;" value="price_desc" type="radio" name="dateprice" id="radio11">
            </div>
        </div>
        <div class="search-result" style="background: transparent;">
            @include($activeTemplate.'auction.filtered', ['auctions'=> $auctions, 'wishlists'=>$wishlists])
        </div>
    </div>
</div>
@push('script')

    <script>
        (function ($) {
            "use strict";

            let minPrice = parseInt('{{$minPrice}}');
            let maxPrice = parseInt('{{$maxPrice}}');

            $( "#slider-range" ).slider({
                range: true,
                min: minPrice,
                max: maxPrice,
                values: [minPrice, maxPrice],
                slide: function (event, ui) {
                    $("#amount").val("€" + ui.values[0] + " - €" + ui.values[1]);
                    $('input[name=min_price]').val(ui.values[0]);
                    $('input[name=max_price]').val(ui.values[1]);
                    Livewire.emit('updatedSlideRange', [ui.values[0], ui.values[1]]);
                },

                change: function () {
                    minPrice = $('input[name="min_price"]').val();
                    maxPrice = $('input[name="max_price"]').val();
                    $( "#slider-range" ).slider("option", "max", maxPrice).slider("option", "min", minPrice);
                }
            });
            
            // Livewire component JavaScript code
            document.addEventListener("livewire:load", function () {
                Livewire.hook('message.processed', function (el, component) {
                    runCountDown();
                });
            });

            function runCountDown() {
                $('.countdown').each(function(){
                    const date = $(this).data('date');
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
