<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Auctionbid;
use App\Models\Bid;
use App\Models\Category;
use App\Models\User;
use App\Models\GeneralSetting;
use App\Models\EmailTemplate;
use App\Models\Searchlist;
use App\Models\Product;
use App\Models\Auction;
use App\Models\Winner;
use App\Models\Transaction;
use App\Models\Auctionwinner;
use App\Models\UserNotification;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    protected $search;

    protected function filterAuctions($type){

        $auctions = Auction::query();
        if($type == "all") {
            $this->pageTitle = "Auctions";
        }
        else if($type == "live") {
            $this->pageTitle = "Auctions live";
        }
        else {
            $this->pageTitle    = ucfirst($type). ' Auctions';
        }
        
        $this->emptyMessage = 'No '.$type. ' auctions found';

        if($type != 'all'){
            $auctions = $auctions->$type();
        }
        
        if($type == "all") {
            $auctions = $auctions->live();
        }

        if(request()->search){
            $search  = request()->search;

            $auctions    = $auctions->where(function($qq) use ($search){
                $qq->where('name', 'like', '%'.$search.'%')->orWhere(function($auction) use($search){
                    $auction->whereHas('merchant', function ($merchant) use ($search) {
                        $merchant->where('username', 'like',"%$search%");
                    })->orWhereHas('admin', function ($admin) use ($search) {
                        $admin->where('username', 'like',"%$search%");
                    });
                });
            });

            $this->pageTitle    = "Search Result for '$search'";
            $this->search = $search;
        }
        
        return $auctions->with('merchant', 'admin')->orderBy('admin_id', 'DESC')->orderBy('id', 'DESC')->latest()->paginate(getPaginate());
    }

    public function index()
    {
        $segments       = request()->segments();
        $auctions       = $this->filterAuctions(end($segments));
        $pageTitle      = $this->pageTitle;
        $emptyMessage   = $this->emptyMessage;
        $search         = $this->search;

        return view('admin.auction.index', compact('pageTitle', 'emptyMessage', 'auctions', 'search'));
    }

    public function approve(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);
        $auction = Auction::findOrFail($request->id);
        $auction->status = 1;
        $auction->save();

        $notify[] = ['success', 'Auction Approved Successfully'];
        return back()->withNotify($notify);
    }

    public function create()
    {
        $pageTitle = 'Create Auction Product';
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->get();

        return view('admin.auction.create', compact('pageTitle', 'categories'));
    }
    
    public function startagain($id) {
        $auction = Auction::findOrFail($id);
        $auction->started_at = now();
        $sdate=date_create(now());
        date_add($sdate,date_interval_create_from_date_string($auction->schedule));
        $auction->expired_at = date_format($sdate,"Y-m-d H:i:s");
        $auction->save();
        $notify[] = ['success', 'Setting is successfully'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = 'Update Auction Product';
        $categories = Category::where('status', 1)->get();
        $auction = Auction::findOrFail($id);

        return view('admin.auction.edit', compact('pageTitle', 'categories', 'auction'));
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');
        $auction            = new Auction();
        $auction->admin_id  = auth()->guard('admin')->id();
        $auction->status    = 1;

        $this->saveAuction($request, $auction);
        
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
        
        $newmatchurl = "https://1400g.de/search-listproducts?search_key=".$request->name;
        
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
        
        $notify[] = ['success', 'Auction product added successfully'];
        return back()->withNotify($notify);
    }
    
    public function storeoneimg(Request $request)
    {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        return $oneimgurl;
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, 'nullable');
        $auction = Auction::findOrFail($id);
        $this->saveAuction($request, $auction);
        $notify[] = ['success', 'Auction updated successfully'];
        return back()->withNotify($notify);
    }

    public function saveAuction($request, $auction)
    {
        if ($request->hasFile('image')) {
            try {
                $auction->image = uploadImage($request->image, imagePath()['product']['path'], imagePath()['product']['size'], $auction->image, imagePath()['auction']['thumb']);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        $auction->name = $request->name;
        $auction->category_id = $request->category;
        $auction->schedule = $request->schedule;
        $auction->price = $request->price;
        $auction->started_at = $request->started_at ?? now();
        $sdate=date_create($request->started_at ?? now());
        date_add($sdate,date_interval_create_from_date_string($request->schedule));
        $auction->expired_at = date_format($sdate,"Y-m-d H:i:s");
        // $auction->expired_at = $request->expired_at;
        $auction->short_description = $request->short_description;
        $auction->long_description = $request->long_description;
        $auction->specification = $request->specification ?? null;
        $auction->imagereplaceinput = $request->imagereplaceinput ?? null;
        
        $auction->save();
    }

    protected function validation($request, $imgValidation){
        $request->validate([
            'name'                  => 'required',
            'category'              => 'required|exists:categories,id',
            'price'                 => 'required|numeric|gte:0',
            'schedule'              => 'required',
            'long_description'      => 'required',
            'specification'         => 'nullable|array',
            'imagereplaceinput'     => 'nullable|array',
            // 'started_at'            => 'required_if:schedule,1|date|after:yesterday|before:expired_at',
            'image'                 => [$imgValidation,'image', new FileTypeValidate(['jpeg', 'jpg', 'png', 'bmp'])]
        ]);
    }


    public function auctionBids($id)
    {
        $auction = Auction::with('auctionwinner')->findOrFail($id);
        $pageTitle = $auction->name.' Bids';
        $emptyMessage = $auction->name.' has no bid yet';
        $bids = Auctionbid::where('auction_id', $id)->with('user', 'auction', 'auctionwinner')->withCount('auctionwinner')->orderBy('auctionwinner_count', 'DESC')->latest()->paginate(getPaginate());
        return view('admin.auction.auction_bids', compact('pageTitle', 'emptyMessage', 'bids'));
    }
    
    public function auctionsWinner(){
        $pageTitle = 'Auction Sales';
        $emptyMessage = 'No winner found';
        $winners = Auctionwinner::with('auction', 'user', 'checkout')->latest()->paginate(getPaginate());
        return view('admin.auction.winners', compact('pageTitle', 'emptyMessage', 'winners'));
    }
    
    public function declinewinner($bid_id) {
        $winner = Auctionwinner::where('auctionbid_id', $bid_id)->exists();
        if($winner) {
            $winners = Auctionwinner::where('auctionbid_id', $bid_id)->get();
            $winnerdel = Auctionwinner::findOrFail($winners[0]->id);
            $winnerdel->delete();
            $notify[] = ['success', 'Winner declined successfully.'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Winner for this product is not exist.'];
            return back()->withNotify($notify);
        }
    }

    public function auctionbidWinner(Request $request)
    {
        $request->validate([
            'bid_id' => 'required'
        ]);

        $bid = Auctionbid::with('user', 'auction')->findOrFail($request->bid_id);
        $auction = $bid->auction;
        $winner = Auctionwinner::where('auction_id', $auction->id)->exists();

        if($winner){
            $notify[] = ['error', 'Winner for this product is already selected'];
            return back()->withNotify($notify);
        }

        if($auction->expired_at > now()){
            $notify[] = ['error', 'This product is not expired till now'];
            return back()->withNotify($notify);
        }

        $user = $bid->user;
        $general = GeneralSetting::first();
        $userss = User::findorFail($user->id);

        $winner = new Auctionwinner();
        $winner->user_id = $user->id;
        $winner->auction_id = $auction->id;
        $winner->auctionbid_id = $bid->id;
        $winner->save();
        
        $userss->balance -= $bid->amount;
        $userss->save();
        
        $trx = getTrx();
        
        $userNotification = new UserNotification();
        $userNotification->user_id = $user->id;
        $userNotification->n_user_id = $user->id;
        $userNotification->auction_id = $auction->id;
        $userNotification->title = "We need your instruction to continue.";
        $userNotification->click_url = urlPath('user.notification.bids', $auction->id);
        $userNotification->save();
    
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $bid->amount;
        $transaction->post_balance = $userss->balance;
        $transaction->trx_type = '-';
        if($auction->admin_id) {
            $transaction->details = "Winning Bid ".$auction->name;
        } else {
            $transaction->details = "Winning Bid ".$auction->name;
        }
        $transaction->trx = $trx;
        $transaction->save();
        
        $trx1 = getTrx();
        
        $transaction1 = new Transaction();
        $transaction1->user_id = $user->id;
        $transaction1->amount = 100;
        $transaction1->post_balance = User::findOrFail($user->id)->bonuspoint + 100;
        $transaction1->charge = 0;
        $transaction1->trx_type = 'bonus_plus';
        $transaction1->details = 'Winning Bid';
        $transaction1->trx =  $trx1;
        $transaction1->save();
        
        
        $users = User::findOrFail($user->id);
        $users->bonuspoint += 100;
        $users->save();

        notify($user, 'BID_WINNER', [
            'product' => $auction->name,
            'product_price' => showAmount($auction->price),
            'currency' => $general->cur_text,
            'amount' => showAmount($bid->amount),
        ]);

        $notify[] = ['success', 'Winner selected successfully'];
        return back()->withNotify($notify);
    }

    public function productWinner(){
        $pageTitle = 'All Winners';
        $emptyMessage = 'No winner found';
        $winners = Winner::with('product', 'user')->latest()->paginate(getPaginate());

        return view('admin.product.winners', compact('pageTitle', 'emptyMessage', 'winners'));
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
    
    public function deleteAuction(Request $request)
    {
        $auction = Auction::findOrFail($request->auction_id);
        
        unlink(imagePath()['product']['path']."/".$auction->image);
        unlink(imagePath()['product']['path']."/thumb_".$auction->image);
        
        foreach($auction->imagereplaceinput as $imgri) {
            unlink(imagePath()['product']['path']."/".$imgri['url']);
        }
        
        $auction->delete();
        
        $notify[] = ['success', 'Auction Deleted Successfully'];
        return back()->withNotify($notify);
    }
    
    public function paidAuction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
        $winner->product_delivered = 1;
        $winner->save();

        $notify[] = ['success', 'Product mark as paid'];
        return back()->withNotify($notify);
    }
    
    public function pickedAuction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
        $winner->product_delivered = 2;
        $winner->save();

        $notify[] = ['success', 'Product mark as picked'];
        return back()->withNotify($notify);
    }
    
    public function packedAuction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
        $winner->product_delivered = 3;
        $winner->save();

        $notify[] = ['success', 'Product mark as packed'];
        return back()->withNotify($notify);
    }
    
    public function transitedAuction(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
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
    
    
    public function storestatusimg(Request $request)
    {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        
        if($request->stype == "pending") {
            $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
            
            if($winner->pending_imageurl == null || $winner->pending_imageurl == "") {
                $userNotification = new UserNotification();
                $userNotification->user_id = $winner->user_id;
                $userNotification->n_user_id = $winner->user_id;
                $userNotification->auction_id = $winner->auction_id;
                $userNotification->title = 'Status changed to "Waiting for Payment"';
                $userNotification->click_url = "changingstatus";
                $userNotification->save();
            }
            
            $winner->pending_imageurl = $winner->pending_imageurl.$oneimgurl.",";
            $winner->save();
        }
        else if($request->stype == "paid") {
            $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
            $winner->paid_imageurl = $winner->paid_imageurl.$oneimgurl.",";
            $winner->save();
        }
        else if($request->stype == "picked")
        {
            $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
            $winner->picked_imageurl = $winner->picked_imageurl.$oneimgurl.",";
            $winner->save();
        }
        else if($request->stype == "packed")
        {
            $winner = Auctionwinner::with('auction')->whereHas('auction')->findOrFail($request->id);
            $winner->packed_imageurl = $winner->packed_imageurl.$oneimgurl.",";
            $winner->save();
        }
    }
    
    public function winneritemdelete(Request $request) {
        $winner = Auctionwinner::findOrFail($request->winner_item_id);
        $winner->delete();
        $notify[] = ['success', 'Item Deleted Successfully'];
        return back()->withNotify($notify);
    }
}
