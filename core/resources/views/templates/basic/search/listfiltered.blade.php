<div id="overlay">
    <div class="cv-spinner">
        <span class="spinner"></span>
    </div>
</div>
<div class="overlay-2" id="overlay2"></div>
<div class="d-flex flex-wrap justify-content-sm-between justify-content-center mb-4" style="gap:15px 30px">
    <p class="mb-0"></p>
    <p class="mb-0">@lang('Results Found'): <span>{{ $products->total() + $auctions->total() }}</span></p>
</div>
<div class="row g-4">
    @forelse ($products as $product)
        <div class="col-sm-6 col-xl-4">
            <div class="auction__item bg--body">
                <div class="auction__item-thumb">
                    <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">
                        <img src="{{getImage(imagePath()['product']['path'].'/thumb_'.$product->image,imagePath()['product']['thumb'])}}" alt="auction">
                    </a>
                    @if (auth()->check())
                        <a class="item_heart_icon" href="{{ route('user.wishlist.add', [$product->id, auth()->user()->id, getenv('REMOTE_ADDR')]) }}" title="add to wishlist">
                            <span><i class="far la-heart"></i></span>
                        </a>
                    @else
                        <a class="item_heart_icon" href="{{ route('user.wishlist.add', [$product->id, 'empty', getenv('REMOTE_ADDR')]) }}" title="add to wishlist">
                            <span><i class="far la-heart"></i></span>
                        </a>
                    @endif
                </div>
                <div class="auction__item-content">
                    <h6 class="auction__item-title">
                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}">{{ __($product->name) }}</a>
                    </h6>
                    <div class="auction__item-countdown">
                        @php
                            $startTimeStamp = strtotime($product->started_at);
                            $endTimeStamp = strtotime($product->expired_at);
                            $timeDiff = abs($endTimeStamp - $startTimeStamp);
                            $numberDays = $timeDiff/86400;
                            $numberDays = intval($numberDays);
                        @endphp
                        <div class="inner__grp">
                            @if($numberDays < 29)
                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
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
                            @else
                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
                                    <li>
                                        <span class="">&nbsp;</span>
                                    </li>
                                </ul>
                            @endif
                            <div class="total-price">
                                {{ $general->cur_sym }}{{ showAmount($product->price) }}
                            </div>
                            @if($numberDays >= 29)
                                <ul class="countdown" data-date="{{ showDateTime($product->expired_at, 'm/d/Y H:i:s') }}">
                                    <li>
                                        <span class="">&nbsp;</span>
                                    </li>
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="auction__item-footer">
                        <a href="{{ route('product.details', [$product->id, slug($product->name)]) }}" class="cmn--btn w-100">@lang('Details')</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            {{ __($emptyPMessage) }}
        </div>
    @endforelse
    @forelse ($auctions as $auction)
        <div class="col-sm-6 col-xl-4">
            <div class="auction__item bg--body">
                <div class="auction__item-thumb">
                    <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}">
                        <img src="{{getImage(imagePath()['product']['path'].'/thumb_'.$auction->image,imagePath()['product']['thumb'])}}" alt="auction">
                    </a>
                    @if (auth()->check())
                        <a class="item_heart_icon" href="{{ route('user.auctionwishlist.add', [$auction->id, auth()->user()->id, getenv('REMOTE_ADDR')]) }}" title="add to wishlist">
                            <span><i class="far la-heart"></i></span>
                        </a>
                    @else
                        <a class="item_heart_icon" href="{{ route('user.auctionwishlist.add', [$auction->id, 'empty', getenv('REMOTE_ADDR')]) }}" title="add to wishlist">
                            <span><i class="far la-heart"></i></span>
                        </a>
                    @endif
                </div>
                <div class="auction__item-content">
                    <h6 class="auction__item-title">
                        <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}">{{ __($auction->name) }}</a>
                    </h6>
                    <div class="auction__item-countdown">
                        <div class="inner__grp">
                            <ul class="countdown" data-date="{{ showDateTime($auction->expired_at, 'm/d/Y H:i:s') }}">
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
                            <div class="total-price">
                                {{ $general->cur_sym }}{{ showAmount($auction->price) }}
                            </div>
                        </div>
                    </div>
                    <div class="auction__item-footer">
                        <a href="{{ route('auction.details', [$auction->id, slug($auction->name)]) }}" class="cmn--btn w-100">@lang('Details')</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            {{ __($emptyAMessage) }}
        </div>
    @endforelse
</div>

{{ $products->links() }}
{{ $auctions->links() }}

@push('style')
    <style>
        .item_heart_icon {
            position: absolute;
            right: 30px;
            top: 30px;
            background-color: transparent;
            display: flex;
            align-items: center;
            color: #fff;
        }

        .item_heart_icon > span {
            background-color: transparent;
        }

        .item_heart_icon > span > i {
            color: #e9ba17 !important;
            transition: 0.3s color ease-in-out;
        }
    </style>
@endpush

