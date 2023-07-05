<aside class="dashboard__sidebar">
    <div class="sidebar-container">
        <div class="dashboard__logo">
            <a href="{{ route('home') }}">
                <img src="{{ getImage(imagePath()['logoIcon']['path'] .'/logo.png') }}" alt="logo">
            </a>
            <span class="close-sidebar d-lg-none">
                <i class="las la-times"></i>
            </span>
        </div>
        <div class="side__menu__area">
            <div class="side__menu__area-inner">
                <ul class="side__menu"> 
                    <li>
                        <a href="{{ route('user.home') }}" class="{{ menuActive('user.home') }}">
                            <i class="las la-home"></i>
                            <span class="cont">@lang('Dashboard')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.leadingbid.history') }}" class="{{ menuActive('user.leadingbid.history') }}">
                            <i class="las la-bolt"></i>
                            <!--<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 144.000000 144.000000" preserveAspectRatio="xMidYMid meet">  <g transform="translate(0.000000,144.000000) scale(0.100000,-0.100000)" fill="rgba(255,255,255,0.9)" stroke="none"> <path d="M565 1293 c-66 -80 -140 -170 -165 -198 -25 -29 -65 -76 -90 -105 -55 -65 -78 -106 -64 -120 6 -6 67 -11 136 -13 70 -1 130 -6 134 -10 5 -4 10 -49 12 -100 1 -51 5 -98 7 -104 6 -18 340 -18 351 -1 5 7 9 53 11 103 2 69 7 93 19 102 11 8 58 12 132 12 64 0 119 4 125 10 15 15 -7 58 -56 113 -109 122 -367 436 -367 448 0 5 -15 10 -32 10 -30 -1 -44 -14 -153 -147z"></path> <path d="M535 480 c-8 -13 -8 -137 0 -150 9 -14 341 -14 350 1 12 19 15 120 5 140 -10 18 -23 19 -180 19 -97 0 -171 -4 -175 -10z"></path> <path d="M533 169 c-15 -15 -4 -130 15 -151 13 -16 35 -18 162 -18 127 0 149 2 162 18 19 21 30 136 15 151 -15 15 -339 15 -354 0z"></path> </g> </svg>-->
                            <span class="cont">@lang('Leading Bids')</span>
                            @if($leading_bid_count)
                                <span class="side__menu__bage__num">{{$leading_bid_count}}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.winningbid.history') }}" class="{{ menuActive('user.winningbid.history') }}">
                            <i class="las la-trophy"></i>
                            <span class="cont">@lang('Winning Bids')</span>
                            @if($winning_bid_count)
                                <span class="side__menu__bage__num">{{$winning_bid_count}}</span>
                            @endif
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.outbidden.history') }}" class="{{ menuActive('user.outbidden.history') }}">
                            <i class="las la-bell"></i>
                            <span class="cont">@lang('Outbidden')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.bidding.history') }}" class="{{ menuActive('user.bidding.history') }}">
                            <i class="las la-history"></i>
                            <span class="cont">@lang('Bidding History')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.winning.history') }}" class="{{ menuActive('user.winning.history') }}">
                            <i class="las la-trophy"></i>
                            <span class="cont">@lang('Wining History')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.transactions') }}" class="{{ menuActive('user.transactions') }}">
                            <i class="las la-list"></i>
                            <span class="cont">@lang('Transaction')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.deposit') }}" class="{{ menuActive('user.deposit') }}">
                            <i class="las la-credit-card"></i>
                            <span class="cont">@lang('Deposit')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.deposit.history') }}" class="{{ menuActive('user.deposit.history') }}">
                            <i class="las la-wallet"></i>
                            <span class="cont">@lang('Deposit History')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.bonus') }}" class="{{ menuActive('user.bonus') }}">
                            <i class="las la-gift"></i>
                            <span class="cont">@lang('Bonus Scheme')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.monitor') }}" class="{{ menuActive('user.monitor') }}">
                            <i class="las la-desktop"></i>
                            <span class="cont">@lang('Item Monitoring')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ticket') }}" class="{{ menuActive('ticket') }}">
                            <i class="las la-envelope"></i>
                            <span class="cont">@lang('Ticket')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('ticket.open') }}" class="{{ menuActive('ticket.open') }}">
                            <i class="las la-envelope-open-text"></i>
                            <span class="cont">@lang('Create Ticket')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile.setting') }}" class="{{ menuActive('user.profile.setting') }}">
                            <i class="lar la-user"></i>
                            <span class="cont">@lang('Profile')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.twofactor') }}" class="{{ menuActive('user.twofactor') }}">
                            <i class="las la-shield-alt"></i>
                            <span class="cont">@lang('Two Factor')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.change.password') }}" class="{{ menuActive('user.change.password') }}">
                            <i class="las la-lock"></i>
                            <span class="cont">@lang('Change Password')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.logout') }}" class="{{ menuActive('user.logout') }}">
                            <i class="las la-sign-in-alt"></i>
                            <span class="cont">@lang('Logout')</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>