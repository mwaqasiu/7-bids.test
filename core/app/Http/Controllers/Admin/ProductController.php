<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Bid;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\Auction;
use App\Models\User;
use App\Models\Searchlist;
use App\Models\Winner;
use App\Models\Transaction;
use App\Models\UserNotification;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    protected $search;

    protected function filterAuctions($type){
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
        
            return $auctions->with('merchant', 'admin')->orderBy('admin_id', 'DESC')->orderBy('id', 'DESC')->latest()->paginate(getPaginate());
        }
        else {
            return [];
        }
    }

    protected function filterProducts($type){

        $products = Product::query();
        if($type == "all") {
            $this->pageTitle = "Marketplace";
        }
        else if($type == "live") {
            $this->pageTitle = "Auction";
        }
        else {
            $this->pageTitle    = ucfirst($type). ' Products';
        }
        $this->emptyMessage = 'No '.$type. ' products found';

        if($type != 'all'){
            $products = $products->$type();
        }
        
        if($type == "all") {
            $products = $products->live()->whereNotIn('products.id', Winner::select('product_id as id')->get());
        }
        
        if(request()->search){
            $search  = request()->search;
            
            $products    = $products->where(function($qq) use ($search){
                $qq->where('name', 'like', '%'.$search.'%')->orWhere(function($product) use($search){
                    $product->whereHas('merchant', function ($merchant) use ($search) {
                        $merchant->where('username', 'like',"%$search%");
                    })->orWhereHas('admin', function ($admin) use ($search) {
                        $admin->where('username', 'like',"%$search%");
                    });
                });
            });

            $this->pageTitle    = "Search Result for '$search'";
            $this->search = $search;
        }
        
        return $products->with('merchant', 'admin')->leftJoin("bids", "bids.product_id", '=', 'products.id')->select("products.*", DB::raw("MAX(bids.amount) as bestoffer"))->groupBy('products.id')->orderBy('admin_id', 'DESC')->orderBy('id', 'DESC')->latest()->paginate(getPaginate());
    }

    public function index()
    {
        $segments       = request()->segments();
        $products       = $this->filterProducts(end($segments));
        $auctions       = $this->filterAuctions(end($segments));
        $pageTitle      = $this->pageTitle;
        $emptyMessage   = $this->emptyMessage;
        $search         = $this->search;

        return view('admin.product.index', compact('pageTitle', 'emptyMessage', 'products', 'auctions', 'search'));
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

    public function create()
    {
        $pageTitle = 'Create Marketplace Product';
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        return view('admin.product.create', compact('pageTitle', 'categories'));
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

    public function edit($id)
    {
        $pageTitle = 'Update Marketplace Product';
        $categories = Category::where('status', 1)->get();
        $product = Product::findOrFail($id);

        return view('admin.product.edit', compact('pageTitle', 'categories', 'product'));
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');
        $product = new Product();
        $product->admin_id  = auth()->guard('admin')->id();
        $product->status    = 1;
        
        $this->saveProduct($request, $product);
        
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
        
        $notify[] = ['success', 'Marketplace product added successfully'];
        return back()->withNotify($notify);
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
    
    public function storeoneimg(Request $request) {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        return $oneimgurl;
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, 'nullable');
        $product = Product::findOrFail($id);
        $this->saveProduct($request, $product);
        $notify[] = ['success', 'Product updated successfully'];
        return back()->withNotify($notify);
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
        if ($request->schedule == "") {
            $product->schedule = "1000weeks";
        } else {
            $product->schedule = "1000weeks";
        }
        $product->price = $request->price;
        $product->newprice = $request->newprice ? $request->newprice : 0;
        $product->started_at = $request->started_at ?? now();
        $sdate=date_create($request->started_at ?? now());
        if ($request->schedule == "") {
            date_add($sdate,date_interval_create_from_date_string("1000weeks"));
            $product->expired_at = date_format($sdate,"Y-m-d H:i:s");
        } else {
            date_add($sdate,date_interval_create_from_date_string("1000weeks"));
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
            'long_description'      => 'required',
            'specification'         => 'nullable|array',
            'imagereplaceinput'     => 'nullable|array',
            // 'started_at'            => 'required_if:started_at,exists|date|after:yesterday',
            'image'                 => [$imgValidation,'image', new FileTypeValidate(['jpeg', 'jpg', 'png', 'bmp'])]
        ]);
    }


    public function productBids($id)
    {
        $product = Product::with('winner')->findOrFail($id);
        $pageTitle = $product->name.' Bids';
        $emptyMessage = $product->name.' has no bid yet';
        $bids = Bid::where('product_id', $id)->with('user', 'product', 'winner')->withCount('winner')->orderBy('winner_count', 'DESC')->latest()->paginate(getPaginate());
        return view('admin.product.product_bids', compact('pageTitle', 'emptyMessage', 'bids'));
    }

    public function bidWinner(Request $request)
    {
        $request->validate([
            'bid_id' => 'required'
        ]);

        $bid = Bid::with('user', 'product')->findOrFail($request->bid_id);
        $product = $bid->product;
        $winner = Winner::where('product_id', $product->id)->exists();

        if($winner){
            $notify[] = ['error', 'Winner for this product is already selected'];
            return back()->withNotify($notify);
        }

        // if($product->expired_at > now()){
        //     $notify[] = ['error', 'This product is not expired till now'];
        //     return back()->withNotify($notify);
        // }

        $user = $bid->user;
        $general = GeneralSetting::first();
        $userss = User::findorFail($user->id);

        $winner = new Winner();
        $winner->user_id = $user->id;
        $winner->product_id = $product->id;
        $winner->bid_id = $bid->id;
        $winner->save();
        
        $userss->balance -= $bid->amount;
        $userss->save();
        
        $trx = getTrx();
    
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $bid->amount;
        $transaction->post_balance = $userss->balance;
        $transaction->trx_type = '-';
        if($product->admin_id) {
            $transaction->details = "Marketplace Order ".$product->name;
        } else {
            $transaction->details = "Marketplace Order ".$product->name;
        }
        $transaction->trx = $trx;
        $transaction->save();
        
        $trx1 = getTrx();
        
        $transaction1 = new Transaction();
        $transaction1->user_id = $user->id;
        $transaction1->amount = 50;
        $transaction1->post_balance = User::findOrFail($user->id)->bonuspoint + 50;
        $transaction1->charge = 0;
        $transaction1->trx_type = 'bonus_plus';
        $transaction1->details = 'Marketplace Order';
        $transaction1->trx =  $trx1;
        $transaction1->save();
        
        $users = User::findOrFail($user->id);
        $users->bonuspoint += 50;
        $users->save();

        notify($user, 'BID_WINNER', [
            'product' => $product->name,
            'product_price' => showAmount($product->price),
            'currency' => $general->cur_text,
            'amount' => showAmount($bid->amount),
        ]);

        $notify[] = ['success', 'Winner selected successfully'];
        return back()->withNotify($notify);
    }

    public function productWinner(){
        $pageTitle = 'Marketplace Sales';
        $emptyMessage = 'No winner found';
        $winners = Winner::with('product', 'user')->latest()->paginate(getPaginate());

        return view('admin.product.winners', compact('pageTitle', 'emptyMessage', 'winners'));
    }
    
    public function declinewinner($bid_id) {
        $winner = Winner::where('bid_id', $bid_id)->exists();
        if($winner) {
            $winners = Winner::where('bid_id', $bid_id)->get();
            $winnerdel = Winner::findOrFail($winners[0]->id);
            $winnerdel->delete();
            
            $bids = Bid::findOrFail($bid_id);
            
            $userNotification = new UserNotification();
            $userNotification->n_user_id = $bids->user_id;
            $userNotification->product_id = $bids->product_id;
            $userNotification->title = 'Unfortunately the seller not accepted your offer.';
            $userNotification->click_url = "";
            $userNotification->save();
            
            $bids->delete();
            
            $notify[] = ['success', 'Success!'];
            return back()->withNotify($notify);
        } else {
            $bids = Bid::findOrFail($bid_id);
            
            $userNotification = new UserNotification();
            $userNotification->n_user_id = $bids->user_id;
            $userNotification->product_id = $bids->product_id;
            $userNotification->title = 'Unfortunately the seller not accepted your offer.';
            $userNotification->click_url = "";
            $userNotification->save();
            
            $bids->delete();
            
            $notify[] = ['success', 'Success!'];
            return back()->withNotify($notify);
        }
    }

    public function deliveredProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 1;
        $winner->save();

        $notify[] = ['success', 'Product mark as delivered'];
        return back()->withNotify($notify);
    }
    
    public function paidProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 1;
        $winner->save();

        $notify[] = ['success', 'Product mark as paid'];
        return back()->withNotify($notify);
    }
    
    public function pickedProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 2;
        $winner->save();

        $notify[] = ['success', 'Product mark as picked'];
        return back()->withNotify($notify);
    }
    
    public function packedProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 3;
        $winner->save();

        $notify[] = ['success', 'Product mark as packed'];
        return back()->withNotify($notify);
    }
    
    public function transitedProduct(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Winner::with('product')->whereHas('product')->findOrFail($request->id);
        $winner->product_delivered = 4;
        $winner->save();

        $notify[] = ['success', 'Product mark as transited'];
        return back()->withNotify($notify);
    }
    
    public function generatedShipUrl(Request $request)
    {
        $request->validate([
            'uid' => 'required|integer',
            'paketid' => 'required',
            'carrier' => 'required'
        ]);
        $user = User::findOrFail($request->uid);
        $user->shipping_url = "https://www.paketda.de/track-carrierdirect.php?id=".$request->paketid."&carrier=".$request->carrier;
        $user->save();
        $notify[] = ['success', 'Generated Track successful!'];
        return back()->withNotify($notify);
    }
    
    public function winneritemdelete(Request $request) {
        $winner = Winner::findOrFail($request->winner_item_id);
        
        $paidresult = explode(',', $winner->paid_imageurl);
        $pickresult = explode(',', $winner->picked_imageurl);
        $packresult = explode(',', $winner->packed_imageurl);
        
        foreach($paidresult as $imgri) {
            if($imgri != "") {
                unlink(imagePath()['product']['path']."/".$imgri);
            }
        }
        
        foreach($pickresult as $imgrii) {
            if($imgri != "") {
                unlink(imagePath()['product']['path']."/".$imgrii);
            }
        }
        
        foreach($packresult as $imgriii) {
            if($imgri != "") {
                unlink(imagePath()['product']['path']."/".$imgriii);
            }
        }
        
        $winner->delete();
        $notify[] = ['success', 'Item Deleted Successfully'];
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
