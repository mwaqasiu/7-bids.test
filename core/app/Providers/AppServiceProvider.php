<?php

namespace App\Providers;

use App\Models\AdminNotification;
use App\Models\UserNotification;
use App\Models\AdminanswerNotification;
use App\Models\SellerNotification;
use App\Models\Advertisement;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Answer;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Languageiplist;
use App\Models\Merchant;
use App\Models\Auctionquestion;
use App\Models\Question;
use App\Models\Page;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\Auction;
use App\Models\Searchlist;
use App\Models\Product;
use App\Models\Auctionbid;
use App\Models\Bid;
use App\Models\Winner;
use App\Models\Auctionwinner;
use App\Models\Shopping;
use App\Models\Wishlist;
use App\Models\Auctionwishlist;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Location;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['request']->server->set('HTTPS', true);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $activeTemplate = activeTemplate();
        $general = GeneralSetting::first();
        $viewShare['general'] = $general;
        $viewShare['activeTemplate'] = $activeTemplate;
        $viewShare['activeTemplateTrue'] = activeTemplate(true);
        $viewShare['language'] = Language::all();
        $viewShare['pages'] = Page::where('tempname',$activeTemplate)->where('is_default',0)->get();
        view()->share($viewShare);
        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'banned_users_count'           => User::banned()->count(),
                'email_unverified_users_count' => User::emailUnverified()->count(),
                'sms_unverified_users_count'   => User::smsUnverified()->count(),
                'banned_merchants_count'           => Merchant::banned()->count(),
                'email_unverified_merchants_count' => Merchant::emailUnverified()->count(),
                'sms_unverified_merchants_count'   => Merchant::smsUnverified()->count(),
                'pending_user_ticket_count'         => SupportTicket::where('user_id', '!=', 0)->whereIN('status', [0,2])->count(),
                'pending_merchant_ticket_count'         => SupportTicket::where('merchant_id', '!=', 0)->whereIN('status', [0,2])->count(),
                'pending_deposits_count'    => Deposit::pending()->count(),
                'pending_withdraw_count'    => Withdrawal::pending()->count(),
                'live_auction_count' => Auction::live()->count(),
                'live_product_count' => Product::live()->whereNotIn('products.id', Winner::select('product_id as id')->get())->count(),
                'pending_product_count' => Product::pending()->count() + Auction::pending()->count(),
                'upcoming_product_count' => Product::upcoming()->count() + Auction::upcoming()->count(),
                'expired_product_count' => Product::expired()->count() + Auction::expired()->count(),
            ]);
        });

        view()->composer('templates.basic.partials.sidenav', function ($view) {
            $view->with([
                'leading_bid_count' => Auctionbid::where('user_id', auth()->id())->count() - Auctionwinner::with('auction.auctionbids')->whereHas('auction.auctionbids', function($bid){
                    $bid->where('user_id', auth()->id());
                })->count(),
                'winning_bid_count' => Auctionwinner::where('user_id', auth()->id())->with('user','auction', 'auctionbid')->latest()->paginate(getPaginate())->count()
            ]);
        });

        view()->composer('templates.basic.userpartials.usersidenav', function ($view) {
            $view->with([
                'leading_bid_count' => Auctionbid::where('user_id', auth()->id())->whereNotIn('auction_id', Auctionwinner::where('user_id', auth()->id())->select('auction_id')->get())->whereNotIn('auction_id', Auction::expired()->select('id')->get())->select('*', DB::raw("MAX(amount) as maxamount"))->groupBy("auction_id")->latest()->paginate(getPaginate())->count(),
                'winning_bid_count' => Auctionwinner::where('user_id', auth()->id())->with('user','auction', 'auctionbid')->latest()->paginate(getPaginate())->count(),
                'winning_history_count' => Winner::where('user_id', auth()->id())->with('user','product', 'bid')->latest()->paginate(getPaginate())->count(),
            ]);
        });

        view()->composer('merchant.partials.sidenav', function ($view) {
            $view->with([
                'total_product_count' => Product::query()->live()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->whereNotIn('id', Winner::query()->select('product_id')->get())->count(),
                'live_product_count' => Auction::query()->live()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count(),
                'pending_product_count' => Product::query()->pending()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count() + Auction::query()->pending()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count(),
                'upcoming_product_count' => Product::query()->upcoming()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count() + Auction::query()->upcoming()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count(),
                'expired_product_count' => Product::query()->expired()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count() + Auction::query()->expired()->with('category')->where('merchant_id', auth()->guard('merchant')->id())->count(),
                'bids_count' => Bid::with('user', 'product')->whereHas('product', function($product){
                                    $product->where('merchant_id', auth()->guard('merchant')->id());
                                })->whereNotIn('product_id', Winner::select('product_id')->get())->count(),
                'winners_count' => Winner::with('product', 'user')->whereHas('product', function($product){
                                        $product->where('merchant_id', auth()->guard('merchant')->id());
                                    })->count() + Auctionwinner::with('auction', 'user')->whereHas('auction', function($auction) {
                                        $auction->where('merchant_id', auth()->guard('merchant')->id());
                                    })->count(),
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'=>AdminNotification::where('read_status',0)->with('user', 'merchant')->orderBy('id','desc')->get(),
                'adminanswerNotifications'=>AdminanswerNotification::where('read_status', 0)->where('admin_id', 1)->with('user', 'product', 'auction')->orderBy('id', 'desc')->get(),
            ]);
        });

        view()->composer('merchant.partials.topnav', function ($view) {
            $view->with([
                'sellerNotifications'=>SellerNotification::where('read_status',0)->where('n_seller_id', auth()->guard('merchant')->id())->with('user', 'product', 'auction')->orderBy('id','desc')->get(),
            ]);
        });

        view()->composer('templates.basic.userpartials.userfootnav', function ($view) {
            if(auth()->check()) {
                $shopping_count = Shopping::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
            } else {
                $shopping_count = Shopping::where('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
            }

            $view->with([
                'shopping_count'=>$shopping_count,
            ]);
        });

        view()->composer('templates.basic.userpartials.usertopnav', function ($view) {
            $view->with([
                'userNotifications'=>UserNotification::where('read_status', 0)->where('n_user_id', auth()->id())->with('user', 'auction', 'product')->orderBy('id', 'desc')->get(),
                'answerauctionNotifications'=>Answer::query()->leftJoin('auctionquestions', "answers.question_id", "=", "auctionquestions.id")->select("answers.*", "auctionquestions.question")->where('answers.auction_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Auctionquestion::where('auctionquestions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get(),
                'answerproductNotifications'=>Answer::query()->leftJoin('questions', "answers.question_id", "=", "questions.id")->select("answers.*", "questions.question")->where('answers.product_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Question::where('questions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get(),
                'bonuscount'=>auth()->user()->bonuspoint,
            ]);
        });

        view()->composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with([
                'seo' => $seo ? $seo->data_values : $seo,
            ]);
        });

        view()->composer('templates.basic.partials.header', function ($view) {
            $answerauctionNotifications = 0;
            $answerproductNotifications = 0;
            $userNotifications = 0;
            $bonuscount = 0;
            $charitydata = Frontend::where('data_keys','charity.data')->firstOrFail();
            if(auth()->check()) {
                $shopping_count = Shopping::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
                $wishlist_count = Wishlist::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->count() + Auctionwishlist::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->count();
                $searchlists = Searchlist::where('user_id', auth()->user()->id)->get();
                $bonuscount = auth()->user()->bonuspoint;
                $userNotifications = UserNotification::where('read_status', 0)->where('n_user_id', auth()->user()->id)->with('user', 'auction', 'product')->orderBy('id', 'desc')->count();
                $answerauctionNotifications = Answer::query()->leftJoin('auctionquestions', "answers.question_id", "=", "auctionquestions.id")->select("answers.*", "auctionquestions.question")->where('answers.auction_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Auctionquestion::where('auctionquestions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->count();
                $answerproductNotifications = Answer::query()->leftJoin('questions', "answers.question_id", "=", "questions.id")->select("answers.*", "questions.question")->where('answers.product_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Question::where('questions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->count();
            } else {
                $shopping_count = Shopping::where('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
                $wishlist_count = Wishlist::where('ip_address', getenv('REMOTE_ADDR'))->count() + Auctionwishlist::where('ip_address', getenv('REMOTE_ADDR'))->count();
                $searchlists = [];
            }

            //$requestip = request()->ip();
            $requestip = '80.187.67.59';
            $locationdata = Location::get($requestip);
            $countrycode = strtolower($locationdata->countryCode);

            $langcount = Languageiplist::where('ipaddress', $requestip)->count();
            $languagelists = Languageiplist::where('ipaddress', $requestip)->get();
            if($langcount > 0) {
                session()->put('lang', $languagelists[0]->lang);
            }

            $view->with([
                'wishlist_count'=>$wishlist_count,
                'shopping_count'=>$shopping_count,
                'searchlists'=>$searchlists,
                'countrycode'=>$countrycode,
                'ipaddress'=>$requestip,
                'bonuscount' => $bonuscount,
                'userNotifications' => $userNotifications,
                'answerauctionNotifications' => $answerauctionNotifications,
                'answerproductNotifications' => $answerproductNotifications,
                'charitydata' => $charitydata,
            ]);
        });

        view()->composer('templates.basic.layouts.frontend', function ($view) {
            //$requestip = request()->ip();
            $requestip = '80.187.67.59';
            $locationdata = Location::get($requestip);
            $countrycode = strtolower($locationdata->countryCode);

            $langcount = Languageiplist::where('ipaddress', $requestip)->count();
            $languagelists = Languageiplist::where('ipaddress', $requestip)->get();
            if($langcount > 0) {
                session()->put('lang', $languagelists[0]->lang);
            }

            $view->with([
                'countrycode'=>$countrycode,
                'ipaddress'=>$requestip,
                'countlist'=>$langcount,
            ]);
        });

        if($general->force_ssl){
            \URL::forceScheme('https');
        }

        Paginator::useBootstrap();

    }
}
