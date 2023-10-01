@extends($activeTemplate.'layouts.frontend')

@section('content')
<!-- Wishlist -->
<section class="pt-5 pb-120">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="summary-block">
            <div>
                @lang('Your wish list contains:') <span style="font-weight: bold;">@php echo count($wishlists) + count($auctionwishlists) @endphp</span> @lang('item')
            </div>
            <table class="table cmn--table shopping-table">
                <thead>
                  <tr>
                    <th scope="col" style="text-transform: none;">@lang('ref')</th>
                    <th scope="col" style="text-transform: none;">@lang('item')</th>
                    <th scope="col" style="width: 10%; text-transform: none;">@lang('seller')</th>
                    <th scope="col" style="width: 10%; text-transform: none;">@lang('price')</th>
                    <th scope="col" style="width: 20%; text-transform: none;">@lang('add to shopping bag')</th>
                    <th scope="col" style="width: 20%; text-transform: none;">@lang("remove from wish list")</th>
                  </tr>
                </thead>
                <tbody>
                    @if(count($wishlists) == 0 && count($auctionwishlists) == 0)
                      <tr>
                         <td colspan="6" style="justify-content: center;">
                            <p style="font-size: 19px; padding: 50px 0;">@lang($emptyMessage)</p>
                        </td>
                      </tr>
                    @else
                        @foreach ($wishlists as $wishlist)
                          <tr>
                            <td data-label="@lang('ref')">{{ $loop->index + 1 }}</td>
                            <td data-label="@lang('item')" class="shoppingcartimagetd">
                                <div>
                                    <a href="{{ route('product.details', [$wishlist->pid, slug($wishlist->name)]) }}">
                                        <img id="image__{{ $wishlist->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$wishlist->image,imagePath()['product']['size'])}}" alt="wishlist" style="width: 75px; height: 75px; max-width: 150px !important;">
                                    </a>
                                    <span style="margin-left: 4px;">{{ $wishlist->product->name }}</span>
                                </div>
                            </td>
                            <td data-label="@lang('seller')">
                                @if($wishlist->admin_id)
                                    @php
                                        echo "7-BIDS";
                                    @endphp
                                @endif
                                @if($wishlist->merchant_id)
                                    @php
                                        echo $wishlist->merchant->username;
                                    @endphp
                                @endif
                            </td>
                            <td data-label="@lang('price')">
                                {{ $general->cur_sym }} {{ number_format($wishlist->price, 0) }}
                            </td>
                            <td data-label="@lang('add to shopping bag')">
                                @if (auth()->check())
                                <a href="{{ route('user.shopping-cart.add', [$wishlist->product_id, auth()->user()->id, getenv('REMOTE_ADDR'), $wishlist->id]) }}" style="color: #fff;">
                                    <i class="las la-shopping-bag" style="font-size: 16px;"></i>
                                </a>
                                @else
                                <a href="{{ route('user.shopping-cart.add', [$wishlist->product_id, 'empty', getenv('REMOTE_ADDR'), $wishlist->id]) }}" style="color: #fff;">
                                    <i class="las la-shopping-bag" style="font-size: 16px;"></i>
                                </a>
                                @endif
                            </td>
                            <td data-label="@lang('remove from wish list')">
                              <button class="deleteShopBtn" style="background: transparent !important; padding: 0; color: white; ouline: none; border: none;" data-wishid="{{ $wishlist->id }}">
                                  <i class="las la-trash-alt" style="font-size: 16px;"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                        @foreach ($auctionwishlists as $auctionwishlist)
                          <tr>
                            <td data-label="@lang('ref')">{{ $loop->index + count($wishlists) + 1 }}</td>
                            <td data-label="@lang('item')" class="shoppingcartimagetd">
                                <div>
                                    <a href="{{ route('auction.details', [$auctionwishlist->aid, slug($auctionwishlist->name)]) }}">
                                        <img id="image__{{ $auctionwishlist->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$auctionwishlist->image,imagePath()['product']['size'])}}" alt="auctionwishlist" style="width: 75px; height: 75px; max-width: 150px !important;">
                                    </a>
                                    <span style="margin-left: 4px;">{{ $auctionwishlist->auction->name }}</span>
                                </div>
                            </td>
                            <td data-label="@lang('seller')">
                                @lang("7-BIDS")
                                <!--{{ strlen($auctionwishlist->long_description) > 100 ? substr($auctionwishlist->long_description, 0, 100)."..." : $auctionwishlist->long_description }}-->
                            </td>
                            <td data-label="@lang('current price')">
                                {{ $general->cur_sym }} {{ number_format($auctionwishlist->price, 0) }}
                            </td>
                            <td>-</td>
                            <td data-label="@lang('remove from wish list')">
                              <button class="deleteAShopBtn" style="background: transparent !important; padding: 0; color: white; ouline: none; border: none;" data-awishid="{{ $auctionwishlist->id }}">
                                  <i class="las la-trash-alt" style="font-size: 16px;"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Wishlist -->

<div class="modal fade" id="auctionwishModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.auctionwishlist.delete') }}" method="POST">
                @csrf
                <input type="hidden" class="awish_id" name="awish_id" required>
                <div class="modal-body">
                    <h6 class="message"></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base awishdelbtn">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="wishModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert')</h5>
                <button class="btn text--danger modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.wishlist.delete') }}" method="POST">
                @csrf
                <input type="hidden" class="wish_id" name="wish_id" required>
                <div class="modal-body">
                    <h6 class="message"></h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--danger" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base wishdelbtn">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
    .shopping-table tbody tr td {
        vertical-align: middle;
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
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.shoppingcartimagetd').on('click', function() {
            $(this).find('a').toggle();
        });
        
        $('.deleteShopBtn').on('click', function() {
            var modal = $('#wishModal');
            var wishid = $(this).data('wishid');
            modal.find('.message').html('@lang("Are you sure to delete marketplace product?")');
            modal.find('.wish_id').val(wishid);
            // modal.modal('show');
            $('.wishdelbtn').click();
        });
        
        $('.deleteAShopBtn').on('click', function() {
            var modal = $('#auctionwishModal');
            var awishid = $(this).data('awishid');
            modal.find('.message').html('@lang("Are you sure to delete auction product?")');
            modal.find('.awish_id').val(awishid);
            // modal.modal('show');
            $('.awishdelbtn').click();
        });
    })(jQuery);
</script>
@endpush