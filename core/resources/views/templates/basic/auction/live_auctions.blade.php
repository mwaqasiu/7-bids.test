@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="product-section pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            @forelse ($auctions as $auction)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-10 mb-3">
                        <div class="slide-item">
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
                </div>
            @empty
                <div class="text-center">
                    <p>{{ __($emptyMessage) }}</p>
                </div>
            @endforelse
        </div>
        {{ $auctions->links() }}
    </div>
</section>
@endsection

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