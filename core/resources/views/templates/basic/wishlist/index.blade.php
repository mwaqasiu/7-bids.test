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
                    <th scope="col">@lang('Ref')</th>
                    <th scope="col">@lang('Item')</th>
                    <th scope="col" style="width: 10%;">@lang('Seller')</th>
                    <th scope="col" style="width: 10%;">@lang('Price')</th>
                    <th scope="col" style="width: 20%;">@lang('Add To Shopping Cart')</th>
                    <th scope="col" style="width: 20%;">@lang("Remove From Wish List")</th>
                  </tr>
                </thead>
                <tbody>
                    @if(count($wishlists) == 0 && count($auctionwishlists) == 0)
                      <tr>
                         <td colspan="6" style="justify-content: center;">
                            <p style="font-size: 24px; padding: 50px 0;">@lang($emptyMessage)</p>
                        </td>
                      </tr>
                    @else
                        @foreach ($wishlists as $wishlist)
                          <tr>
                            <td data-label="@lang('Ref')">{{ $loop->index + 1 }}</td>
                            <td data-label="@lang('Item')" class="shoppingcartimagetd">
                                <div>
                                    <a href="{{ route('product.details', [$wishlist->pid, slug($wishlist->name)]) }}">
                                        <img id="image__{{ $wishlist->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$wishlist->image,imagePath()['product']['size'])}}" alt="wishlist" style="width: 75px; height: 75px; max-width: 150px !important;">
                                    </a>
                                    <span>{{ $wishlist->product->name }}</span>
                                </div>
                            </td>
                            <td data-label="@lang('Seller')">
                                @if($wishlist->admin_id)
                                    @php
                                        echo $wishlist->admin->username;
                                    @endphp
                                @endif
                                @if($wishlist->merchant_id)
                                    @php
                                        echo $wishlist->merchant->username;
                                    @endphp
                                @endif
                            </td>
                            <td data-label="@lang('Price')">
                                {{ $general->cur_sym }} {{ number_format($wishlist->price, 0) }}
                            </td>
                            <td data-label="@lang('Add To Shopping Cart')">
                                @if (auth()->check())
                                <a href="{{ route('user.shopping-cart.add', [$wishlist->product_id, auth()->user()->id, getenv('REMOTE_ADDR'), $wishlist->id]) }}">
                                    <i class="fas la-shopping-cart"></i>
                                </a>
                                @else
                                <a href="{{ route('user.shopping-cart.add', [$wishlist->product_id, 'empty', getenv('REMOTE_ADDR'), $wishlist->id]) }}">
                                    <i class="fas la-shopping-cart"></i>
                                </a>
                                @endif
                            </td>
                            <td data-label="@lang('Remove From Wish List')">
                              <button class="deleteShopBtn" style="background: transparent !important; padding: 0; color: white; ouline: none; border: none;" data-wishid="{{ $wishlist->id }}"><i class="fa fa-trash"></i></button>
                            </td>
                          </tr>
                        @endforeach
                        @foreach ($auctionwishlists as $auctionwishlist)
                          <tr>
                            <td data-label="@lang('Ref')">{{ $loop->index + count($wishlists) + 1 }}</td>
                            <td data-label="@lang('Item')">
                                <a href="{{ route('auction.details', [$auctionwishlist->aid, slug($auctionwishlist->name)]) }}">
                                    <img id="image__{{ $auctionwishlist->id }}" src="{{getImage(imagePath()['product']['path'].'/'.$auctionwishlist->image,imagePath()['product']['size'])}}" alt="auctionwishlist" style="width: 75px; height: 75px; max-width: 150px !important;">
                                </a>
                                <span>{{ $auctionwishlist->auction->name }}</span>
                            </td>
                            <td data-label="@lang('Description')">{{ strlen($auctionwishlist->long_description) > 100 ? substr($auctionwishlist->long_description, 0, 100)."..." : $auctionwishlist->long_description }}</td>
                            <td data-label="@lang('Current Price')">
                                {{ $general->cur_sym }} {{ number_format($auctionwishlist->price, 0) }}
                            </td>
                            <td></td>
                            <td data-label="@lang('Remove From Wish List')">
                              <button class="deleteAShopBtn" style="background: transparent !important; padding: 0; color: white; ouline: none; border: none;" data-awishid="{{ $auctionwishlist->id }}"><i class="fa fa-trash"></i></button>
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