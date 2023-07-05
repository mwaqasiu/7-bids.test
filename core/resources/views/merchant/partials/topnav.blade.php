<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    <form class="navbar-search" onsubmit="return false;">
        <button type="submit" class="navbar-search__btn">
            <i class="las la-search"></i>
        </button>
        <input type="search" name="navbar-search__field" id="navbar-search__field" placeholder="@lang('Search')">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>

        <div id="navbar_search_result_area">
            <ul class="navbar_search_result"></ul>
        </div>
    </form>
    
    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="las la-bell text--primary"></i>
                  @if($sellerNotifications->count() > 0)
                    <span class="pulse--primary" style="color: #fff; border-radius: 9px; height: 18px; min-width: 18px; white-space: nowrap; width: auto; text-align: center; padding: 1px; background-color: #e91260; font-size: 11px; right: 0px; top: -5px;">{{ $sellerNotifications->count() }}</span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 dropdown-menu-right" style="box-shadow: 0px 2px 13px -10px #cdd4e7 !important; right: unset; left: auto;">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Notification')</span>
                    @if($sellerNotifications->count() > 0)
                        <p>@lang('You have') {{ $sellerNotifications->count() }} @lang('unread notification')</p>
                    @else
                        <p>@lang('No unread notification found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($sellerNotifications as $notification)
                        <a class="dropdown-menu__item sellernotificationitem" data-notification_id="{{ $notification->id }}" data-question_id="{{ $notification->question_id }}" data-auction_id="{{ $notification->auction_id }}" data-product_id="{{ $notification->product_id }}" data-question="{{ $notification->question }}" data-title="{{ $notification->title }}" data-user_id="{{ $notification->user->id }}">
                          <div class="navbar-notifi">
                            <div class="navbar-notifi__left bg--green b-radius--rounded">
                                @if($notification->auction_id)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                                @endif
                                @if($notification->product_id)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Product Image')">
                                @endif
                            </div>
                            <div class="navbar-notifi__right">
                              <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                              <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                          </div>
                        </a>
                    @endforeach
                  </div>
                </div>
            </li>
            <li>
                <a href="{{ route('merchant.product.auction.create') }}" style="display: flex; justify-content: center; align-items: flex-start; padding-right: 7px; margin-right: 5px; position: relative;">
                    <i class="las la-gavel" style="font-size: 18px;"></i>
                    <div class="plus-icon-hoz"></div>
                    <div class="plus-icon-ver"></div>
                </a>
            </li>
            <li>
                <a href="{{ route('merchant.product.create') }}" style="display: flex; justify-content: center; align-items: flex-start; padding-right: 7px; margin-right: 5px; position: relative;">
                    <i class="las la-shopping-cart" style="font-size: 18px;"></i>
                    <div class="plus-icon-hoz"></div>
                    <div class="plus-icon-ver"></div>
                </a>
            </li>
            <li>
                <button class="res-sidebar-open-btn"><i class="las la-bars"></i></button>
                <button type="button" class="fullscreen-btn">
                    <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
                    <!--<i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>-->
                </button>
            </li>
            <li>
                <a href="{{ route('merchant.logout') }}" style="display: flex; justify-content: center; align-items: center; margin-left: 10px; background: #dc3545 !important; padding: 5px 10px; border-radius: 5px;">
                    <i class="las la-sign-out-alt" style="margin-right: 5px; font-size: 20px; background: transparent;"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->

{{-- BId modal --}}
<div id="questionModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title notification-modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('merchant.question.answering') }}">
                @csrf
                <div class="modal-body">
                   <div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="notification_question"></span>
                            </li>
                        </ul>
                   </div>
                   <div style="padding: 0.75rem 1.25rem;">
                        <div>
                            Answer:
                        </div>
                        <div>
                            <Textarea name="answer" id="answer" required ></Textarea>
                        </div>
                   </div>
                   <input type="hidden" id="notification_id" name="notification_id" value="" />
                   <input type="hidden" id="question_id" name="question_id" value="" required />
                   <input type="hidden" id="auction_id" name="auction_id" value="" />
                   <input type="hidden" id="product_id" name="product_id" value="" />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--success btn-block">@lang('Send Answer')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('style')
<style>
    .plus-icon-hoz {
        position: absolute;
        right: 0;
        top: 3px;
        width: 8px;
        height: 2px;
        background: #dc3545 !important;
        z-index: 10;
    }
    
    .plus-icon-ver {
        position: absolute;
        right: 3px;
        top: 0;
        width: 2px;
        height: 8px;
        background: #dc3545 !important;
        z-index: 10;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.sellernotificationitem').on('click', function() {
            var data = $(this).data();
            var modal = $('#questionModal');
            modal.find('.notification-modal-title').text(data.title);
            modal.find('.notification_question').text(data.question);
            modal.find('#notification_id').val(data.notification_id);
            modal.find('#auction_id').val(data.auction_id);
            modal.find('#product_id').val(data.product_id);
            modal.find('#question_id').val(data.question_id);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush