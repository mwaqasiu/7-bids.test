<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Auction;
use App\Models\Winner;
use App\Models\Auctionwinner;
use Illuminate\Http\Request;

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
        $categories     = Category::with('products')->where('status', 1)->get();

        $products       = Product::live();
        $products       = $products->where('name', 'like', '%'.request()->search_key.'%')->with('category');
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

        return view($this->activeTemplate.'search.list', compact('pageTitle', 'emptyPMessage', 'emptyAMessage', 'products', 'allProducts', 'auctions', 'allAuctions', 'categories'));
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
        $products       = Product::live()->whereNotIn('id', Winner::select('product_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');
        $auctions       = Auction::live()->whereNotIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%');

        if($request->sorting){
            if($request->sorting == "arrivals") {
                $sdate=date_create(now());
                date_sub($sdate, date_interval_create_from_date_string("2days"));
                $products = $products->orderBy('expired_at', 'ASC')->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"));
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"));
            } else if($request->sorting == "excellent") {
                $products = $products->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Condition","value":null%');
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Condition","value":null%');
            } else if($request->sorting == "certificated") {
                $products = $products->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Certificated","value":null%');
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Certificated","value":null%');
            } else if($request->sorting == "mentioned") {
                $products = $products->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Literature","value":null%');
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Literature","value":null%');
            } else if($request->sorting == "limited") {
                $products = $products->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Edition","value":null%');
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Edition","value":null%');
            } else if($request->sorting == "noteworthy") {
                $products = $products->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Provenance","value":null%');
                $auctions = $auctions->orderBy('expired_at', 'ASC')->where('specification', 'not like', '%"name":"Provenance","value":null%');
            } else if($request->sorting == "created_at") {
                $products = $products->orderBy('created_at', 'DESC');
                $auctions = $auctions->orderBy('expired_at', 'ASC');
            } else if($request->sorting == "sold") {
                $products = Product::expired()->whereIn('id', Winner::select('product_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%')->orderBy('created_at', 'DESC');
                $auctions = Auction::expired()->whereIn('id', Auctionwinner::select('auction_id as id')->get())->where('name', 'like', '%'.$request->search_key.'%')->orderBy('created_at', 'DESC');
            } else {
                $products->orderBy($request->sorting, 'ASC');
                $auctions->orderBy($request->sorting, 'ASC');
            }
        }
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

        return view($this->activeTemplate.'search.filtered', compact('pageTitle', 'emptyAMessage', 'emptyPMessage', 'products', 'auctions'));
    }

}
