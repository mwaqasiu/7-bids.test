<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\Product;
use App\Models\Auction;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\Auctionwinner;
use App\Models\Transaction;
use App\Models\Merchant;
use App\Models\Winner;
use App\Models\Searchlist;
use App\Models\UserNotification;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    
    protected function filterAuctions($type) {
        if($type != 'all') {
            $auctions = Auction::query()->$type();
            if(request()->search){
            $search  = request()->search;

            $auctions = $auctions->where(function($qq) use ($search){
                    $qq->where('name', 'like', '%'.$search.'%')->orWhere(function($auction) use($search){
                        $auction->whereHas('merchant', function ($merchant) use ($search) {
                            $merchant->where('username', 'like',"%$search%");
                        })->orWhereHas('admin', function ($admin) use ($search) {
                            $admin->where('username', 'like',"%$search%");
                        });
                    });
                });
            }
        
            return $auctions->with('category')->where('merchant_id', auth()->guard('merchant')->id())->orderBy('admin_id', 'DESC')->latest()->paginate(getPaginate());
        }
        else {
            return [];
        }
    }
    
    public function storestatusimg(Request $request)
    {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        if($request->stype == "pending") {
            $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
            
            if($winner->pending_imageurl == null || $winner->pending_imageurl == "") {
                $userNotification = new UserNotification();
                $userNotification->user_id = $winner->user_id;
                $userNotification->n_user_id = $winner->user_id;
                $userNotification->product_id = $winner->product_id;
                $userNotification->title = 'Status changed to "Waiting for Payment"';
                $userNotification->click_url = "productchangingstatus";
                $userNotification->save();
            }
            
            $winner->pending_imageurl = $winner->pending_imageurl.$oneimgurl.",";
            $winner->save();
        } else if($request->stype == "paid") {
            $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
            $winner->paid_imageurl = $winner->paid_imageurl.$oneimgurl.",";
            $winner->save();
        } else if($request->stype == "picked")
        {
            $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
            $winner->picked_imageurl = $winner->picked_imageurl.$oneimgurl.",";
            $winner->save();
        } else if($request->stype == "packed")
        {
            $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
            $winner->packed_imageurl = $winner->packed_imageurl.$oneimgurl.",";
            $winner->save();
        }
    }

    protected function filterProducts($type){

        $products = Product::query();
        if($type == "all") {
            $this->pageTitle = "My Marketplace Offers";
        }
        else if($type == "live") {
            $this->pageTitle = "My Auction Offers";
        }
        else {
            $this->pageTitle    = 'My '.ucfirst($type). ' Offers';
        }
        $this->emptyMessage = 'No Offers found';
        
        if($type == 'all') {
            $products = $products->live();
        }

        if($type != 'all'){
            $products = $products->$type();
        }
        
        if(request()->search){
            $search  = request()->search;
            $products    = $products->orWhere('name', 'like', '%'.$search.'%')
                                    ->orWhereHas('merchant', function ($merchant) use ($search) {
                                            $merchant->where('username', 'like',"%$search%");
                                        })->orWhereHas('admin', function ($admin) use ($search) {
                                            $admin->where('username', 'like',"%$search%");
                                        });

            $this->pageTitle    = "Search Result for '$search'";
        }
        
        return $products->leftJoin("bids", "bids.product_id", '=', 'products.id')->select("products.*", DB::raw("MAX(bids.amount) as bestoffer"))->groupBy('products.id')->with('category')->where('merchant_id', auth()->guard('merchant')->id())->whereNotIn('products.id', Winner::query()->select('product_id')->get())->latest()->paginate(getPaginate());
    }

    public function index()
    {
        $segments       = request()->segments();
        $type = end($segments);
        $products       = $this->filterProducts(end($segments));
        $auctions       = $this->filterAuctions(end($segments));
        $pageTitle      = $this->pageTitle;
        $emptyMessage   = $this->emptyMessage;
        return view('merchant.product.index', compact('pageTitle', 'emptyMessage', 'products', 'auctions', 'type'));
    }

    public function create(){
        $pageTitle = 'Create Marketplace Item';
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        return view('merchant.product.create', compact('pageTitle', 'categories'));
    }

    public function edit($id){
        $pageTitle = 'Update Product';
        $categories = Category::where('status', 1)->get();
        $product = Product::findOrFail($id);

        return view('merchant.product.edit', compact('pageTitle', 'categories', 'product'));
    }

    public function startagain($id) {
        $product = Product::findOrFail($id);
        $product->started_at = now();
        $sdate=date_create(now());
        date_add($sdate,date_interval_create_from_date_string($product->schedule));
        $product->expired_at = date_format($sdate,"Y-m-d H:i:s");
        $product->save();
        $notify[] = ['success', 'Setting is successfully'];
        return back()->withNotify($notify);
    }
    
    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $product = Product::findOrFail($request->id);
        $product->status = 1;
        $product->save();

        $notify[] = ['success', 'Product Approved Successfully'];
        return back()->withNotify($notify);
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');
        $product            = new Product();
        $product->status = 1;
        $this->saveProduct($request, $product);
        
        $user = auth()->guard('merchant')->user();
        $merchant = Merchant::findOrFail($user->id);
        if ($request->schedule == "") {
            $merchant->balance -= 0.5;
            $merchant->save();
        } else if($request->schedule == "1weeks") {
            $merchant->balance -= 0.5;
            $merchant->save();
        } else if($request->schedule == "2weeks") {
            $merchant->balance -= 0.7;
            $merchant->save();
        } else if($request->schedule == "3weeks") {
            $merchant->balance -= 0.9;
            $merchant->save();
        } else if($request->schedule == "4weeks") {
            $merchant->balance -= 1;
            $merchant->save();
        }
        
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->merchant_id = $merchant->id;
        if ($request->schedule == "") {
            $transaction->amount = 0.5;
        } else if($request->schedule == "1weeks") {
            $transaction->amount = 0.5;
        } else if($request->schedule == "2weeks") {
            $transaction->amount = 0.7;
        } else if($request->schedule == "3weeks") {
            $transaction->amount = 0.9;
        } else if($request->schedule == "4weeks") {
            $transaction->amount = 1;
        }
        $transaction->post_balance = $merchant->balance;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Marketplace Offer Fee';
        $transaction->trx =  $trx;
        $transaction->save();
        
        $sdate=date_create(now());
        date_sub($sdate, date_interval_create_from_date_string("2days"));
        $products = Product::where('name', $request->name)->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->orderBy('id', 'DESC')->limit(10)->get();
        $auctions = Auction::where('name', $request->name)->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->orderBy('id', 'DESC')->limit(10)->get();
        
        $products_count = Product::where('name', $request->name)->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->orderBy('id', 'DESC')->limit(10)->count();
        $auctions_count = Auction::where('name', $request->name)->where('created_at', '>=', date_format($sdate,"Y-m-d H:i:s"))->orderBy('id', 'DESC')->limit(10)->count();
        
        $counts = $products_count + $auctions_count;
        
        $searchlists = Searchlist::where('search_name', $request->name)->with('user')->get();
        
        $general = GeneralSetting::first();
        
        $emailTemplate = EmailTemplate::where('act', "SEARCH_ITEM")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $attachments="";
        
        if(!empty($products)) {
            foreach($products as $key => $product) {
                $attachments = $attachments.'<tr>
  <td
	align="right"
	style="
	  padding-right: 10px;
	  font-family: Open sans, Arial, sans-serif;
	  color: #7f8c8d;
	  font-size: 16px;
	  padding-bottom: 15px;
	  width: 50%;
	"
  >
	'.$product->name.'
  </td>
  <td
	align="left"
	style="
	  font-family: Open sans, Arial, sans-serif;
	  color: #7f8c8d;
	  font-size: 16px;
	  padding-bottom: 15px;
	  width: 50%;
	"
  >
	<img
	  src="'.getImage(imagePath()['product']['path'].'/'.$product->image).'"
	  width="80px"
	  height="50px"
	/>
  </td>
</tr>';
            }
        }
        
        if(!empty($auctions)) {
            foreach($auctions as $key => $auction) {
                $attachments = $attachments.'<tr>
  <td
	align="right"
	style="
	  padding-right: 10px;
	  font-family: Open sans, Arial, sans-serif;
	  color: #7f8c8d;
	  font-size: 16px;
	  padding-bottom: 15px;
	  width: 50%;
	"
  >
	'.$auction->name.'
  </td>
  <td
	align="left"
	style="
	  font-family: Open sans, Arial, sans-serif;
	  color: #7f8c8d;
	  font-size: 16px;
	  padding-bottom: 15px;
	  width: 50%;
	"
  >
	<img
	  src="'.getImage(imagePath()['product']['path'].'/'.$auction->image).'"
	  width="80px"
	  height="50px"
	/>
  </td>
</tr>';
            }
        }
        
        $newmatchurl = "https://1400g.de/search-products?search_key=".$request->name;
        
        $message = shortCodeReplacer("{{attachment}}", $attachments, $emailTemplate->email_body);
        $message = shortCodeReplacer("{{title}}", $request->name, $message);
        $message = shortCodeReplacer("{{newmatchurl}}", $newmatchurl, $message);
        $message = shortCodeReplacer("{{count}}", $counts, $message);
        
        $adminmail = $general->email_from;
        
        $subject = $request->name.": ".$counts." new item(s) matching your search";
        
        if(!empty($searchlists)) {
            foreach($searchlists as $key => $searchlist) {
                $receive_mail = explode('@', $searchlist->user->email)[0];
                
                try {
                    sendSellusEmail($searchlist->user->email, $subject, $message, $receive_mail);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Invalid credential'];
                    return back()->withNotify($notify);
                }
            }
        }
        
        $notify[] = ['success', 'Marketplace Item added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, 'nullable');
        $product = Product::findOrFail($id);
        
        $this->saveProduct($request, $product);
        $notify[] = ['success', 'Product updated successfully'];
        return back()->withNotify($notify);
    }
    
    public function storeoneimg(Request $request)
    {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        return $oneimgurl;
    }

    public function saveProduct($request, $product)
    {
        if ($request->hasFile('image')) {
            try {
                $product->image = uploadImage($request->image, imagePath()['product']['path'], imagePath()['product']['size'], $product->image, imagePath()['product']['thumb']);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $product->name = $request->name;
        $product->category_id = $request->category;
        $product->merchant_id = auth()->guard('merchant')->id();
        if ($request->schedule == "") {
            $product->schedule = "1weeks";
        } else {
            $product->schedule = $request->schedule;
        }
        $product->price = $request->price;
        $product->started_at = $request->started_at ?? now();
        $sdate=date_create($request->started_at ?? now());
        if ($request->schedule == "") {
            date_add($sdate,date_interval_create_from_date_string("1weeks"));
            $product->expired_at = date_format($sdate,"Y-m-d H:i:s");
        } else {
            date_add($sdate,date_interval_create_from_date_string($request->schedule));
            $product->expired_at = date_format($sdate,"Y-m-d H:i:s");
        }
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->specification = $request->specification ?? null;
        $product->imagereplaceinput = $request->imagereplaceinput ?? null;

        $product->save();
    }


    protected function validation($request, $imgValidation){
        $request->validate([
            'name'                  => 'required',
            'category'              => 'required|exists:categories,id',
            'price'                 => 'required|numeric|gte:0',
            'schedule'            => 'required',
            'long_description'      => 'required',
            'specification'         => 'nullable|array',
            'imagereplaceinput'     => 'nullable|array',
            // 'started_at'            => 'required_if:schedule,1|date|after:yesterday|before:expired_at',
            'image'                 => [$imgValidation,'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);
    }

    public function productBids($id){
        $product = Product::with('winner')->findOrFail($id);
        $pageTitle = $product->name.' Bids';
        $emptyMessage = $product->name.' has no bid yet';
        $bids = Bid::where('product_id', $id)->with('user', 'product', 'winner')->withCount('winner')->orderBy('winner_count', 'DESC')->latest()->paginate(getPaginate());
        $winner = $product->winner;
        
        return view('merchant.product.product_bids', compact('pageTitle', 'emptyMessage', 'bids', 'winner'));
    }

    public function bidWinner(Request $request){
        $request->validate([
            'bid_id' => 'required'
        ]);
        
        $bid = Bid::with('user', 'product')
        ->whereHas('product', function($product){
           $product->where('merchant_id', auth()->guard('merchant')->id());
        })->findOrFail($request->bid_id);

        $product = $bid->product;

        $winner = Winner::where('product_id', $product->id)->exists();

        if($winner){
            $notify[] = ['error', 'Winner for this product id already selected'];
            return back()->withNotify($notify);
        }

        // if($product->expired_at > now()){
        //     $notify[] = ['error', 'This product is not expired till now'];
        //     return back()->withNotify($notify);
        // }

        $winner = new Winner();
        $winner->user_id = $bid->user_id;
        $winner->product_id = $bid->product_id;
        $winner->bid_id = $bid->id;
        $winner->save();

        $notify[] = ['success', 'Winner published successfully'];
        return back()->withNotify($notify);
    }
    
    public function bidpriceofferdecline($bid_id) {
        $winner = Winner::where('bid_id', $bid_id)->exists();
        if($winner) {
            $winners = Winner::where('bid_id', $bid_id)->get();
            $winnerdel = Winner::findOrFail($winners[0]->id);
            $winnerdel->delete();
            $bid = Bid::findOrFail($bid_id);
            
            $userNotification = new UserNotification();
            $userNotification->n_user_id = $bid->user_id;
            $userNotification->product_id = $bid->product_id;
            $userNotification->title = 'Unfortunately the seller not accepted your offer.';
            $userNotification->click_url = "";
            $userNotification->save();
            
            $bid->delete();
            $notify[] = ['success', 'Price offer declined.'];
            return back()->withNotify($notify);
        } else {
            $bid = Bid::findOrFail($bid_id);
            
            $userNotification = new UserNotification();
            $userNotification->n_user_id = $bid->user_id;
            $userNotification->product_id = $bid->product_id;
            $userNotification->title = 'Unfortunately the seller not accepted your offer.';
            $userNotification->click_url = "";
            $userNotification->save();
            
            $bid->delete();
            $notify[] = ['success', 'Price offer declined.'];
            return back()->withNotify($notify);
        }
    }
    
    public function bidpriceofferwinner($bid_id) {
        $bid = Bid::with('user', 'product')
        ->whereHas('product', function($product) {
           $product->where('merchant_id', auth()->guard('merchant')->id());
        })->findOrFail($bid_id);

        $product = $bid->product;

        $winner = Winner::where('product_id', $product->id)->exists();

        if($winner){
            $notify[] = ['error', 'Winner for this product id already selected'];
            return back()->withNotify($notify);
        }

        // if($product->expired_at > now()){
        //     $notify[] = ['error', 'This product is not expired till now'];
        //     return back()->withNotify($notify);
        // }

        $winner = new Winner();
        $winner->user_id = $bid->user_id;
        $winner->product_id = $bid->product_id;
        $winner->bid_id = $bid->id;
        $winner->save();

        $notify[] = ['success', 'You accepted the price offer.'];
        return back()->withNotify($notify);
    }

    public function bids()
    {
        $pageTitle    = 'Price Offers';
        $emptyMessage = 'No bids found';
        $bids = Bid::with('user', 'product')->whereHas('product', function($product){
            $product->where('merchant_id', auth()->guard('merchant')->id());
        })->whereNotIn('product_id', Winner::select('product_id')->get())->latest()->paginate(getPaginate());

        return view('merchant.product.bids', compact('pageTitle', 'emptyMessage', 'bids'));
    }

    public function productWinner(){
        $pageTitle = 'Sold Items';
        $emptyMessage = 'No winner found';
        $auctionwinners = Auctionwinner::with('auction', 'user', 'auctionbid', 'checkout')->whereHas('auction', function($auction) {
            $auction->where('merchant_id', auth()->guard('merchant')->id());
        })->latest()->paginate(getPaginate());
        $winners = Winner::with('product', 'user', 'bid', 'checkout')
        ->whereHas('product', function($product){
            $product->where('merchant_id', auth()->guard('merchant')->id());
        })
        ->latest()->paginate(getPaginate());
        
        return view('merchant.product.winners', compact('pageTitle', 'emptyMessage', 'winners', 'auctionwinners'));
    }

    public function deliveredProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product', function($product){
            $product->where('merchant_id', auth()->guard('merchant')->id());
        })->findOrFail($request->id);
        $winner->product_delivered = 1;
        $winner->save();

        $notify[] = ['success', 'Product mark as delivered'];
        return back()->withNotify($notify);

    }
    
    public function deleteProduct(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        unlink(imagePath()['product']['path']."/".$product->image);
        unlink(imagePath()['product']['path']."/thumb_".$product->image);
        
        foreach($product->imagereplaceinput as $imgri) {
            unlink(imagePath()['product']['path']."/".$imgri['url']);
        }
        
        $product->delete();
        
        $notify[] = ['success', 'Product Deleted Successfully'];
        return back()->withNotify($notify);
    }
}
