<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Auctionwishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function __construct() {
        $this->activeTemplate = activeTemplate();
    }
    
    public function index($uid, $cip) {
        $pageTitle = "Wish List";
        
        if($uid == 'empty') {
            $wishlists = Wishlist::query()->with('product')->leftJoin("products","wishlists.product_id", "=", "products.id")->select("wishlists.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.long_description")->where('wishlists.ip_address', $cip)->with('admin', 'merchant')->orderBy('wishlists.id', 'DESC')->get();
            $auctionwishlists = Auctionwishlist::query()->leftJoin("auctions","auctionwishlists.auction_id", "=", "auctions.id")->select("auctionwishlists.*", "auctions.id AS aid", "auctions.name", "auctions.image", "auctions.price", "auctions.long_description")->where('auctionwishlists.ip_address', $cip)->orderBy('auctionwishlists.id', 'DESC')->get();
        } else {
            $wishlists = Wishlist::query()->with('product')->leftJoin("products","wishlists.product_id", "=", "products.id")->select("wishlists.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.long_description")->where('wishlists.user_id', $uid)->orWhere('wishlists.ip_address', $cip)->with('admin', 'merchant')->orderBy('wishlists.id', 'DESC')->get();
            $auctionwishlists = Auctionwishlist::query()->leftJoin("auctions","auctionwishlists.auction_id", "=", "auctions.id")->select("auctionwishlists.*", "auctions.id AS aid", "auctions.name", "auctions.image", "auctions.price", "auctions.long_description")->where('auctionwishlists.user_id', $uid)->orWhere('auctionwishlists.ip_address', $cip)->orderBy('auctionwishlists.id', 'DESC')->get();
        }
        
        $emptyMessage   = 'Your wish list is empty.';
        return view($this->activeTemplate.'wishlist.index', compact('pageTitle', 'wishlists', 'auctionwishlists', 'emptyMessage'));
    }
    
    public function auctionadd($aid, $uid, $cip) {
        if($uid == "empty") {
            $auctionwishlists = Auctionwishlist::query()->where('ip_address', $cip)->where('auction_id', $aid)->get();
            if(count($auctionwishlists) > 0) {
                $notify[] = ['warning', 'The item is already on your wishlist.'];
                return back()->withNotify($notify);
            } else {
                $auctionwishlists = new Auctionwishlist();
                $auctionwishlists->ip_address = $cip;
                $auctionwishlists->auction_id = $aid;
                $auctionwishlists->save();
                
                $notify[] = ['success', 'Item added to your wish list.'];
                return back()->withNotify($notify);
            }
        } else {
            $auctionwishlists = Auctionwishlist::query()->where('ip_address', $cip)->where('auction_id', $aid)->get();
            if(count($auctionwishlists) > 0) {
                $notify[] = ['warning', 'The item is already on your wishlist.'];
                return back()->withNotify($notify);
            } else {
                $auctionwishlists = new Auctionwishlist();
                $auctionwishlists->user_id = $uid;
                $auctionwishlists->ip_address = $cip;
                $auctionwishlists->auction_id = $aid;
                $auctionwishlists->save();
                
                $notify[] = ['success', 'Item added to your wish list.'];
                return back()->withNotify($notify);
            }
        }
    }
    
    public function add($pid, $uid, $cip) {
        if($uid == 'empty') {
            $wishlists = Wishlist::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($wishlists) > 0) {
                $notify[] = ['warning', 'The item is already on your wishlist.'];
                return back()->withNotify($notify);
            } else {
                $wishlist = new Wishlist();
                $wishlist->ip_address = $cip;
                $wishlist->product_id = $pid;
                $wishlist->save();
                
                $notify[] = ['success', 'Item added to your wish list.'];
                return back()->withNotify($notify);
            }
        } else {
            $wishlists = Wishlist::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($wishlists) > 0) {
                $notify[] = ['warning', 'The item is already on your wishlist.'];
                return back()->withNotify($notify);
            } else {
                $wishlist = new Wishlist();
                $wishlist->user_id = $uid;
                $wishlist->ip_address = $cip;
                $wishlist->product_id = $pid;
                $wishlist->save();
                
                $notify[] = ['success', 'Item added to your wish list.'];
                return back()->withNotify($notify);
            }
        }
    }
    
    public function auctiondeleteitem(Request $request) {
        $auctionwishlist = Auctionwishlist::findOrFail($request->awish_id);
        $auctionwishlist->delete();
        $notify[] = ['success', 'Item removed successfully.'];
        return back()->withNotify($notify);
    }
    
    public function deleteitem(Request $request) {
        $wishlist = Wishlist::findOrFail($request->wish_id);
        $wishlist->delete();
        $notify[] = ['success', 'Item removed successfully.'];
        return back()->withNotify($notify);
    }
    
    public function deleteoneitem($id) {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();
        $notify[] = ['success', 'Item successfully removed from wish list.'];
        return back()->withNotify($notify);
    }
    
    public function auctiononedeleteitem($id) {
        $wishlist = Auctionwishlist::findOrFail($id);
        $wishlist->delete();
        $notify[] = ['success', 'Item successfully removed from wish list.'];
        return back()->withNotify($notify);
    }
}
