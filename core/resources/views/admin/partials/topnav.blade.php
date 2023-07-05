<!-- navbar-wrapper start -->
<nav class="navbar-wrapper">
    <form class="navbar-search" onsubmit="return false;">
        <button type="submit" class="navbar-search__btn">
            <i class="las la-search"></i>
        </button>
        <input type="search" name="navbar-search__field" id="navbar-search__field"
               placeholder="@lang('Search')">
        <button type="button" class="navbar-search__close"><i class="las la-times"></i></button>
        <div id="navbar_search_result_area">
            <ul class="navbar_search_result"></ul>
        </div>
    </form>

    <div class="navbar__left">
        <button class="res-sidebar-open-btn"><i class="las la-bars"></i></button>
        <button type="button" class="fullscreen-btn">
            <i class="fullscreen-open las la-compress" onclick="openFullscreen();"></i>
            <i class="fullscreen-close las la-compress-arrows-alt" onclick="closeFullscreen();"></i>
        </button>
    </div>

    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="las la-bell text--primary"></i>
                  @if($adminanswerNotifications->count() > 0)
                    <span class="pulse--primary" style="color: #fff; border-radius: 9px; height: 18px; min-width: 18px; white-space: nowrap; width: auto; text-align: center; padding: 1px; background-color: #e91260; font-size: 11px; right: 0px; top: -5px;">{{ $adminanswerNotifications->count() }}</span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 box--shadow1 dropdown-menu-right">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Notification')</span>
                    @if($adminanswerNotifications->count() > 0)
                        <p>@lang('You have') {{ $adminanswerNotifications->count() }} @lang('unread notification')</p>
                    @else
                        <p>@lang('No unread notification found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($adminanswerNotifications as $notification)
                        <a class="dropdown-menu__item adminanswernotificationitem" data-notification_id="{{ $notification->id }}" data-question_id="{{ $notification->question_id }}" data-auction_id="{{ $notification->auction_id }}" data-product_id="{{ $notification->product_id }}" data-question="{{ $notification->question }}" data-title="{{ $notification->title }}" data-user_id="{{ $notification->user->id }}">
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
                          </div><!-- navbar-notifi end -->
                        </a>
                    @endforeach
                  </div>
                </div>
            </li>
            <li>
                <a href="{{ route('admin.reset.user.language') }}" onclick="if (confirm('Are you sure?')){return true;}else{event.preventDefault();};" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                    @lang('Reset Language')
                </a>
            </li>
            <li>
                <a href="{{ route('home') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                    <i class="dropdown-menu__icon las la-home"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.logout') }}" class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                    <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                    <span class="dropdown-menu__caption">@lang('Logout')</span>
                </a>
            </li>
            <li>
                <button type="button" class="navbar-search__btn-open">
                    <i class="las la-search"></i>
                </button>
            </li>
            <li class="dropdown">
                <button type="button" class="primary--layer" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <i class="las la-bell text--primary"></i>
                  @if($adminNotifications->count() > 0)
                    <span class="pulse--primary" style="color: #fff; border-radius: 9px; height: 18px; min-width: 18px; white-space: nowrap; width: auto; text-align: center; padding: 1px; background-color: #e91260; font-size: 11px; right: 0px; top: -5px;">{{ $adminNotifications->count() }}</span>
                  @endif
                </button>
                <div class="dropdown-menu dropdown-menu--md p-0 border-0 box--shadow1 dropdown-menu-right">
                  <div class="dropdown-menu__header">
                    <span class="caption">@lang('Notification')</span>
                    @if($adminNotifications->count() > 0)
                        <p>@lang('You have') {{ $adminNotifications->count() }} @lang('unread notification')</p>
                    @else
                        <p>@lang('No unread notification found')</p>
                    @endif
                  </div>
                  <div class="dropdown-menu__body">
                    @foreach($adminNotifications as $notification)
                        <a href="{{ route('admin.notification.read',$notification->id) }}" class="dropdown-menu__item">
                          <div class="navbar-notifi">
                            <div class="navbar-notifi__left bg--green b-radius--rounded">
                                @if($notification->user)
                                    <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'.@$notification->user->image, null, true)}}" alt="@lang('Profile Image')">
                                @else
                                    <img src="{{ getImage(imagePath()['profile']['merchant']['path'].'/'.@$notification->merchant->image, null, true)}}" alt="@lang('Profile Image')">
                                @endif
                            </div>
                            <div class="navbar-notifi__right">
                              <h6 class="notifi__title">{{ __($notification->title) }}</h6>
                              <span class="time"><i class="far fa-clock"></i> {{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                          </div><!-- navbar-notifi end -->
                        </a>
                    @endforeach
                  </div>
                  <div class="dropdown-menu__footer">
                    <a href="{{ route('admin.notifications') }}" class="view-all-message">@lang('View all notification')</a>
                  </div>
                </div>
            </li>
            <li class="dropdown">
                <button type="button" class="" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                  <span class="navbar-user">
                    <span class="navbar-user__thumb"><img src="{{ getImage('assets/admin/images/profile/'. auth()->guard('admin')->user()->image) }}" alt="image"></span>
                    <span class="navbar-user__info">
                      <span class="navbar-user__name">{{auth()->guard('admin')->user()->username}}</span>
                    </span>
                    <span class="icon"><i class="las la-chevron-circle-down"></i></span>
                  </span>
                </button>
                <div class="dropdown-menu dropdown-menu--sm p-0 border-0 box--shadow1 dropdown-menu-right">
                    <a href="{{ route('admin.profile') }}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-user-circle"></i>
                        <span class="dropdown-menu__caption">@lang('Profile')</span>
                    </a>
                    <a href="{{route('admin.password')}}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-key"></i>
                        <span class="dropdown-menu__caption">@lang('Password')</span>
                    </a>
                    <a href="{{ route('admin.logout') }}"
                       class="dropdown-menu__item d-flex align-items-center px-3 py-2">
                        <i class="dropdown-menu__icon las la-sign-out-alt"></i>
                        <span class="dropdown-menu__caption">@lang('Logout')</span>
                    </a>
                </div>
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
            <form method="POST" action="{{ route('admin.question.answering') }}">
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

@push('script')
<script>
    (function ($) {
        "use strict";
        $('.adminanswernotificationitem').on('click', function() {
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