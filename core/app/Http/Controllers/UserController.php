<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\Auctionbid;
use App\Models\Auction;
use App\Models\Winner;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Auctionquestion;
use App\Models\UserNotification;
use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Searchlist;
use App\Models\Transaction;
use App\Models\Paymentmethod;
use App\Models\Auctionwinner;
use App\Models\AdminNotification;
use App\Models\Checkout;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Buyer Dashboard';
        $widget['balance']              = Auth::user()->balance;
        $widget['total_deposit']        = Deposit::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $widget['total_bid']            = Auctionbid::where('user_id', auth()->id())->count();
        $widget['total_bid_amount']     = Auctionbid::where('user_id', auth()->id())->sum('amount');
        $widget['total_wining_product'] = Auctionwinner::where('user_id', auth()->id())->count();
        $widget['total_transactions']   = Transaction::where('user_id', auth()->id())->count();
        $widget['total_tickets']        = SupportTicket::where('user_id', auth()->id())->count();
        $widget['waiting_for_result']   = $widget['total_bid'] - Auctionwinner::with('auction.auctionbids')->whereHas('auction.auctionbids', function($bid){
            $bid->where('user_id', auth()->id());
        })->count();
        $transactions = DB::select('select * from transactions where user_id = '.auth()->id().' AND (trx_type = "-" OR trx_type = "+") ORDER BY id DESC LIMIT 5', [1]);
        $emptyMessage = 'No transaction found';
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'widget', 'transactions', 'emptyMessage'));
    }
    
    public function checkout() {
        $pageTitle = "Express Checkout";
        $user = Auth::user();
        $checkstatus = Checkout::where('user_id', $user->id)->exists();
        if($checkstatus) {
            $checkoutprofile = Checkout::where('user_id', $user->id)->take(1)->get();
            $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
            $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
            return view($this->activeTemplate.'user.checkout', compact('pageTitle', 'countries', 'paymentmethods', 'checkoutprofile', 'checkstatus'));
        } else {
            $checkoutprofile = [];
            $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
            $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
            return view($this->activeTemplate.'user.checkout', compact('pageTitle', 'countries', 'paymentmethods', 'checkoutprofile', 'checkstatus'));
        }
    }
    
    public function checkoutSave(Request $request) {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postalcode' => 'required',
            'country' => 'required',
            'email' => 'required',
            'paymentname' => 'required'
        ]);
        
        $checkout = new Checkout();
        $checkout->user_id = Auth::user()->id;
        $checkout->firstname = $request->firstname;
        $checkout->lastname = $request->lastname;
        $checkout->city = $request->city;
        $checkout->address = $request->address;
        $checkout->tel = $request->tel;
        $checkout->postalcode = $request->postalcode;
        $checkout->country = $request->country;
        $checkout->email = $request->email;
        $checkout->paymentname = $request->paymentname;
        $checkout->save();
        
        $notify[] = ['success', 'Saved!'];
        return back()->withNotify($notify);
    }
    
    public function checkoutUpdate(Request $request) {
        $this->validate($request, [
            'checkoutid' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postalcode' => 'required',
            'country' => 'required',
            'email' => 'required',
            'paymentname' => 'required'
        ]);
        
        $checkout = Checkout::findOrFail($request->checkoutid);
        $checkout->firstname = $request->firstname;
        $checkout->lastname = $request->lastname;
        $checkout->city = $request->city;
        $checkout->address = $request->address;
        $checkout->tel = $request->tel;
        $checkout->postalcode = $request->postalcode;
        $checkout->country = $request->country;
        $checkout->email = $request->email;
        $checkout->paymentname = $request->paymentname;
        $checkout->save();
        
        $notify[] = ['success', 'Updated!'];
        return back()->withNotify($notify);
    }
    
    public function clearanswer(Request $request) {
        $answerone = Answer::findOrFail($request->answer_id);
        $answerone->read_status = 1;
        $answerone->save();
        return back();
    }
    
    public function closeAccount() {
        $pageTitle = 'Delete User Account';
        $user = Auth::user();
        $widget['balance'] = $user->balance;
        $emptyMessage = "No Account";
        return view($this->activeTemplate . 'user.closeaccount', compact('pageTitle', 'emptyMessage', 'widget'));
    }
    
    public function closeAccountclose() {
        $user = Auth::user();
        $users = User::findOrFail($user->id);
        $users->status = 0;
        $users->save();
        $notify[] = ['success', 'Your account is now deactived and will be deleted within 7 days.'];
        return back()->withNotify($notify);
    }
    
    public function notificationAnswerOneDelete($id) {
        $answerone = Answer::findOrFail($id);
        $answerone->delete();
        $notify[] = ['success', 'Delete answer successfully.'];
        return back()->withNotify($notify);
    }
    
    public function notificationanswers() {
        $answerauctionNotifications = Answer::query()->leftJoin('auctionquestions', "answers.question_id", "=", "auctionquestions.id")->select("answers.*", "auctionquestions.question")->where('answers.auction_id', '!=', 0)->whereIn('answers.question_id', Auctionquestion::where('auctionquestions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get();
        $answerproductNotifications = Answer::query()->leftJoin('questions', "answers.question_id", "=", "questions.id")->select("answers.*", "questions.question")->where('answers.product_id', '!=', 0)->whereIn('answers.question_id', Question::where('questions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get();
        $pageTitle = "Question and Answer";
        return view($this->activeTemplate.'user.notificationAnswers', compact('pageTitle', 'answerauctionNotifications', 'answerproductNotifications'));
    }
    
    public function answerReadAll(){
        $answerauctionNotifications = Answer::query()->leftJoin('auctionquestions', "answers.question_id", "=", "auctionquestions.id")->select("answers.*", "auctionquestions.question")->where('answers.auction_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Auctionquestion::where('auctionquestions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get();
        $answerproductNotifications = Answer::query()->leftJoin('questions', "answers.question_id", "=", "questions.id")->select("answers.*", "questions.question")->where('answers.product_id', '!=', 0)->where('read_status', 0)->whereIn('answers.question_id', Question::where('questions.user_id', auth()->user()->id)->select(DB::raw("id as question_id"))->get())->with('auction', 'product')->orderBy('answers.created_at', 'DESC')->get();
        
        foreach($answerauctionNotifications as $notification) {
            $item = Answer::findOrFail($notification->id);
            $item->read_status = 1;
            $item->save();
        }
        
        foreach($answerproductNotifications as $notification) {
            $item = Answer::findOrFail($notification->id);
            $item->read_status = 1;
            $item->save();
        }
        
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }
    
    public function notifications(){
        $notifications = UserNotification::where('n_user_id', auth()->id())->orderBy('id','desc')->with('user', 'auction', 'product')->paginate(getPaginate());
        $pageTitle = 'Notifications';
        return view($this->activeTemplate.'user.notifications',compact('pageTitle','notifications'));
    }
    
    public function notificationDelete($id) {
        $notification = UserNotification::findOrFail($id);
        $notification->delete();
        $notify[] = ['success', 'Notification delete successfully.'];
        return back()->withNotify($notify);
    }
    
    public function notificationProductRead($id) {
        $notification = UserNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        if($notification->auction_id == 0) {
            return redirect()->route('product.details', [$notification->product_id, "notifications"]);
        } else {
            return redirect()->route('auction.details', [$notification->auction_id, "notifications"]);
        }
    }
    
    public function notificationChangingStatusRead($id) {
        $notification = UserNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        return redirect()->route('user.winningbid.history');
    }
    
    public function notificationProductChangingStatusRead($id) {
        $notification = UserNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        return redirect()->route('user.winning.history');
    }
    
    public function notificationRead($id){
        $notification = UserNotification::findOrFail($id);
        $notification->read_status = 1;
        $notification->save();
        if($notification->auction_id == 0) {
            return redirect()->route('product.details', [$notification->product_id, "notifications"]);
        } else {
            return redirect()->route('auction.details', [$notification->auction_id, "notifications"]);
        }
    }
    
    public function readAll(){
        UserNotification::where('read_status',0)->where('n_user_id', auth()->id())->update([
            'read_status'=>1
        ]);
        $notify[] = ['success','Notifications read successfully'];
        return back()->withNotify($notify);
    }
    
    public function notificationBids($id) {
        $auction = Auction::with('auctionwinner')->findOrFail($id);
        $pageTitle = $auction->name.' Bids';
        $emptyMessage = $auction->name.' has no bid yet';
        $bids = Auctionbid::where('auction_id', $id)->with('user', 'auction', 'auctionwinner')->withCount('auctionwinner')->orderBy('auctionwinner_count', 'DESC')->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.notifications_bids', compact('pageTitle', 'emptyMessage', 'bids'));
    }
    
    public function deleteleadingbidHistory(Request $request) {
        $onebid = Auctionbid::where('auction_id', $request->delbid_id);
        $onebid->delete();
        $notify[] = ['success', 'Bid Deleted successfully.'];
        return back()->withNotify($notify);
    }
    
    public function leadingbidHistory() {
        $pageTitle = 'Your bids';
        $emptyMessage = 'No bids found.';
        $skey = "120";
        $outtenbids = Auctionbid::where('user_id', '!=', auth()->id())->get();
        $leadingbids = Auctionbid::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))->whereNotIn('auction_id', Auctionwinner::where('user_id', auth()->id())->select('auction_id')->get())->select('*', DB::raw("MAX(amount) as maxamount"))->groupBy("auction_id")->with('user', 'auction')->get();
        return view($this->activeTemplate.'user.leading_bid', compact('pageTitle', 'emptyMessage', 'leadingbids', 'skey', 'outtenbids'));
    }
    
    public function searchleadingbidHistory($searchkey) {
        $pageTitle = "My bids";
        $emptyMessage = "No bidding found";
        $skey = $searchkey;
        $outtenbids = Auctionbid::where('user_id', '!=', auth()->id())->get();
        $leadingbids = Auctionbid::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$searchkey.' DAY)'))->whereNotIn('auction_id', Auctionwinner::query()->select('auction_id')->get())->select('*', DB::raw("MAX(amount) as maxamount"))->groupBy("auction_id")->with('user', 'auction')->get();
        return view($this->activeTemplate.'user.leading_bid', compact('pageTitle', 'emptyMessage', 'leadingbids', 'skey', 'outtenbids'));
    }
    
    public function winningbidHistory() {
        $pageTitle = 'Your Winning Bids';
        $emptyMessage = 'No winning bids found.';
        $user = Auth::user();
        $url = $user->shipping_url;
        $checkdata = Checkout::where('user_id', $user->id)->get();
        $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
        $skey = "120";
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $winningbids = Auctionwinner::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))->with('user', 'checkout','auction', 'auctionbid')->orderBy('id', 'DESC')->get();
        return view($this->activeTemplate.'user.winning_bid', compact('pageTitle', 'countries', 'emptyMessage', 'winningbids', 'skey', 'url', 'checkdata', 'paymentmethods'));
    }
    
    public function searchwinningbidHistory($searchkey) {
        $pageTitle = 'Your Winning Bids';
        $emptyMessage = 'No winning bids found.';
        $user = Auth::user();
        $url = $user->shipping_url;
        $skey = $searchkey;
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $checkdata = Checkout::where('user_id', $user->id)->get();
        $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
        $winningbids = Auctionwinner::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$searchkey.' DAY)'))->with('user','auction', 'auctionbid')->get();
        return view($this->activeTemplate.'user.winning_bid', compact('pageTitle', 'countries', 'emptyMessage', 'winningbids', 'skey', 'url', 'checkdata', 'paymentmethods'));
    }
    
    public function addshippingnew(Request $request) {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'city' => 'required',
            'postalcode' => 'required',
            'country' => 'required',
        ]);
        
        $checkout = new Checkout();
        $checkout->user_id = Auth::user()->id;
        $checkout->firstname = $request->firstname;
        $checkout->lastname = $request->lastname;
        $checkout->city = $request->city;
        $checkout->address = $request->address;
        $checkout->tel = $request->tel;
        $checkout->postalcode = $request->postalcode;
        $checkout->country = $request->country;
        $checkout->email = $request->email;
        $checkout->save();
        
        return $checkout;
    }
    
    public function updatecombineshipping(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->combine_shipping_flag = 1;
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "auctionmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'We will wait up to 14 days or until you unmark and choosed another option.'];
        return back()->withNotify($notify);
    }
    
    public function unmarkcombineshipping(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->combine_shipping_flag = 0;
        $auctionwinner->save();
        
        $notify[] = ['warning', 'Unmarked, please choose again an option.'];
        return back()->withNotify($notify);
    }
    
    public function updatepickupdate(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
            'started_at' => 'required|date',
        ]);
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->pickup_date = date_format(date_create($request->started_at),"Y-m-d H:i:s");
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "auctionmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'Pick-up date forwarded successfully.'];
        return back()->withNotify($notify);
    }
    
    public function unmarkpickupdate(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->pickup_date = NULL;
        $auctionwinner->save();
        
        $notify[] = ['warning', "Unmarked, please choose again an option."];
        return back()->withNotify($notify);
    }
    
    public function unupdatecheckout(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->checkout_id = 0;
        $auctionwinner->product_delivered = 0;
        $auctionwinner->save();
        
        $notify[] = ['warning', 'Unmarked, please choose again an option.'];
        return back()->withNotify($notify);
    }
    
    public function updatecheckout(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
            'checkout_id' => 'required',
            'paymentmethod_id' => 'required',
        ]);
        
        $checkout = Checkout::findOrFail($request->checkout_id);
        $checkout->paymentname = $request->paymentmethod_id;
        $checkout->save();
        if(strpos(strtolower($request->paymentmethod_id), "wise") > -1) {
            $trx = getTrx();
            
            $transaction = new Transaction();
            $transaction->user_id = auth()->id();
            $transaction->amount = 50;
            $transaction->post_balance = User::findOrFail(auth()->id())->bonuspoint + 50;
            $transaction->charge = 0;
            $transaction->trx_type = 'bonus_plus';
            $transaction->details = 'WISE payment';
            $transaction->trx =  $trx;
            $transaction->save();
            
            $userss = User::findOrFail(auth()->id());
            $userss->bonuspoint += 50;
            $userss->save();
        }
        
        $auctionwinner = Auctionwinner::findOrFail($request->auctionwinner_id);
        $auctionwinner->checkout_id = $request->checkout_id;
        $auctionwinner->product_delivered = 0;
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "auctionmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'We send you the invoice as soon as possible.'];
        return back()->withNotify($notify);
    }
    
    public function winningHistory(){
        $pageTitle = 'My Buy It Now Orders';
        $emptyMessage = 'No buy it now orders found.';
        $user = Auth::user();
        $url = $user->shipping_url;
        $skey = "120";
        $checkdata = Checkout::where('user_id', $user->id)->get();
        $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $winningHistories = Winner::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL 120 DAY)'))->with('user','product', 'bid', 'checkout')->orderBy('id', "DESC")->get();
        return view($this->activeTemplate.'user.winning_history', compact('pageTitle', 'countries', 'emptyMessage', 'winningHistories', 'url', 'skey', 'checkdata', 'paymentmethods'));
    }
    
    public function searchwinningHistory($searchkey) {
        $pageTitle = 'My Buy It Now Orders';
        $emptyMessage = 'No buy it now orders found.';
        $user = Auth::user();
        $url = $user->shipping_url;
        $skey = $searchkey;
        $checkdata = Checkout::where('user_id', $user->id)->get();
        $paymentmethods = Paymentmethod::where('status', 1)->orderBy('id', 'DESC')->get();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $winningHistories = Winner::where('user_id', auth()->id())->where('created_at', '>=', DB::raw('DATE_SUB(NOW(), INTERVAL '.$searchkey.' DAY)'))->with('user','product', 'bid')->orderBy('id', "DESC")->get();
        return view($this->activeTemplate.'user.winning_history', compact('pageTitle', 'countries', 'emptyMessage', 'winningHistories', 'url', 'skey', 'checkdata', 'paymentmethods'));
    }
    
    public function productupdatecombineshipping(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->combine_shipping_flag = 1;
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "productmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'We will wait up to 14 days or until you unmark and choosed another option.'];
        return back()->withNotify($notify);
    }
    
    public function productunmarkcombineshipping(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->combine_shipping_flag = 0;
        $auctionwinner->save();
        
        $notify[] = ['warning', 'Unmarked, please choose again an option.'];
        return back()->withNotify($notify);
    }
    
    public function productupdatepickupdate(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
            'started_at' => 'required|date',
        ]);
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->pickup_date = date_format(date_create($request->started_at),"Y-m-d H:i:s");
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "productmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'Pick-up date forwarded successfully.'];
        return back()->withNotify($notify);
    }
    
    public function productunmarkpickupdate(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->pickup_date = NULL;
        $auctionwinner->save();
        
        $notify[] = ['warning', "Unmarked, please choose again an option."];
        return back()->withNotify($notify);
    }
    
    public function productunupdatecheckout(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
        ]);
        
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->checkout_id = 0;
        $auctionwinner->product_delivered = 0;
        $auctionwinner->save();
        
        $notify[] = ['warning', 'Unmarked, please choose again an option.'];
        return back()->withNotify($notify);
    }
    
    public function productupdatecheckout(Request $request) {
        $this->validate($request, [
            'auctionwinner_id' => 'required',
            'checkout_id' => 'required',
            'paymentmethod_id' => 'required',
        ]);
        
        $checkout = Checkout::findOrFail($request->checkout_id);
        $checkout->paymentname = $request->paymentmethod_id;
        $checkout->save();
        
        if(strpos(strtolower($request->paymentmethod_id), "wise") > -1) {
            $trx = getTrx();
            
            $transaction = new Transaction();
            $transaction->user_id = auth()->id();
            $transaction->amount = 50;
            $transaction->post_balance = User::findOrFail(auth()->id())->bonuspoint + 50;
            $transaction->charge = 0;
            $transaction->trx_type = 'bonus_plus';
            $transaction->details = 'WISE payment';
            $transaction->trx =  $trx;
            $transaction->save();
            
            $userss = User::findOrFail(auth()->id());
            $userss->bonuspoint += 50;
            $userss->save();
        }
        
        $auctionwinner = Winner::findOrFail($request->auctionwinner_id);
        $auctionwinner->checkout_id = $request->checkout_id;
        $auctionwinner->product_delivered = 0;
        $auctionwinner->save();
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user()->id;
        $adminNotification->title = 'Status changed';
        $adminNotification->click_url = "productmarkchanged";
        $adminNotification->save();
        
        $notify[] = ['success', 'We send you the invoice as soon as possible.'];
        return back()->withNotify($notify);
    }
    
    public function winningbiddelete(Request $request) {
        $this->validate($request, [
            'delbid_id' => 'required',
        ]);
        $winningbids = Auctionwinner::where('id', $request->delbid_id);
        $winningbids->delete();
        $notify[] = ['success', 'Item deleted successfully.'];
        return back()->withNotify($notify);
    }
    
    public function outbiddenHistory() {
        $pageTitle = 'Outbidden';
        $emptyMessage = 'No bidding found';
        $outbiddenbids = Auctionbid::where('user_id', auth()->id())->with('user', 'auction')->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.outbidden', compact('pageTitle', 'emptyMessage', 'outbiddenbids'));
    }

    public function biddingHistory(){
        $pageTitle = 'My Bidding History';
        $emptyMessage = 'No bidding history found';
        $biddingHistories = Auctionbid::where('user_id', auth()->id())->with('user', 'auction')->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'user.bidding_history', compact('pageTitle', 'emptyMessage', 'biddingHistories'));
    }
    
    public function winningHistorydelete(Request $request) {
        $this->validate($request, [
            'delbid_id' => 'required',
        ]);
        $winningbids = Winner::where('id', $request->delbid_id);
        $winningbids->delete();
        $notify[] = ['success', 'Item deleted successfully.'];
        return back()->withNotify($notify);
    }
    
    public function bonus()
    {
        $pageTitle = "Bonus Scheme";
        $user = Auth::user();
        $transactions = DB::select('SELECT * FROM transactions WHERE user_id = '.auth()->id().' AND (trx_type = "bonus_minus" OR trx_type = "bonus_plus") ORDER BY id DESC', [1]);
        $emptyMessage = 'No transaction found';
        return view($this->activeTemplate. 'user.bonus_scheme', compact('pageTitle','user', 'transactions', 'emptyMessage'));
    }
    
    public function transactions() {
        $pageTitle = 'All Transactions';
        $emptyMessage = 'No transaction history found';
        $skey = "30";
        $transactions = DB::select('SELECT * FROM transactions WHERE user_id = '.auth()->id().' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) AND (trx_type = "-" OR trx_type = "+") ORDER BY id DESC', [1]);
        return view($this->activeTemplate.'user.transactions', compact('pageTitle', 'emptyMessage', 'transactions', 'skey'));
    }
    
    public function searchtransactions($searchkey) {
        $pageTitle = 'All Transaction';
        $emptyMessage = 'No transaction history found';
        $skey = $searchkey;
        $transactions = DB::select('SELECT * FROM transactions WHERE user_id = '.auth()->id().' AND created_at >= DATE_SUB(NOW(), INTERVAL '.$searchkey.' DAY) AND (trx_type = "-" OR trx_type = "+") ORDER BY id DESC', [1]);
        return view($this->activeTemplate.'user.transactions', compact('pageTitle', 'emptyMessage', 'transactions', 'skey'));
    }
    
    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'email' => 'required|string',
            'mobile' => 'required',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'country' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['email'] = $request->email;
        $in['mobile'] = $request->mobile;
        
        if($user->email != $request->email) {
            $in['ev'] = 0;
            $in['sv'] = 0;
        }
        

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'User changed profile.';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();
        
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $pageTitle = 'Deposit History';
        $emptyMessage = 'No deposit history found.';
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = '2FA Authentication';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }
    
    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);
        
        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }
    
    
    public function monitor()
    {
        $pageTitle = "Purchase Monitoring";
        $user = Auth::user();
        $winners = Winner::where('user_id', $user->id)->with('product')->get();
        $auctionwinners = Auctionwinner::where('user_id', $user->id)->with('auction')->get();
        $url = $user->shipping_url;
        $emptyMessage = "No Image Data";
        $auctionemptyMessage = "No Auction Product Image Data";
        return view($this->activeTemplate. 'user.item_monitor', compact('pageTitle','url', 'winners', 'emptyMessage', 'auctionemptyMessage', 'auctionwinners'));
    }
    
    public function searchlistindex() {
        $pageTitle = "Search List";
        $searchlists = Searchlist::where('user_id', auth()->user()->id)->get();
        $emptyMessage = "No search keyword found.";
        return view($this->activeTemplate.'user.searchlist', compact('pageTitle', 'searchlists', 'emptyMessage'));
    }
    
    public function searchlistdelete($id) {
        $searchlists = Searchlist::where('id', $id);
        $searchlists->delete();
        $notify[] = ['success', 'Keyword deleted successfully.'];
        return back()->withNotify($notify);
    }
    
    public function searchitemsave(Request $request) {
        $this->validate($request, [
            'search_keys' => 'required',
        ]);
        
        $user_id = auth()->user()->id;
        
        $searchlists = new Searchlist();
        $searchlists->search_name = $request->search_keys;
        $searchlists->user_id = $user_id;
        $searchlists->save();
        
        $notify[] = ['success', 'Saved into your search list.'];
        
        return back()->withNotify($notify);
    }

}
