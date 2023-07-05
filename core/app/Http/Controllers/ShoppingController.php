<?php

namespace App\Http\Controllers;

use App\Models\Shopping;
use App\Models\Paymentmethod;
use App\Models\Wishlist;
use App\Models\Winner;
use App\Models\Product;
use App\Models\Bid;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function index($uid, $cip) {
        $pageTitle = "Shopping Cart";
        $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
        $checkoutprofile = [];
        $checkexist = '';
        if($uid == 'empty') {
            $shoppings = Shopping::query();
            $shoppings = $shoppings->with('product')->leftJoin("products","shoppings.product_id", "=", "products.id")->select("shoppings.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.short_description")->where('shoppings.ip_address', $cip)->with('merchant', 'admin')->orderBy('shoppings.id', 'DESC')->get();
            $groupshoppings = Shopping::query()->with('product')->leftJoin("products", "shoppings.product_id", "=", "products.id")->select("shoppings.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.short_description")->where('shoppings.ip_address', $cip)->with('merchant', 'admin')->groupBy('admin_id')->groupBy('merchant_id')->orderBy('shoppings.id', 'DESC')->get();
        } else {
            $shoppings = Shopping::query();
            $shoppings = $shoppings->with('product')->leftJoin("products","shoppings.product_id", "=", "products.id")->select("shoppings.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.short_description")->where('shoppings.user_id', $uid)->orWhere('shoppings.ip_address', $cip)->with('merchant', 'admin')->orderBy('shoppings.id', 'DESC')->get();
            $groupshoppings = Shopping::query()->with('product')->leftJoin("products", "shoppings.product_id", "=", "products.id")->select("shoppings.*", "products.id AS pid", "products.name", "products.image", "products.merchant_id", "products.admin_id", "products.price", "products.short_description")->where('shoppings.user_id', $uid)->orWhere('shoppings.ip_address', $cip)->with('merchant', 'admin')->groupBy('admin_id')->groupBy('merchant_id')->orderBy('shoppings.id', 'DESC')->get();
            $checkexist = Checkout::where('user_id', Auth::user()->id)->exists();
            if($checkexist) {
                $checkoutprofile = Checkout::where('user_id', Auth::user()->id)->get();
            }
        }
        $emptyMessage   = 'Your shopping cart is empty.';
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate.'shopping.index', compact('pageTitle', 'shoppings', 'paymentmethods', 'checkoutprofile', 'checkexist', 'groupshoppings', 'emptyMessage', 'countries'));
    }
    
    public function savewinner(Request $request) {
        $winner = Winner::where('product_id', $request->pid)->exists();
        if($winner) {
            $shopping = Shopping::findOrFail($request->id);
            $product_id = $shopping->product_id;
            $shopping->delete();
            Shopping::where('product_id', $product_id)->delete();
            return "exist";
        }
        $user = auth()->user();
        $product = Product::with('merchant', 'admin')->findOrFail($request->pid);
        
        if(strpos(strtolower($request->paymentmethod), "wise") > -1) {
            $trx1 = getTrx();
            
            $transaction1 = new Transaction();
            $transaction1->user_id = $user->id;
            $transaction1->amount = 50;
            $transaction1->post_balance = User::findOrFail($user->id)->bonuspoint + 50;
            $transaction1->charge = 0;
            $transaction1->trx_type = 'bonus_plus';
            $transaction1->details = 'WISE payment';
            $transaction1->trx =  $trx1;
            $transaction1->save();
            
            $userss = User::findOrFail($user->id);
            $userss->bonuspoint += 50;
            $userss->save();
        }
        
        $bid = new Bid();
        $bid->product_id = $request->pid;
        $bid->user_id = $user->id;
        $bid->amount = $product->price;
        $bid->save();
        
        $winner = new Winner();
        $winner->user_id = $user->id;
        $winner->product_id = $request->pid;
        $winner->bid_id = $bid->id;
        $winner->save();
        
        $trx = getTrx();
        
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = 50;
        $transaction->post_balance = User::findOrFail($user->id)->bonuspoint + 50;
        $transaction->charge = 0;
        $transaction->trx_type = 'bonus_plus';
        $transaction->details = 'Marketplace Order';
        $transaction->trx =  $trx;
        $transaction->save();
        
        $users = User::findOrFail($user->id);
        $users->bonuspoint += 50;
        $users->save();
        
        $userssss = User::findOrFail($user->id);
        $userssss->balance -= $product->price;
        $userssss->save();
        
        $trx2 = getTrx();
        
        $transaction2 = new Transaction();
        $transaction2->user_id = $user->id;
        $transaction2->amount = $product->price;
        $transaction2->post_balance = User::findOrFail($user->id)->balance;
        $transaction2->trx_type = '-';
        $transaction2->details = "Winning bid ".$product->name;;
        $transaction2->trx =  $trx2;
        $transaction2->save();
        
        $shopping = Shopping::findOrFail($request->id);
        $product_id = $shopping->product_id;
        $shopping->delete();
        Shopping::where('product_id', $product_id)->delete();
        return "success";
    }
    
    public function unlogin() {
        $notify[] = ['warning', 'You must log-in to place bids.'];
        return back()->withNotify($notify);
    }
    
    public function unadd() {
        $notify[] = ['warning', 'Please Login in!'];
        return back()->withNotify($notify);
    }
    
    public function shopadd($pid, $uid, $cip) {
        if($uid == 'empty') {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return back()->withNotify($notify);
            }
        } else {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->user_id = $uid;
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return back()->withNotify($notify);
            }
        }
    }
    
    public function product_shopping_add($pid, $uid, $cip) {
        if($uid == 'empty') {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return redirect()->route('user.shopping-cart', [$uid, $cip])->withNotify($notify);
            }
        } else {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->user_id = $uid;
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return redirect()->route('user.shopping-cart', [$uid, $cip])->withNotify($notify);
            }
        }
    }
    
    public function add($pid, $uid, $cip, $wid) {
        if($uid == 'empty') {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $wishlist = Wishlist::findOrFail($wid);
                $wishlist->delete();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return back()->withNotify($notify);
            }
        } else {
            $shoppings = Shopping::query()->where('ip_address', $cip)->where('product_id', $pid)->get();
            if(count($shoppings) > 0)
            {
                $notify[] = ['warning', 'The item already exists.'];
                return back()->withNotify($notify);
            }
            else {
                $shopping = new Shopping();
                $shopping->user_id = $uid;
                $shopping->ip_address = $cip;
                $shopping->product_id = $pid;
                $shopping->save();
                
                $wishlist = Wishlist::findOrFail($wid);
                $wishlist->delete();
                
                $notify[] = ['success', 'Item successfully moved to the shopping cart.'];
                return back()->withNotify($notify);
            }
        }
    }
    
    public function deleteitem(Request $request) {
        $shopping = Shopping::findOrFail($request->shop_id);
        $shopping->delete();
        $notify[] = ['success', 'Item removed successfully.'];
        return back()->withNotify($notify);
    }
    
}