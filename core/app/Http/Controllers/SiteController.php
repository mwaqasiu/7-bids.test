<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Languageiplist;
use App\Models\Merchant;
use App\Models\Page;
use App\Models\Product;
use App\Models\Auction;
use App\Models\GeneralSetting;
use App\Models\Slider;
use App\Models\Frontslider;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Location;


class SiteController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    
    public function updatecolor(Request $request)
    {
        $general = GeneralSetting::first();
        $general->base_color = $request->base_color;
        $general->save();
    }

    public function index()
    {
        $pageTitle = 'Home';
        $sliders = Frontslider::where('status', 1)->get();
        $sections = Page::where('tempname',$this->activeTemplate)->where('slug','home')->first();
        return view($this->activeTemplate . 'home', compact('pageTitle','sections', 'sliders'));
    }
    
    function shortCodeReplacer($shortCode, $replace_with, $template_string)
    {
        return str_replace($shortCode, $replace_with, $template_string);
    }

    public function pages($slug)
    {
        $page = Page::where('tempname',$this->activeTemplate)->where('slug',$slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle','sections'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        $page = Page::where('tempname',$this->activeTemplate)->where('slug','contact')->first();
        $sections = $page->secs;
        return view($this->activeTemplate . 'contact',compact('pageTitle', 'sections'));
    }
    
    public function faq() {
        $pageTitle = "";
        return view($this->activeTemplate . 'faq', compact('pageTitle'));
    }

    public function contactSubmit(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|max:191',
            'email' => 'required|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
        ]);

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = 2;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = 0;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.user.ticket.view',$ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        
        $requestip = request()->ip();
        $langcount = Languageiplist::where('ipaddress', $requestip)->count();
        $languagelists = Languageiplist::where('ipaddress', $requestip)->get();
        if($langcount > 0) {
            session()->put('lang', $languagelists[0]->lang);
        } else {
            session()->put('lang', $lang);
        }
        return redirect()->back();
    }
    
    public function germanchangeLanguage(Request $request) {
        $this->validate($request, [
            'germanlang' => 'required',
            'ipaddress' => 'required',
        ]);
        
        $langlist = new Languageiplist();
        $langlist->ipaddress = $request->ipaddress;
        $langlist->lang = $request->germanlang;
        $langlist->save();
        
        session()->put('lang', $request->germanlang);
        
        return redirect()->back();
    }

    public function blogs()
    {
        $pageTitle      = 'Blog Posts';
        $emptyMessage   = 'No blog post found';
        $blogs          = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate());
        $page           = Page::where('tempname',$this->activeTemplate)->where('slug','blog')->first();
        $sections       = $page->secs;

        return view($this->activeTemplate.'blogs', compact('pageTitle', 'emptyMessage', 'blogs', 'sections'));
    }

    public function blogDetails($id,$slug)
    {
        $pageTitle = 'Blog Details';
        $blog = Frontend::where('id',$id)->where('data_keys','blog.element')->firstOrFail();
        $recentBlogs = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $blog->id)->latest()->limit(10)->get();

        return view($this->activeTemplate.'blog_details',compact('blog','pageTitle', 'recentBlogs'));
    }

    public function cookieAccept()
    {
        session()->put('cookie_accepted',true);
        return back();
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        if($imgWidth == "1600" || $imgHeight == "1200")
        {
            $text = "Max 3 MB (JPEG, PNG, BMP)";
        } else if($imgWidth == "2400" || $imgHeight == "1800") {
            $text = "";
        } else {
            $text = $imgWidth . '×' . $imgHeight;
        }
        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize = round(($imgWidth - 50) / 8);
        
        if($imgWidth == "1600" || $imgHeight == "1200")
        {
            $fontSize = 70;
        } else if($imgWidth == "2400" || $imgHeight == "1800") {
            $fontSize = 38;
        } else {
            if ($fontSize <= 9) {
                $fontSize = 9;
            }
            if($imgHeight < 100 && $fontSize > 30){
                $fontSize = 30;
            }
        }
        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function policy($id)
    {
        $page = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $page->data_values->title;
        $description = $page->data_values->details;
        $pdf_url = $page->data_values->pdf;
        return view($this->activeTemplate . 'policy', compact('pageTitle', 'description', 'pdf_url'));
    }

    public function merchants()
    {
        $pageTitle = 'Merchant List';
        $emptyMessage = 'No merchant found';
        $merchants = Merchant::where('status', 1)->paginate(getPaginate());

        return view($this->activeTemplate.'merchants', compact('pageTitle', 'emptyMessage', 'merchants'));
    }

    public function adminProfile($id, $name)
    {
        $pageTitle = 'Merchant Profile';
        $merchant = Admin::findOrFail($id);
        $products = Product::live()->where('admin_id', $id)->paginate(getPaginate());
        $admin = true;

        return view($this->activeTemplate.'merchant_profile', compact('pageTitle', 'merchant', 'products', 'admin'));
    }

    public function merchantProfile($id, $name)
    {
        $pageTitle = 'Merchant Profile';
        $merchant = Merchant::findOrFail($id);
        $products = Product::live()->where('merchant_id', $id)->paginate(getPaginate());
        $admin = false;

        return view($this->activeTemplate.'merchant_profile', compact('pageTitle', 'merchant', 'products', 'admin'));
    }

    public function aboutUs()
    {
        $pageTitle = 'About Us';
        $page = Page::where('tempname',$this->activeTemplate)->where('slug','about-us')->first();
        $sections = $page->secs;
        return view($this->activeTemplate.'about_us', compact('pageTitle', 'sections'));
    }

    function adRedirect($hash){
        $id = decrypt($hash);
        $ad = Advertisement::findOrFail($id);
        $ad->click += 1;
        $ad->save();
        if($ad->type == 'image'){
            return redirect($ad->redirect_url);
        }else{
            return back();
        }
    }

    public function categories(){
        $pageTitle = 'All Categories';
        $emptyMessage = 'No category found';
        $categories = Category::where('status', 1)->paginate(getPaginate());

        return view($this->activeTemplate.'product.categories', compact('pageTitle', 'emptyMessage', 'categories'));

    }

    public function liveProduct(){
        $pageTitle = 'Live Products';
        $emptyMessage = 'No live product found';
        $products = Product::live()->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'product.live_products', compact('pageTitle', 'emptyMessage', 'products'));
    }
    
    public function liveAuction() {
        $pageTitle = 'Live Auctions';
        $emptyMessage = 'No live auction found';
        $auctions = Auction::live()->latest()->paginate(getPaginate());
        
        return view($this->activeTemplate.'auction.live_auctions', compact('pageTitle', 'emptyMessage', 'auctions'));
    }

    public function upcomingProduct(){
        $pageTitle = 'Upcoming Products';
        $emptyMessage = 'No upcoming product found';
        $products = Product::upcoming()->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'product.upcoming_products', compact('pageTitle', 'emptyMessage', 'products'));
    }
    
    public function upcomingAuction(){
        $pageTitle = 'Upcoming Auctions';
        $emptyMessage = 'No upcoming auction product found';
        $auctions = Auction::upcoming()->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'auction.upcoming_auctions', compact('pageTitle', 'emptyMessage', 'auctions'));
    }
    
    public function sellwithus() {
        $pageTitle = "SELL WITH US";
        $sliders = Slider::where('status', 1)->get();
        return view($this->activeTemplate.'sellwithus', compact('pageTitle', 'sliders'));
    }
    
    public function privatesales() {
        $pageTitle = "Private Sales";
        return view($this->activeTemplate.'privatesales', compact('pageTitle'));
    }
    
    public function storeoneimg(Request $request) {
        $oneimgurl = uploadOneImage($request->imagefile, imagePath()['product']['path'], imagePath()['product']['size']);
        return $oneimgurl;
    }
    
    public function contactsend(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'subject' => 'required',
            'message' => 'required',
            'ipaddress' => 'required',
        ]);
        $name = $request->name;
        $email = $request->email;
        $subject = $request->subject;
        $msg = $request->message;
        $ipaddress = $request->ipaddress;
        
        $general = GeneralSetting::first();
        
        $emailTemplate = EmailTemplate::where('act', "GET_IN_TOUCH")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $message = shortCodeReplacer("{{name}}", $name, $emailTemplate->email_body);
        $message = shortCodeReplacer("{{ipaddress}}", $ipaddress, $message);
        $message = shortCodeReplacer("{{email}}", $email, $message);
        $message = shortCodeReplacer("{{subject}}", $subject, $message);
        $message = shortCodeReplacer("{{message}}", $msg, $message);
        
        // admin
        $adminmail = $general->email_from;
        $receive_mail = explode('@', $email)[0];

        try {
            sendSellusEmail($adminmail, $subject, $message, $receive_mail);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Invalid credential'];
            return back()->withNotify($notify);
        }
        
        $notify[] = ['success', 'Thank you for contacting 7-BIDS, you will hear from us soon.'];
        return back()->withNotify($notify);
    }
    
    public function privatesalesSend(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'artist' => 'required',
            'addinfo' => 'required',
            'measure' => 'required',
            'sex' => 'required',
            'username' => 'required',
            'sellimagereplaceinput1id' => 'nullable|array',
        ]);
        
        $artist = $request->artist;
        $measure = $request->measure;
        $addinfo = $request->addinfo;
        if($request->sex == 0) {
            $sex = "Madam";
        } else {
            $sex = "Mister";
        }
        $username = $request->username;
        $email = $request->email;
        
        $general = GeneralSetting::first();
        
        $emailTemplate = EmailTemplate::where('act', "PRIVATE_SALES")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $message = shortCodeReplacer("{{username}}", $username, $emailTemplate->email_body);
        $message = shortCodeReplacer("{{email}}", $email, $message);
        $message = shortCodeReplacer("{{artist}}", $artist, $message);
        $message = shortCodeReplacer("{{measure}}", $measure, $message);
        $message = shortCodeReplacer("{{addinfo}}", $addinfo, $message);
        $message = shortCodeReplacer("{{usernames}}", $username, $message);
        
        // admin
        $adminmail = $general->email_from;
        $receive_mail = explode('@', $email)[0];
        
        $subject = 'private sales';

        $totalimg= "";
        
        if(!empty($request->sellimagereplaceinput1id)) {
            foreach ($request->sellimagereplaceinput1id as $key => $value) {
                foreach($value as $item) {
                    $totalimg = $totalimg.'<a href="https://7-bids.com/assets/images/product/'.$item.'" download rel="nofollow" target="_blank" title="Download image" style="margin: 2px;"><img src="https://7-bids.com/assets/images/product/'.$item.'" style="width: 40px; height: 26px;" /></a>';
                }
            }
        }
        
        $message = shortCodeReplacer("{{attachment}}", $totalimg, $message);
        
        try {
            sendSellusEmail($adminmail, $subject, $message, $receive_mail);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Invalid credential'];
            return back()->withNotify($notify);
        }
        
        $notify[] = ['success', 'Thank you for contacting 7-BIDS, you will hear from us soon.'];
        return back()->withNotify($notify);
    }
    
    public function sellwithusSend(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'artist' => 'required',
            'addinfo' => 'required',
            'measure' => 'required',
            'damagestatus' => 'required',
            'ipaddress' => 'required',
            //'sex' => 'required',
            'username' => 'required',
            'sellimagereplaceinput1id' => 'nullable|array',
            'sellimagereplaceinput2id' => 'nullable|array',
            'sellimagereplaceinput3id' => 'nullable|array',
            'sellimagereplaceinput4id' => 'nullable|array',
        ]);
        
        $artist = $request->artist;
        $measure = $request->measure;
        $addinfo = $request->addinfo;
        $ipaddress = $request->ipaddress;
        if($request->damagestatus == 1) {
            $damagestatus = "Yes";
        } else if($request->damagestatus == 2) {
            $damagestatus = "No";
        } else {
            $damagestatus = "I don't know";
        }
        $sex = '';
        /*if($request->sex == 0) {
            $sex = "Madam";
        } else {
            $sex = "Mister";
        }*/
        $username = $request->username;
        $email = $request->email;
        
        $general = GeneralSetting::first();
        
        $emailTemplate = EmailTemplate::where('act', "SELL_WITH_US")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $message = shortCodeReplacer("{{username}}", $username, $emailTemplate->email_body);
        $message = shortCodeReplacer("{{email}}", $email, $message);
        $message = shortCodeReplacer("{{artist}}", $artist, $message);
        $message = shortCodeReplacer("{{ipaddress}}", $ipaddress, $message);
        $message = shortCodeReplacer("{{measure}}", $measure, $message);
        $message = shortCodeReplacer("{{addinfo}}", $addinfo, $message);
        $message = shortCodeReplacer("{{damagestatus}}", $damagestatus, $message);
        $message = shortCodeReplacer("{{usernames}}", $username, $message);
        
        // admin
        $adminmail = $general->email_from;
        $receive_mail = explode('@', $email)[0];
        
        $subject = 'sell with us';

        $totalimg= "";
        
        if(!empty($request->sellimagereplaceinput1id)) {
            foreach ($request->sellimagereplaceinput1id as $key => $value) {
                foreach($value as $item) {
                    $totalimg = $totalimg.'<a href="https://7-bids.com/assets/images/product/'.$item.'" download rel="nofollow" target="_blank" title="Download image" style="margin: 2px;"><img src="https://7-bids.com/assets/images/product/'.$item.'" style="width: 40px; height: 26px;" /></a>';
                }
            }
        }
        
        $message = shortCodeReplacer("{{attachment}}", $totalimg, $message);
        
        try {
            sendSellusEmail($adminmail, $subject, $message, $receive_mail);
        } catch (\Exception $exp) {
            $notify[] = ['error', 'Invalid credential'];
            return back()->withNotify($notify);
        }
        
        $notify[] = ['success', 'Thank you for contacting 7-BIDS, you will hear from us soon.'];
        return back()->withNotify($notify);
    }
    
    public function charity() {
        $pageTitle = "Charity Project";
        $charitydata = Frontend::where('data_keys','charity.data')->firstOrFail();
        return view($this->activeTemplate.'charityproject', compact('pageTitle', 'charitydata'));
    }

}
