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
            <li style="margin-right: 15px;">
                <a href="{{ route('user.bonus') }}">
                    <span style="border: 1px solid; padding: 4px 8px; color: #000;">
                        {{ $bonuscount }} pts
                    </span>
                </a>
            </li>
            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="lab la-facebook-messenger"></i>
                  @php
                    $answernotificationcount = $answerauctionNotifications->count() + $answerproductNotifications->count();
                  @endphp
                  @if($answernotificationcount > 0)
                    <span class="pulse--primary" style="color: #fff; border-radius: 9px; height: 18px; min-width: 18px; white-space: nowrap; width: auto; text-align: center; padding: 1px; background-color: #e91260; font-size: 11px; right: 0px; top: -5px;">{{ $answernotificationcount }}</span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 dropdown-menu-right" style="box-shadow: 0px 2px 13px -10px #cdd4e7 !important;">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Question & Answer')</span>
                    @if($answernotificationcount > 0)
                        <p>@lang('You have') {{ $answernotificationcount }} @lang('unread answer')</p>
                    @else
                        <p>@lang('No unread answer found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($answerauctionNotifications as $notification)
                    <a class="dropdown-menu__item answershowmodalitembtn" data-answer_id="{{ $notification->id }}" data-question="{{ $notification->question }}" data-answer="{{ $notification->answer }}">
                      <div class="navbar-notifi">
                        <div class="navbar-notifi__left bg--green b-radius--rounded">
                            <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                        </div>
                        <div class="navbar-notifi__right">
                          <h6 class="notifi__title">@lang('Seller replied')</h6>
                          <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                      </div><!-- navbar-notifi end -->
                    </a>
                    @endforeach
                    @foreach($answerproductNotifications as $notification)
                    <a class="dropdown-menu__item answershowmodalitembtn" data-answer_id="{{ $notification->id }}" data-question="{{ $notification->question }}" data-answer="{{ $notification->answer }}">
                      <div class="navbar-notifi">
                        <div class="navbar-notifi__left bg--green b-radius--rounded">
                            <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Product Image')">
                        </div>
                        <div class="navbar-notifi__right">
                          <h6 class="notifi__title">@lang('Seller replied')</h6>
                          <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                      </div><!-- navbar-notifi end -->
                    </a>
                    @endforeach
                  </div>
                  <div class="dropdown-menu__footer">
                    <a href="{{ route('user.notification.answers') }}" class="view-all-message">@lang('View all answers')</a>
                  </div>
                </div>
            </li>
            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="las la-bell"></i>
                  @if($userNotifications->count() > 0)
                    <span class="pulse--primary" style="color: #fff; border-radius: 9px; height: 18px; min-width: 18px; white-space: nowrap; width: auto; text-align: center; padding: 1px; background-color: #e91260; font-size: 11px; right: 0px; top: -5px;">{{ $userNotifications->count() }}</span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 dropdown-menu-right" style="box-shadow: 0px 2px 13px -10px #cdd4e7 !important;">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Notification')</span>
                    @if($userNotifications->count() > 0)
                        <p>@lang('You have') {{ $userNotifications->count() }} @lang('unread notification')</p>
                    @else
                        <p>@lang('No unread notification found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($userNotifications as $notification)
                        @if($notification->click_url == "")
                            <a href="{{ route('user.notification.productread', $notification->id) }}" class="dropdown-menu__item">
                              <div class="navbar-notifi">
                                <div class="navbar-notifi__left bg--green b-radius--rounded">
                                    @if($notification->auction)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                                    @elseif($notification->product)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                                    @endif
                                </div>
                                <div class="navbar-notifi__right">
                                  <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                  <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                              </div><!-- navbar-notifi end -->
                            </a>
                        @elseif($notification->click_url == "productchangingstatus")
                            <a href="{{ route('user.notification.productchangingstatusread', $notification->id) }}" class="dropdown-menu__item">
                              <div class="navbar-notifi">
                                <div class="navbar-notifi__left bg--green b-radius--rounded">
                                    @if($notification->auction)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                                    @elseif($notification->product)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                                    @endif
                                </div>
                                <div class="navbar-notifi__right">
                                  <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                  <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                              </div><!-- navbar-notifi end -->
                            </a>
                        @elseif($notification->click_url == "changingstatus")
                            <a href="{{ route('user.notification.changingstatusread', $notification->id) }}" class="dropdown-menu__item">
                              <div class="navbar-notifi">
                                <div class="navbar-notifi__left bg--green b-radius--rounded">
                                    @if($notification->auction)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                                    @elseif($notification->product)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                                    @endif
                                </div>
                                <div class="navbar-notifi__right">
                                  <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                  <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                              </div><!-- navbar-notifi end -->
                            </a>
                        @else
                            <a href="{{ route('user.notification.read',$notification->id) }}" class="dropdown-menu__item">
                              <div class="navbar-notifi">
                                <div class="navbar-notifi__left bg--green b-radius--rounded">
                                    @if($notification->auction)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->auction->image, null, true) }}" alt="@lang('Auction Image')">
                                    @elseif($notification->product)
                                    <img src="{{ getImage(imagePath()['product']['path'].'/'.@$notification->product->image, null, true) }}" alt="@lang('Auction Image')">
                                    @endif
                                </div>
                                <div class="navbar-notifi__right">
                                  <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                                  <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                </div>
                              </div><!-- navbar-notifi end -->
                            </a>
                        @endif
                    @endforeach
                  </div>
                  <div class="dropdown-menu__footer">
                    <a href="{{ route('user.notifications') }}" class="view-all-message">@lang('View all notifications')</a>
                  </div>
                </div>
            </li>
            <li>
                <button type="button" class="fullscreen-btn">
                    <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
                    <!--<i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>-->
                </button>
            </li>
            <li>
                <a href="{{ route('user.logout') }}" style="margin-left: 5px; display: flex; justify-content: center; align-items: center; background: #dc3545 !important; padding: 5px 10px; border-radius: 5px;">
                    <i class="las la-sign-out-alt" style="font-size: 20px; background: transparent;"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->


{{-- Show modal --}}
<div id="showModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title notification-modal-title">@lang('Question and Answer')</h5>
            </div>
            <form action="{{ route('user.clear.answer') }}" method="POST">
                @csrf
                <div class="modal-body">
                   <div style="padding: 0.75rem 1.25rem;">
                        <div style="margin-bottom: 10px; font-weight: bold;">
                            @lang('Question'):
                        </div>
                        <div id="question" class="question"></div>
                   </div>
                   <div style="padding: 0.75rem 1.25rem;">
                        <div style="margin-bottom: 10px; font-weight: bold;">
                            @lang('Answer'):
                        </div>
                        <div id="answer" class="answer"></div>
                   </div>
                   <input type="hidden" name="answer_id" id="answer_id" required />
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--success btn-block">@lang('OK')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.answershowmodalitembtn').on('click', function() {
            var data = $(this).data();
            var modal = $('#showModal');
            modal.find('#answer').text(data.answer);
            modal.find('#question').text(data.question);
            modal.find('#answer_id').val(data.answer_id);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
