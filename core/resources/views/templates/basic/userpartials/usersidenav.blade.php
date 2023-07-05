<div class="sidebar {{ sidebarVariation()['selector'] }} {{ sidebarVariation()['sidebar'] }} {{ @sidebarVariation()['overlay'] }} {{ @sidebarVariation()['opacity'] }}"
    data-background="{{ getImage('assets/admin/images/sidebar/2.jpg','400x800') }}">
    <button class="res-sidebar-close-btn" style="background: #001329;"><i class="las la-times" style="background-color: transparent !important;"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a href="{{ route('home') }}" class="sidebar__main-logo"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}"
                    alt="@lang('image')"></a>
            <a href="{{ route('home') }}" class="sidebar__logo-shape"><img
                    src="{{ getImage(imagePath()['logoIcon']['path'] .'/favicon.png') }}"
                    alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('user.home') }} {{ menuActive('user.checkout') }}">
                    <a href="{{ route('user.home') }}" class="nav-link ">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Buyer Dashboard')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.leadingbid.history') }}">
                    <a href="{{ route('user.leadingbid.history') }}" class="nav-link ">
                        <i class="menu-icon las la-bolt"></i>
                        <!--<svg class="menu-icon" version="1.0" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 144.000000 144.000000" preserveAspectRatio="xMidYMid meet">  <g transform="translate(0.000000,144.000000) scale(0.100000,-0.100000)" fill="rgba(255,255,255,0.9)" stroke="none"> <path d="M565 1293 c-66 -80 -140 -170 -165 -198 -25 -29 -65 -76 -90 -105 -55 -65 -78 -106 -64 -120 6 -6 67 -11 136 -13 70 -1 130 -6 134 -10 5 -4 10 -49 12 -100 1 -51 5 -98 7 -104 6 -18 340 -18 351 -1 5 7 9 53 11 103 2 69 7 93 19 102 11 8 58 12 132 12 64 0 119 4 125 10 15 15 -7 58 -56 113 -109 122 -367 436 -367 448 0 5 -15 10 -32 10 -30 -1 -44 -14 -153 -147z"></path> <path d="M535 480 c-8 -13 -8 -137 0 -150 9 -14 341 -14 350 1 12 19 15 120 5 140 -10 18 -23 19 -180 19 -97 0 -171 -4 -175 -10z"></path> <path d="M533 169 c-15 -15 -4 -130 15 -151 13 -16 35 -18 162 -18 127 0 149 2 162 18 19 21 30 136 15 151 -15 15 -339 15 -354 0z"></path> </g> </svg>-->
                        <span class="menu-title">@lang('Your Bids')</span>
                        @if($leading_bid_count)
                            <span class="side__menu__bage__num">{{$leading_bid_count}}</span>
                        @endif
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.winningbid.history') }}">
                    <a href="{{ route('user.winningbid.history') }}" class="nav-link ">
                        <i class="menu-icon las la-trophy"></i>
                        <span class="menu-title">@lang('Winning Bids')</span>
                        @if($winning_bid_count)
                            <span class="side__menu__bage__num">{{$winning_bid_count}}</span>
                        @endif
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.winning.history') }}">
                    <a href="{{ route('user.winning.history') }}" class="nav-link ">
                        <i class="menu-icon las la-store"></i>
                        <span class="menu-title">@lang('Buy It Now Orders')</span>
                        @if($winning_history_count)
                            <span class="side__menu__bage__num">{{$winning_history_count}}</span>
                        @endif
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.transactions') }}">
                    <a href="{{ route('user.transactions') }}" class="nav-link ">
                        <i class="menu-icon las la-list"></i>
                        <span class="menu-title">@lang('Transactions')</span>
                    </a>
                </li>
                
                <!--<li class="sidebar-menu-item {{ menuActive('user.deposit') }}">-->
                <!--    <a href="{{ route('user.deposit') }}" class="nav-link ">-->
                <!--        <i class="menu-icon las la-credit-card"></i>-->
                <!--        <span class="menu-title">@lang('Deposit')</span>-->
                <!--    </a>-->
                <!--</li>-->
                
                <!--<li class="sidebar-menu-item {{ menuActive('user.deposit.history') }}">-->
                <!--    <a href="{{ route('user.deposit.history') }}" class="nav-link ">-->
                <!--        <i class="menu-icon las la-wallet"></i>-->
                <!--        <span class="menu-title">@lang('Deposit History')</span>-->
                <!--    </a>-->
                <!--</li>-->
                
                <li class="sidebar-menu-item {{ menuActive('user.bonus') }}">
                    <a href="{{ route('user.bonus') }}" class="nav-link ">
                        <i class="menu-icon las la-gift"></i>
                        <span class="menu-title">@lang('Bonus Scheme')</span>
                    </a>
                </li>
                
                <!--<li class="sidebar-menu-item {{ menuActive('user.monitor') }}">-->
                <!--    <a href="{{ route('user.monitor') }}" class="nav-link ">-->
                <!--        <i class="menu-icon las la-truck-loading"></i>-->
                <!--        <span class="menu-title">@lang('Order Status')</span>-->
                <!--    </a>-->
                <!--</li>-->
                
                <li class="sidebar-menu-item sidebar-dropdown">
                    <a href="javascript:void(0)" class="{{menuActive('ticket*',3)}}">
                        <i class="menu-icon las la-envelope"></i>
                        <span class="menu-title">@lang('Support Ticket')</span>
                    </a>
                    <div class="sidebar-submenu {{menuActive('ticket*',2)}} ">
                        <ul>
                            <li class="sidebar-menu-item {{ menuActive('ticket') }}">
                                <a href="{{ route('ticket') }}" class="nav-link ">
                                    <i class="menu-icon las la-envelope"></i>
                                    <span class="menu-title">@lang('Latest Tickets')</span>
                                </a>
                            </li>
                            <li class="sidebar-menu-item {{ menuActive('ticket.open') }}">
                                <a href="{{ route('ticket.open') }}" class="nav-link ">
                                    <i class="menu-icon las la-envelope-open-text"></i>
                                    <span class="menu-title">@lang('Create Ticket')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.profile.setting') }}">
                    <a href="{{ route('user.profile.setting') }}" class="nav-link ">
                        <i class="menu-icon lar la-user"></i>
                        <span class="menu-title">@lang('Profile')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.searchlist.index') }}">
                    <a href="{{ route('user.searchlist.index') }}" class="nav-link ">
                        <i class="menu-icon las la-search"></i>
                        <span class="menu-title">@lang('Search List')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.twofactor') }}">
                    <a href="{{ route('user.twofactor') }}" class="nav-link ">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Authentication')</span>
                    </a>
                </li>
                
                <li class="sidebar-menu-item {{ menuActive('user.closeaccount') }}">
                    <a href="{{ route('user.closeaccount') }}" class="nav-link ">
                        <i class="menu-icon las la-user-minus"></i>
                        <span class="menu-title">@lang('Delete User Account')</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('style')
<style>
    .comma-hint-icons {
        background: transparent;
    }
    
    .comma-hint-icons > i {
        font-size: 15px;
        color: red !important;
    }
    
    @media (max-width: 768px) {
        .notify__item .notify__content .title {
            font-size: 14px;
        }
    }
    
    @media (max-width: 380px) {
        .notify__item .notify__content .title {
            font-size: 12px;
        }
    }
</style>
@endpush
