<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Auction;
use App\Models\Winner;
use App\Models\Auctionwinner;
use App\Models\Wishlist;
use App\Models\Auctionwishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SearchproductController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    
    public function searchproducts()
    {
        $pageTitle      = 'Search Items';
        $emptyPMessage  = 'No Marketplace Item found';
        $emptyAMessage  = 'No Auction Item found';
        $wishlists = Wishlist::all();
        $auctionwishlists = Auctionwishlist::all();
        $categories     = Category::with('products')->where('status', 1)->get();

        $products       = Product::live();
        
        //$products       = $products->where('name', 'like', '%'.request()->search_key.'%')->with('category');
        $products       = $products->where('name', 'like', '%'.request()->search_key.'%')->where('status', '!=', 0)->where('started_at', '<', Carbon::now())->where('expired_at', '>', Carbon::now())->live()->doesnthave('winner')->with('category');
        $allProducts    = clone $products->live()->get();
        if(request()->category_id){
            $products   = $products->where('category_id', request()->category_id);
        }
        $products = $products->orderBy('expired_at', 'ASC')->paginate(getPaginate(18));

        $auctions       = Auction::live();
        $auctions       = $auctions->where('name', 'like', '%'.request()->search_key.'%')->with('category');
        $allAuctions    = clone $auctions->live()->get();
        if(request()->category_id) {
            $auctions   = $auctions->where('category_id', request()->category_id);
        }
        $auctions = $auctions->orderBy('expired_at', 'ASC')->paginate(getPaginate(18));

        return view($this->activeTemplate.'search.list', compact('pageTitle', 'wishlists', 'auctionwishlists', 'emptyPMessage', 'emptyAMessage', 'products', 'allProducts', 'auctions', 'allAuctions', 'categories'));
    }
    
    public function searchlistproducts(Request $request) {
        $pageTitle      = 'Search Items';
        $emptyPMessage  = 'No Marketplace Item found';
        $emptyAMessage  = 'No Auction Item found';
        $categories     = Category::with('products')->where('status', 1)->get();
        
        $sdate=date_create(now());
        date_sub($sdate, date_interval_create_from_date_string("2days"));

        $products       = Product::live();
        $products       = $products->where('name', 'like', '%'.request()->search_key.'%')->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->with('category');
        $allProducts    = clone $products->get();
        if(request()->category_id){
            $products   = $products->where('category_id', request()->category_id);
        }
        $products = $products->paginate(getPaginate(18));
        
        $auctions       = Auction::live();
        $auctions       = $auctions->where('name', 'like', '%'.request()->search_key.'%')->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->with('category');
        $allAuctions    = clone $auctions->get();
        if(request()->category_id) {
            $auctions   = $auctions->where('category_id', request()->category_id);
        }
        $auctions = $auctions->paginate(getPaginate(18));

        return view($this->activeTemplate.'search.list', compact('pageTitle', 'emptyPMessage', 'emptyAMessage', 'products', 'allProducts', 'auctions', 'allAuctions', 'categories'));
    }
    
    public function searchlistfilter(Request $request) {
        $pageTitle = "Search Items";
        $emptyPMessage = "No Marketplace Item found";
        $emptyAMessage = "No Auction Item found";
        $products = Product::live()->where('name', 'like', '%'.$request->search_key.'%');
        $auctions = Auction::live()->where('name', 'like', '%'.$request->search_key.'%');
        
        $sdate=date_create(now());
        date_sub($sdate, date_interval_create_from_date_string("2days"));
        $products = $products->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"));
        $auctions = $auctions->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"));
        
        if($request->categories){
            $products->whereIn('category_id', $request->categories);
            $auctions->whereIn('category_id', $request->categories);
        }
        if($request->minPrice){
            $products->where('price', '>=', $request->minPrice);
            $auctions->where('price', '>=', $request->minPrice);
        }
        if($request->maxPrice){
            $products->where('price', '<=', $request->maxPrice);
            $auctions->where('price', '<=', $request->maxPrice);
        }
        
        $products = $products->paginate(getPaginate(18));
        $auctions = $auctions->paginate(getPaginate(18));

        return view($this->activeTemplate.'search.listfiltered', compact('pageTitle', 'emptyAMessage', 'emptyPMessage', 'products', 'auctions'));
    }
    
    public function searchfilter(Request $request)
    {
        $pageTitle      = 'Search Items';
        $emptyPMessage  = 'No Marketplace Item found';
        $emptyAMessage  = 'No Auction Item found';
        
        
        $wishlists = Wishlist::all();
        $products       = Product::live()->whereNotIn('id', Winner::select('product_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');
        $winnertext = "";
        $categories = Category::with('products')->where('status', 1)->get();
        $allProducts = clone $products->get();

        if($request->timing == "sold") {
            $winnertext = "winner";
            $products = Product::whereIn('products.id', Winner::select('product_id as id')->get())->leftJoin("winners", "winners.product_id", '=', 'products.id')->select("products.*", DB::raw("winners.bid_id as wbid_id"))->leftJoin("bids", "bids.id", '=', 'winners.bid_id')->select("products.*", DB::raw("bids.amount as soldamount"))->where('products.name', 'like', '%'.$request->search_key.'%');
            
            if(!empty($request->sorting)) {
                if($request->sorting['excellent'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Condition","value":null%');
                }
                if($request->sorting['certificated'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Certificated","value":null%');
                }
                if($request->sorting['mentioned'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Literature","value":null%');
                }
                if($request->sorting['limited'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Edition","value":null%');
                }
                if($request->sorting['noteworthy'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Provenance","value":null%');
                }
            }

            if($request->date) {
                if($request->date == "created_at_desc") {
                    $products = $products->orderBy('products.created_at', 'DESC');
                } else {
                    $products = $products->orderBy('products.created_at', 'ASC');
                }
            }

            if($request->price){
                if($request->price == "created_at_desc") {
                    $products = $products->orderBy('price', 'DESC');
                } else {
                    $products = $products->orderBy('price', 'ASC');
                }
            }

            if($request->categories){
                $products = $products->whereIn('category_id', $request->categories);
            }
        } else if($request->timing == "arrivals") {
            $sdate=date_create(now());
            date_sub($sdate, date_interval_create_from_date_string("2days"));
            $products = $products->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"));

            if(!empty($request->sorting)) {
                if($request->sorting['excellent'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Condition","value":null%');
                }
                if($request->sorting['certificated'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Certificated","value":null%');
                }
                if($request->sorting['mentioned'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Literature","value":null%');
                }
                if($request->sorting['limited'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Edition","value":null%');
                }
                if($request->sorting['noteworthy'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Provenance","value":null%');
                }
            }

            if($request->date) {
                if($request->date == "created_at_desc") {
                    $products = $products->orderBy('products.created_at', 'DESC');
                } else {
                    $products = $products->orderBy('products.created_at', 'ASC');
                }
            }

            if($request->price){
                if($request->price == "created_at_desc") {
                    $products = $products->orderBy('price', 'DESC');
                } else {
                    $products = $products->orderBy('price', 'ASC');
                }
            }

            if($request->categories){
                $products = $products->whereIn('category_id', $request->categories);
            }
        } else {
            if(!empty($request->sorting)) {
                if($request->sorting['excellent'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Condition","value":null%');
                }
                if($request->sorting['certificated'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Certificated","value":null%');
                }
                if($request->sorting['mentioned'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Literature","value":null%');
                }
                if($request->sorting['limited'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Edition","value":null%');
                }
                if($request->sorting['noteworthy'] == "true") {
                    $products = $products->where('specification', 'not like', '%"name":"Provenance","value":null%');
                }
            }

            if($request->date) {
                if($request->date == "created_at_desc") {
                    $products = $products->orderBy('products.created_at', 'DESC');
                } else {
                    $products = $products->orderBy('products.created_at', 'ASC');
                }
            }

            if($request->price){
               if($request->price == "created_at_desc") {
                    $products = $products->orderBy('price', 'DESC');
               } else {
                    $products = $products->orderBy('price', 'ASC');
               }
            }

            if($request->categories){
                $products = $products->whereIn('category_id', $request->categories);
            }
        }

        if($request->minPrice){
            $products = $products->where('price', '>=', $request->minPrice);
        }
        if($request->maxPrice){
            $products = $products->where('price', '<=', $request->maxPrice);
        }
        $minPrice = $products->min('price');
        $maxPrice = $products->max('price');
        $priceProducts = clone $products->get();
        $products = $products->paginate(getPaginate(18));
        
        $auctionwishlists = Auctionwishlist::all();
        $auctions = Auction::live()->whereNotIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%' . $request->search_key . '%');
        $auctionwinnertext = "";
        $auctionupcomingtext = "";
        $allAuctions = clone $auctions->get();
        if ($request->timing == "sold") {
            $auctionwinnertext = "winner";
            $auctions = Auction::whereIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%' . $request->search_key . '%');

            if ($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }

            if ($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }

            if ($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }

            if ($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }

            if ($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }

            $auctions = $auctions->orderBy('expired_at', 'ASC');

            if ($request->dateprice) {
                if ($request->dateprice == "created_at") {
                    $auctions = $auctions->orderBy('expired_at', 'DESC');
                } else if ($request->dateprice == "created_at_asc") {
                    $auctions = $auctions->orderBy('expired_at', 'ASC');
                } else if ($request->dateprice == "price_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
                }
            }

            if ($request->categories) {
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        } else if ($request->timing == "arrivals") {
            $auctionupcomingtext = "upcoming";
            $auctions = Auction::upcoming()->whereNotIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%' . $request->search_key . '%');

            if ($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }

            if ($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }

            if ($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }

            if ($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }

            if ($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }

            $auctions = $auctions->orderBy('expired_at', 'ASC');

            if ($request->dateprice) {
                if ($request->dateprice == "created_at") {
                    $auctions = $auctions->orderBy('expired_at', 'DESC');
                } else if ($request->dateprice == "created_at_asc") {
                    $auctions = $auctions->orderBy('expired_at', 'ASC');
                } else if ($request->dateprice == "price_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
                }
            }

            if ($request->categories) {
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        } else {
            if ($request->sorting['excellent'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Condition","value":null%');
            }

            if ($request->sorting['certificated'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Certificated","value":null%');
            }

            if ($request->sorting['mentioned'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Literature","value":null%');
            }

            if ($request->sorting['limited'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Edition","value":null%');
            }

            if ($request->sorting['noteworthy'] == "true") {
                $auctions = $auctions->where('specification', 'not like', '%"name":"Provenance","value":null%');
            }

            $auctions = $auctions->orderBy('expired_at', 'ASC');

            
            if($request->date) {
                if($request->date == "created_at_desc") {
                    $auctions = $auctions->orderBy('products.created_at', 'DESC');
                } else {
                    $auctions = $auctions->orderBy('products.created_at', 'ASC');
                }
            }

            if($request->price){
               if($request->price == "created_at_desc") {
                    $auctions = $auctions->orderBy('price', 'DESC');
               } else {
                    $auctions = $auctions->orderBy('price', 'ASC');
               }
            }

            if ($request->categories) {
                $auctions = $auctions->whereIn('category_id', $request->categories);
            }
        }

        $priceAuctions = clone $auctions->get();

        if ($request->minPrice) {
            $auctions = $auctions->where('price', '>=', $request->minPrice);
        }

        if ($request->maxPrice) {
            $auctions = $auctions->where('price', '<=', $request->maxPrice);
        }

        $auctions = $auctions->paginate(getPaginate(18));
        
        
        return view($this->activeTemplate.'search.filtered', compact('pageTitle', 'wishlists', 'auctionwishlists', 'emptyPMessage', 'emptyAMessage', 'products', 'allProducts', 'auctions', 'allAuctions', 'categories'));
    }
    
}