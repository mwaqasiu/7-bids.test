<nav class="userfootnav_main">
    <div class="container">
        <div>
            @if (!auth()->check())
            <div>
                <a href="{{ route('user.login') }}" class="{{ menuActive('user.login') }}">
                    <i class="fas la-user"></i>
                    <span>@lang('Login')</span>
                </a>
            </div>
            @endif
            @auth
            <div style="position: relative;">
                <a href="{{ route('home') }}" class="{{ menuActive('user.home') }}">
                    <i class="fas la-home"></i>
                    <span>@lang('Home')</span>
                </a>
            </div>
            @endauth
            @auth('merchant')
            <div>
                <a href="{{ route('home') }}" class="{{ menuActive('merchant.dashboard') }}">
                    <i class="fas la-user"></i>
                    <span>@lang('Home')</span>
                </a>
            </div>
            @endauth
            <div>
                <a href="{{ route('auction.all') }}" class="{{ menuActive('auction.all') }}">
                    <i class="fas la-gavel"></i>
                    <span>@lang('Auction')</span>
                </a>
            </div>
            <div>
                <a href="{{ route('product.all') }}" class="{{ menuActive('product.all') }}">
                    <i class="fas la-store"></i>
                    <span>@lang('Buy It Now')</span>
                </a>
            </div>
            @if(auth()->check())
                <div style="position: relative;">
                    <a href="{{ route('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}" class="{{ menuActive('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                        <i class="fas la-shopping-cart"></i>
                        <span>@lang('Send Order')</span>
                    </a>
                    @if($shopping_count > 0)
                        <div class="userfootshoppingcountsec">{{ $shopping_count }}</div>
                    @endif
                </div>
            @else
                <div style="position: relative;">
                    <a href="{{ route('user.shopping-cart', ['empty', getenv('REMOTE_ADDR')]) }}" class="{{ menuActive('user.shopping-cart', [auth()->user()->id, getenv('REMOTE_ADDR')]) }}">
                        <i class="fas la-shopping-cart"></i>
                        <span>@lang('Send Order')</span>
                    </a>
                    @if($shopping_count > 0)
                        <div class="userfootshoppingcountsec">{{ $shopping_count }}</div>
                    @endif
                </div>
            @endif
            <div>
                <button class="res-sidebar-open-btn">
                    <i class="fas la-bars"></i>
                    <span>@lang('Overview')</span>
                </button>
            </div>
        </div>
    </div>
</nav>

@push('style')
<style>
    .userfootnav_main {
        display: none;
        transition: 0.5s all ease-in-out;
    }
    
    @media (max-width: 991px) {
        .userfootnav_main {
            display: block;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 10000;
        }
    }
    
    .userfootnav_main {
        position: fixed;
        bottom: 0;
        -webkit-transition: all ease 0.3s;
        -moz-transition: all ease 0.3s;
        transition: all ease 0.3s;
        z-index: 8;
        padding-top: 5px;
        padding-bottom: 5px;
        background: #001329;
        border-bottom: 1px dashed rgba(193, 81, 204, 0.1);
        box-shadow: 0 8px 8px rgb(193 81 204 / 10%);
        width: 100%;
    }
    
    .userfootnav_main > .container > div {
        display: flex;
        justify-content: space-evenly;
        align-items: center;
    }
    
    .userfootnav_main > .container > div > div {
        display: flex;
        flex-direction: column;
        margin: 3px 5px;
    }
    
    .userfootnav_main > .container > div > div > a {
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .userfootnav_main > .container > div > div > button {
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
    
    .userfootnav_main > .container > div > div > a > i {
        font-size: 20px;
    }
    
    .userfootnav_main > .container > div > div > button > i {
        font-size: 20px;
    }
    
    .userfootnav_main > .container > div > div > a > span {
        font-size: 10px;
        text-align: center;
        line-height: 26px;
        font-weight: 500;
        font-family: "Nunito", sans-serif;
    }
    
    .userfootnav_main > .container > div > div > button > span {
        font-size: 10px;
        text-align: center;
        line-height: 26px;
        font-weight: 500;
        font-family: "Nunito", sans-serif;
    }
    
    .userfootnav_main > .container > div > div > a.active > i {
        color: #336699;
    }
    
    .userfootnav_main > .container > div > div > a.active > span {
        color: #336699;
    }
    
    .answernotificationstatus {
        background: #ea5455 !important;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(8px);
    }
    
    .userfootshoppingcountsec {
        position: absolute;
        top: -4px;
        right: 7px;
        width: 16px;
        height: 16px;
        background: red;
        font-size: 10px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
@endpush