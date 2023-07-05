<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\SellerNotification;
use App\Models\AdminanswerNotification;
use App\Models\UserNotification;
use App\Models\GeneralSetting;
use App\Models\Auction;
use App\Models\Auctionquestion;
use App\Models\Product;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Auctionreview;
use App\Models\Auctionbid;
use App\Models\Auctionwinner;
use App\Models\Auctionwishlist;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AuctionController extends Controller {
    
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    
    public function auctions()
    {
        $pageTitle      = request()->search_key?'Search Auctions':'Auction';
        $emptyMessage   = 'No auction found';
        $categories     = Category::with('auctions')->where('status', 1)->get();
        $winnertext = "";
        $upcomingtext = "";
        $wishlists = Auctionwishlist::all();
        $auctions       = Auction::live();
        $auctions       = $auctions->where('name', 'like', '%'.request()->search_key.'%')->with('category');
        $allAuctions    = clone $auctions->get();
        $priceAuctions = clone Auction::query()->get();
        if(request()->category_id){
            $auctions       = $auctions->where('category_id', request()->category_id);
        }
        $auctions = $auctions->orderBy('expired_at', 'ASC')->paginate(getPaginate(18));
        return view($this->activeTemplate.'auction.list', compact('pageTitle', 'emptyMessage', 'auctions', 'allAuctions', 'priceAuctions', 'categories', 'wishlists', 'winnertext', 'upcomingtext'));
    }
    
    public function filter(Request $request)
    {
        $pageTitle      = 'Search Auctions';
        $emptyMessage   = 'No auction found';
        $wishlists = Auctionwishlist::all();
        $auctions       = Auction::live()->whereNotIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');
        $winnertext = "";
        $upcomingtext = "";
        $allAuctions    = clone $auctions->get();
        $categories     = Category::with('auctions')->where('status', 1)->get();
        
        if($request->timing == "sold") {
            $winnertext = "winner";
            $auctions = Auction::whereIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');
            
            if($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }
            
            if($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }
            
            if($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }
            
            if($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }
            
            if($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }
            
            $auctions = $auctions->orderBy('expired_at', 'ASC');
            
            if($request->dateprice) {
                if($request->dateprice == "created_at") {
                    $auctions = $auctions->orderBy('expired_at', 'DESC');
                } else if($request->dateprice == "created_at_asc") {
                    $auctions = $auctions->orderBy('expired_at', 'ASC');
                } else if($request->dateprice == "price_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
                }
            }
            
            if($request->categories){
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        } else if($request->timing == "arrivals") {
            $upcomingtext = "upcoming";
            $auctions = Auction::upcoming()->whereNotIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');
            
            if($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }
            
            if($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }
            
            if($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }
            
            if($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }
            
            if($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }
            
            $auctions = $auctions->orderBy('expired_at', 'ASC');
            
            if($request->dateprice) {
                if($request->dateprice == "created_at") {
                    $auctions = $auctions->orderBy('expired_at', 'DESC');
                } else if($request->dateprice == "created_at_asc") {
                    $auctions = $auctions->orderBy('expired_at', 'ASC');
                } else if($request->dateprice == "price_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
                }
            }
            
            if($request->categories){
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        } else {
            if($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }
            
            if($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }
            
            if($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }
            
            if($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }
            
            if($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }
            
            $auctions = $auctions->orderBy('expired_at', 'ASC');
            
            if($request->dateprice) {
                if($request->dateprice == "created_at") {
                    $auctions = $auctions->orderBy('expired_at', 'DESC');
                } else if($request->dateprice == "created_at_asc") {
                    $auctions = $auctions->orderBy('expired_at', 'ASC');
                } else if($request->dateprice == "price_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
                }
            }
            
            if($request->categories){
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        }
        
        $priceAuctions = clone $auctions->get();
        
        if($request->minPrice){
            $auctions = $auctions->where('price', '>=', $request->minPrice);
        }
        
        if($request->maxPrice){
            $auctions = $auctions->where('price', '<=', $request->maxPrice);
        }
        
        $auctions = $auctions->paginate(getPaginate(18));

        return view($this->activeTemplate.'auction.filtered', compact('pageTitle', 'allAuctions', 'priceAuctions', 'categories', 'emptyMessage', 'auctions', 'wishlists', 'winnertext', 'upcomingtext'));
    }
    
    public function auctionDetails($id)
    {
        $pageTitle = "";
        
        $user = auth()->user();
        
        $auction = Auction::with('auctionreviews', 'admin', 'merchant', 'auctionreviews.user')->where('status', '!=', 0)->findOrFail($id);

        $relatedAuctions = Auction::live()->where('category_id', $auction->category_id)->where('id', '!=', $id)->limit(10)->get();
        
        $relatedAuctionbids = Auctionbid::where('auction_id', $id)->with('user')->orderBy('amount', 'DESC')->orderBy('created_at', 'ASC')->get();
        
        $relatedAuctionbidary = Auctionbid::where('auction_id', $id)->with('user')->groupBy('user_id')->select(DB::raw("*, MIN(created_at) AS maxcreated_at"))->orderBy('maxcreated_at', 'ASC')->get();
        
        $empty = "No bidders";

        $imageData      = imagePath()['product'];

        $seoContents    = getSeoContents($auction, $imageData, 'image');
        
        $winnerflag = Auctionwinner::query()->where('auction_id', $id)->count();
        $winnerdatas = Auctionwinner::where('auction_id', $id)->with('auctionbid')->get();
        
        $auctionquestions_count = Auctionquestion::where('auction_id', $id)->count();
        $auctionquestions = Auctionquestion::where('auction_id', $id)->orderBy('id', 'desc')->get();
        
        // get max bid amount
        $maxbidamount = 0;
        $maxuserbidamount = 0;
        if($user)
        {
            $getmaxbids = Auctionbid::where('auction_id', $id)->select(DB::raw("MAX(amount) AS maxamount"))->get();
            foreach($getmaxbids as $getbid) {
                $maxbidamount = $getbid->maxamount;
            }
            
            $getusermaxbids = Auctionbid::where('auction_id', $id)->where('user_id', $user->id)->select(DB::raw("MAX(amount) AS maxamount"))->get();
            foreach($getusermaxbids as $getuserbid) {
                $maxuserbidamount = $getuserbid->maxamount;
            }
        }
        
        $auction_count = Auction::where('status', '!=', 0)->where('merchant_id', 1)->count();
        $product_count = Product::where('status', '!=', 0)->where('merchant_id', 1)->count();
        
        $auctionanswers = Answer::where('auction_id', $auction->id)->get();
        
        $wishlist = Auctionwishlist::where('auction_id', $auction->id)->where('ip_address', getenv('REMOTE_ADDR'))->get();
        
        return view($this->activeTemplate.'auction.details', compact('pageTitle', 'auctionanswers', 'auctionquestions', 'auctionquestions_count', 'auction_count', 'product_count', 'wishlist', 'maxbidamount', 'maxuserbidamount', 'auction', 'relatedAuctions', 'relatedAuctionbids', 'relatedAuctionbidary', 'seoContents', 'winnerflag', 'winnerdatas', 'empty'));
    }
    
    public function auctionQuestionSave(Request $request) {
        $request->validate([
            'username' => 'required',
            'auction_id' => 'required|numeric',
            'question' => 'required',
        ]);
        
        $user = auth()->user();
        $auctionquestion = new Auctionquestion();
        $auctionquestion->user_id = $user->id;
        $auctionquestion->auction_id = $request->auction_id;
        $auctionquestion->username = $request->username;
        $auctionquestion->question = $request->question;
        $auctionquestion->save();
        
        $auction = Auction::findOrFail($request->auction_id);
        
        $adminanswernotification = new AdminanswerNotification();
        $adminanswernotification->admin_id = $auction->admin_id;
        $adminanswernotification->user_id = $user->id;
        $adminanswernotification->title = $request->username." asked a question";
        $adminanswernotification->auction_id = $request->auction_id;
        $adminanswernotification->question_id = $auctionquestion->id;
        $adminanswernotification->question = $request->question;
        $adminanswernotification->save();
        
        $sellernotification = new SellerNotification();
        $sellernotification->n_seller_id = $auction->merchant_id;
        $sellernotification->user_id = $user->id;
        $sellernotification->title = $request->username." asked a question";
        $sellernotification->auction_id = $request->auction_id;
        $sellernotification->question_id = $auctionquestion->id;
        $sellernotification->question = $request->question;
        $sellernotification->save();
        
        $notify[] = ['success', 'Question successfully sent.'];
        return back()->withNotify($notify);
    }
    
    public function loadMore(Request $request)
    {
        $reviews = Auctionreview::where('auction_id', $request->aid)->with('user')->latest()->paginate(5);

        return view($this->activeTemplate . 'partials.product_review', compact('reviews'));
    }
    
    public function addbid(Request $request) {
        $request->validate([
            'addbidamount' => 'required|numeric|gt:0',
            'addbid_id' => 'required|integer|gt:0',
        ]);
        
        $auction = Auction::live()->with('merchant', 'admin')->findOrFail($request->addbid_id);
        
        $user = auth()->user();
        
        if($auction->price > $request->addbidamount){
            $notify[] = ['error', 'Bid amount must be greater than product price'];
            return back()->withNotify($notify);
        }
        
        // if($request->addbidamount > $user->balance){
        //     $notify[] = ['error', 'Insufficient Balance'];
        //     return back()->withNotify($notify);
        // }
        
        // get max bid amount
        $maxbidamount = 0;
        $maxuserid = 0;
        $getmaxbids = Auctionbid::where('auction_id', $request->addbid_id)->orderBy('amount', 'desc')->limit(1)->get();
        foreach($getmaxbids as $getbid) {
            $maxbidamount = $getbid->amount;
            $maxuserid = $getbid->user_id;
        }
        
        if((float)$maxbidamount < (float)$request->addbidamount) {
            if(intval($maxuserid) != intval($user->id) && intval($maxuserid) != 0)
            {
                $setauction = Auction::findOrFail($request->addbid_id);
                if(intval($maxbidamount) >= 100 && intval($maxbidamount) < 500) {
                    $setauction->price = $maxbidamount + 50;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 500 && intval($maxbidamount) < 1000) {
                    $setauction->price = $maxbidamount + 100;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 1000 && intval($maxbidamount) < 5000) {
                    $setauction->price = $maxbidamount + 200;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 5000 && intval($maxbidamount) < 10000) {
                    $setauction->price = $maxbidamount + 500;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 10000 && intval($maxbidamount) < 20000) {
                    $setauction->price = $maxbidamount + 1000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 20000 && intval($maxbidamount) < 50000) {
                    $setauction->price = $maxbidamount + 2000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 50000 && intval($maxbidamount) < 100000) {
                    $setauction->price = $maxbidamount + 5000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 100000 && intval($maxbidamount) < 200000) {
                    $setauction->price = $maxbidamount + 10000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 200000 && intval($maxbidamount) < 300000) {
                    $setauction->price = $maxbidamount + 20000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 300000 && intval($maxbidamount) < 500000) {
                    $setauction->price = $maxbidamount + 25000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 500000 && intval($maxbidamount) < 1000000) {
                    $setauction->price = $maxbidamount + 50000;
                    $setauction->save();
                } else if(intval($maxbidamount) == 1000000) {
                    $setauction->price = 1000000;
                    $setauction->save();
                }
            }
        } else if((float)$maxbidamount == (float)$request->addbidamount) {
            $setauction = Auction::findOrFail($request->addbid_id);
            $setauction->price = $request->addbidamount;
            $setauction->save();
            
            $filterbids = Auctionbid::where('auction_id', $request->addbid_id)->where('user_id', '!=', $user->id)->select('*', DB::raw("MAX(amount) AS maxamount"))->groupBy('user_id')->get();
        
            foreach($filterbids as $fbid) {
                if((float)$fbid->maxamount < (float)$request->addbidamount)
                {
                    $userNotification = new UserNotification();
                    $userNotification->user_id = auth()->user()->id;
                    $userNotification->n_user_id = $fbid->user_id;
                    $userNotification->auction_id = $auction->id;
                    $userNotification->title = 'Another user placed a higher bid.';
                    $userNotification->click_url = urlPath('user.notifications.bids', $auction->id);
                    $userNotification->save();
                }
            }
            
            $bid = new Auctionbid();
            $bid->auction_id = $auction->id;
            $bid->user_id = auth()->id();
            $bid->amount = $request->addbidamount;
            $bid->save();
    
            $auction->total_bid += 1;
            $auction->save();
           
            $general = GeneralSetting::first();
    
            
            if($auction->admin){
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = auth()->user()->id;
                $adminNotification->title = 'A user has been bidden on your auction product';
                $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
                $adminNotification->save();

                $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
                return back()->withNotify($notify);
            }
            
            $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
            return back()->withNotify($notify);
        } else {
            if(intval($maxuserid) != intval($user->id) && intval($maxuserid) != 0)
            {
                $setauction = Auction::findOrFail($request->addbid_id);
                if(intval($request->addbidamount) >= 100 && intval($request->addbidamount) < 500) {
                    $setauction->price = $request->addbidamount + 50;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 500 && intval($request->addbidamount) < 1000) {
                    $setauction->price = $request->addbidamount + 100;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 1000 && intval($request->addbidamount) < 5000) {
                    $setauction->price = $request->addbidamount + 200;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 5000 && intval($request->addbidamount) < 10000) {
                    $setauction->price = $request->addbidamount + 500;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 10000 && intval($request->addbidamount) < 20000) {
                    $setauction->price = $request->addbidamount + 1000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 20000 && intval($request->addbidamount) < 50000) {
                    $setauction->price = $request->addbidamount + 2000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 50000 && intval($request->addbidamount) < 100000) {
                    $setauction->price = $request->addbidamount + 5000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 100000 && intval($request->addbidamount) < 200000) {
                    $setauction->price = $request->addbidamount + 10000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 200000 && intval($request->addbidamount) < 300000) {
                    $setauction->price = $request->addbidamount + 20000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 300000 && intval($request->addbidamount) < 500000) {
                    $setauction->price = $request->addbidamount + 25000;
                    $setauction->save();
                } else if(intval($request->addbidamount) >= 500000 && intval($request->addbidamount) < 1000000) {
                    $setauction->price = $request->addbidamount + 50000;
                    $setauction->save();
                } else if(intval($request->addbidamount) == 1000000) {
                    $setauction->price = 1000000;
                    $setauction->save();
                }
            }
            $bid = new Auctionbid();
            $bid->auction_id = $auction->id;
            $bid->user_id = auth()->id();
            $bid->amount = $request->addbidamount;
            $bid->save();
    
            $auction->total_bid += 1;
            $auction->save();
            
            $general = GeneralSetting::first();
    
            
            if($auction->admin){
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = auth()->user()->id;
                $adminNotification->title = 'A user has been bidden on your auction product';
                $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
                $adminNotification->save();

                $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
                return back()->withNotify($notify);
            }
            
            $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
            return back()->withNotify($notify);
            
        }
        
        $filterbids = Auctionbid::where('auction_id', $request->addbid_id)->where('user_id', '!=', $user->id)->select('*', DB::raw("MAX(amount) AS maxamount"))->groupBy('user_id')->get();
        
        foreach($filterbids as $fbid) {
            if((float)$fbid->maxamount < (float)$request->addbidamount)
            {
                $userNotification = new UserNotification();
                $userNotification->user_id = auth()->user()->id;
                $userNotification->n_user_id = $fbid->user_id;
                $userNotification->auction_id = $auction->id;
                $userNotification->title = 'Another user placed a higher bid.';
                $userNotification->click_url = urlPath('user.notifications.bids', $auction->id);
                $userNotification->save();
            }
        }

        $bid = new Auctionbid();
        $bid->auction_id = $auction->id;
        $bid->user_id = auth()->id();
        $bid->amount = $request->addbidamount;
        $bid->save();

        $auction->total_bid += 1;
        $auction->save();
        
        $general = GeneralSetting::first();


        if($auction->admin){
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->user()->id;
            $adminNotification->title = 'A user has been bidden on your auction product';
            $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
            $adminNotification->save();

            $notify[] = ['success', 'You increased your bid successfully'];
            return back()->withNotify($notify);
        }
        
        $notify[] = ['success', 'You increased your bid successfully'];
        return back()->withNotify($notify);
    }
    
    public function bid(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'auction_id' => 'required|integer|gt:0'
        ]);

        $auction = Auction::live()->with('merchant', 'admin')->findOrFail($request->auction_id);

        $user = auth()->user();

        if($auction->price > $request->amount){
            $notify[] = ['error', 'Bid amount must be greater than product price'];
            return back()->withNotify($notify);
        }

        // if($request->amount > $user->balance){
        //     $notify[] = ['error', 'Insufficient Balance'];
        //     return back()->withNotify($notify);
        // }
        
        // get max bid amount
        $maxbidamount = 0;
        $maxuserid = 0;
        $getmaxbids = Auctionbid::where('auction_id', $request->auction_id)->orderBy('amount', 'desc')->limit(1)->get();
        foreach($getmaxbids as $getbid) {
            $maxbidamount = $getbid->amount;
            $maxuserid = $getbid->user_id;
        }
        
        if((float)$maxbidamount < (float)$request->amount) {
            if(intval($maxuserid) != intval($user->id) && intval($maxuserid) != 0)
            {
                $setauction = Auction::findOrFail($request->auction_id);
                if(intval($maxbidamount) >= 100 && intval($maxbidamount) < 500) {
                    $setauction->price = $maxbidamount + 50;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 500 && intval($maxbidamount) < 1000) {
                    $setauction->price = $maxbidamount + 100;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 1000 && intval($maxbidamount) < 5000) {
                    $setauction->price = $maxbidamount + 200;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 5000 && intval($maxbidamount) < 10000) {
                    $setauction->price = $maxbidamount + 500;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 10000 && intval($maxbidamount) < 20000) {
                    $setauction->price = $maxbidamount + 1000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 20000 && intval($maxbidamount) < 50000) {
                    $setauction->price = $maxbidamount + 2000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 50000 && intval($maxbidamount) < 100000) {
                    $setauction->price = $maxbidamount + 5000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 100000 && intval($maxbidamount) < 200000) {
                    $setauction->price = $maxbidamount + 10000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 200000 && intval($maxbidamount) < 300000) {
                    $setauction->price = $maxbidamount + 20000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 300000 && intval($maxbidamount) < 500000) {
                    $setauction->price = $maxbidamount + 25000;
                    $setauction->save();
                } else if(intval($maxbidamount) >= 500000 && intval($maxbidamount) < 1000000) {
                    $setauction->price = $maxbidamount + 50000;
                    $setauction->save();
                } else if(intval($maxbidamount) == 1000000) {
                    $setauction->price = 1000000;
                    $setauction->save();
                }
            }
        } else if((float)$maxbidamount == (float)$request->amount) {
            $setauction = Auction::findOrFail($request->auction_id);
            $setauction->price = $request->amount;
            $setauction->save();
            
            $filterbids = Auctionbid::where('auction_id', $request->auction_id)->where('user_id', '!=', $user->id)->select('*', DB::raw("MAX(amount) AS maxamount"))->groupBy('user_id')->get();
        
            foreach($filterbids as $fbid) {
                if((float)$fbid->maxamount < (float)$request->amount)
                {
                    $userNotification = new UserNotification();
                    $userNotification->user_id = auth()->user()->id;
                    $userNotification->n_user_id = $fbid->user_id;
                    $userNotification->auction_id = $auction->id;
                    $userNotification->title = 'Another user placed a higher bid.';
                    $userNotification->click_url = urlPath('user.notifications.bids', $auction->id);
                    $userNotification->save();
                }
            }
            
            $bid = new Auctionbid();
            $bid->auction_id = $auction->id;
            $bid->user_id = auth()->id();
            $bid->amount = $request->amount;
            $bid->save();
    
            $auction->total_bid += 1;
            $auction->save();
            
            $general = GeneralSetting::first();
    
    
            if($auction->admin){
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = auth()->user()->id;
                $adminNotification->title = 'A user has been bidden on your auction product';
                $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
                $adminNotification->save();
    
                $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
                return back()->withNotify($notify);
            }
    
            
            $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
            return back()->withNotify($notify);
        } else {
            if(intval($maxuserid) != intval($user->id) && intval($maxuserid) != 0)
            {
                $setauction = Auction::findOrFail($request->auction_id);
                if(intval($request->amount) >= 100 && intval($request->amount) < 500) {
                    $setauction->price = $request->amount + 50;
                    $setauction->save();
                } else if(intval($request->amount) >= 500 && intval($request->amount) < 1000) {
                    $setauction->price = $request->amount + 100;
                    $setauction->save();
                } else if(intval($request->amount) >= 1000 && intval($request->amount) < 5000) {
                    $setauction->price = $request->amount + 200;
                    $setauction->save();
                } else if(intval($request->amount) >= 5000 && intval($request->amount) < 10000) {
                    $setauction->price = $request->amount + 500;
                    $setauction->save();
                } else if(intval($request->amount) >= 10000 && intval($request->amount) < 20000) {
                    $setauction->price = $request->amount + 1000;
                    $setauction->save();
                } else if(intval($request->amount) >= 20000 && intval($request->amount) < 50000) {
                    $setauction->price = $request->amount + 2000;
                    $setauction->save();
                } else if(intval($request->amount) >= 50000 && intval($request->amount) < 100000) {
                    $setauction->price = $request->amount + 5000;
                    $setauction->save();
                } else if(intval($request->amount) >= 100000 && intval($request->amount) < 200000) {
                    $setauction->price = $request->amount + 10000;
                    $setauction->save();
                } else if(intval($request->amount) >= 200000 && intval($request->amount) < 300000) {
                    $setauction->price = $request->amount + 20000;
                    $setauction->save();
                } else if(intval($request->amount) >= 300000 && intval($request->amount) < 500000) {
                    $setauction->price = $request->amount + 25000;
                    $setauction->save();
                } else if(intval($request->amount) >= 500000 && intval($request->amount) < 1000000) {
                    $setauction->price = $request->amount + 50000;
                    $setauction->save();
                } else if(intval($request->amount) == 1000000) {
                    $setauction->price = 1000000;
                    $setauction->save();
                }
            }
            
            $bid = new Auctionbid();
            $bid->auction_id = $auction->id;
            $bid->user_id = auth()->id();
            $bid->amount = $request->amount;
            $bid->save();
    
            $auction->total_bid += 1;
            $auction->save();
            
            $general = GeneralSetting::first();
    
    
            if($auction->admin){
                $adminNotification = new AdminNotification();
                $adminNotification->user_id = auth()->user()->id;
                $adminNotification->title = 'A user has been bidden on your auction product';
                $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
                $adminNotification->save();
    
                $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
                return back()->withNotify($notify);
            }
            
            $notify[] = ['error', 'Another bidder placed a higher or equal bid.'];
            return back()->withNotify($notify);
        }
        
        $filterbids = Auctionbid::where('auction_id', $request->auction_id)->where('user_id', '!=', $user->id)->select('*', DB::raw("MAX(amount) AS maxamount"))->groupBy('user_id')->get();
        
        foreach($filterbids as $fbid) {
            if((float)$fbid->maxamount < (float)$request->amount)
            {
                $userNotification = new UserNotification();
                $userNotification->user_id = auth()->user()->id;
                $userNotification->n_user_id = $fbid->user_id;
                $userNotification->auction_id = $auction->id;
                $userNotification->title = 'Another user placed a higher bid.';
                $userNotification->click_url = urlPath('user.notifications.bids', $auction->id);
                $userNotification->save();
            }
        }

        $bid = new Auctionbid();
        $bid->auction_id = $auction->id;
        $bid->user_id = auth()->id();
        $bid->amount = $request->amount;
        $bid->save();

        $auction->total_bid += 1;
        $auction->save();
        
        $general = GeneralSetting::first();


        if($auction->admin){
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->user()->id;
            $adminNotification->title = 'A user has been bidden on your auction product';
            $adminNotification->click_url = urlPath('admin.product.auction.bids',$auction->id);
            $adminNotification->save();

            $notify[] = ['success', 'You are currently the highest bidder.'];
            return back()->withNotify($notify);
        }


        $notify[] = ['success', 'You are currently the highest bidder.'];
        return back()->withNotify($notify);
    }
    
    public function saveAuctionReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'auction_id' => 'required|integer'
        ]);

        Auctionbid::where('user_id', auth()->id())->where('auction_id', $request->auction_id)->firstOrFail();

        $review = Auctionreview::where('user_id', auth()->id())->where('auction_id', $request->auction_id)->first();
        $auction = Auction::find($request->auction_id);

        if(!$review){
            $review = new Auctionreview();
            $auction->total_rating += $request->rating;
            $auction->review_count += 1;
            $notify[] = ['success', 'Review given successfully'];
        }else{
            $auction->total_rating = $auction->total_rating - $review->rating + $request->rating;
            $notify[] = ['success', 'Review updated successfully'];
        }

        $auction->avg_rating = $auction->total_rating / $auction->review_count;
        $auction->save();

        $review->rating = $request->rating;
        $review->description = $request->description;
        $review->user_id = auth()->id();
        $review->auction_id = $request->auction_id;
        $review->save();

        return back()->withNotify($notify);

    }
}