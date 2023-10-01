<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\AdminNotification;
use App\Models\AdminanswerNotification;
use App\Models\SellerNotification;
use App\Models\Bid;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Merchant;
use App\Models\Question;
use App\Models\EmailTemplate;
use App\Models\Answer;
use App\Models\Product;
use App\Models\Review;
use App\Models\Winner;
use App\Models\Wishlist;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function products()
    {
        
        $pageTitle      = request()->search_key?'Search Products':'BUY IT NOW';
        
        return view($this->activeTemplate.'product.list', compact('pageTitle'));
    }

    public function filter(Request $request)
    {
        $pageTitle      = 'Search Products';
        $emptyMessage   = 'No product found';
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
        
        return response()->json([
            'html' => view($this->activeTemplate.'product.filtered', compact('pageTitle', 'emptyMessage', 'allProducts', 'priceProducts', 'categories', 'products', 'wishlists', 'winnertext'))->render(),
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }

    public function productDetails($id)
    {
        $pageTitle = '';

        $user = auth()->user();

        $product = Product::with('reviews', 'merchant', 'reviews.user', 'admin')->where('status', '!=', 0)->findOrFail($id);
        $productcount = Product::with('admin')->where('status', '!=', 0)->where('started_at', '<', Carbon::now())->where('expired_at', '>', Carbon::now())->live()->doesnthave('winner');
       
       
        $relatedProducts = Product::live()->where('category_id', $product->category_id)->where('id', '!=', $id)->limit(10)->get();

        $imageData      = imagePath()['product'];

        $seoContents    = getSeoContents($product, $imageData, 'image');

        $winnerflag = Winner::query()->where('product_id', $id)->count();
        $winnerdatas = Winner::where('product_id', $id)->with('bid')->get();

        $productquestions_count = Question::where('product_id', $product->id)->count();
        $productquestions = Question::where('product_id', $product->id)->orderBy('id', 'desc')->get();

        $productanswers = Answer::where('product_id', $product->id)->get();

        $wishlist = Wishlist::where('product_id', $product->id)->where('ip_address', getenv('REMOTE_ADDR'))->get();

        return view($this->activeTemplate.'product.details', compact('pageTitle', 'productanswers', 'productquestions', 'productquestions_count', 'product', 'productcount','relatedProducts', 'seoContents', 'winnerdatas', 'winnerflag', 'wishlist'));
    }

    public function productQuestionSave(Request $request) {
        $request->validate([
            'username' => 'required',
            'product_id' => 'required|numeric',
            'question' => 'required',
        ]);

        $user = auth()->user();
        $productquestion = new Question();
        $productquestion->user_id = $user->id;
        $productquestion->product_id = $request->product_id;
        $productquestion->username = $request->username;
        $productquestion->question = $request->question;
        $productquestion->save();

        $product = Product::findOrFail($request->product_id);

        $adminanswernotification = new AdminanswerNotification();
        $adminanswernotification->admin_id = $product->admin_id;
        $adminanswernotification->user_id = $user->id;
        $adminanswernotification->title = $request->username." asked a question";
        $adminanswernotification->product_id = $request->product_id;
        $adminanswernotification->question_id = $productquestion->id;
        $adminanswernotification->question = $request->question;
        $adminanswernotification->save();

        $sellernotification = new SellerNotification();
        $sellernotification->n_seller_id = $product->merchant_id;
        $sellernotification->user_id = $user->id;
        $sellernotification->title = $request->username." asked a question";
        $sellernotification->product_id = $request->product_id;
        $sellernotification->question_id = $productquestion->id;
        $sellernotification->question = $request->question;
        $sellernotification->save();

        $notify[] = ['success', 'Thank you for contacting 7-BIDS, you will hear from us soon.'];
        return back()->withNotify($notify);
    }


    public function loadMore(Request $request)
    {
        $reviews = Review::where('product_id', $request->pid)->with('user')->latest()->paginate(5);

        return view($this->activeTemplate . 'partials.product_review', compact('reviews'));
    }

    public function bid(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
            'product_id' => 'required|integer|gt:0'
        ]);

        $product = Product::live()->with('merchant', 'admin')->findOrFail($request->product_id);

        $user = auth()->user();

        // if($product->price > $request->amount){
        //     $notify[] = ['error', 'Bid amount must be greater than product price'];
        //     return back()->withNotify($notify);
        // }

        // if($request->amount > $user->balance){
        //     $notify[] = ['error', 'Insufficient Balance'];
        //     return back()->withNotify($notify);
        // }

        // $bid = Bid::where('product_id', $request->product_id)->where('user_id', $user->id)->exists();

        // if($bid){
        //     $notify[] = ['error', 'You already bidden on this product'];
        //     return back()->withNotify($notify);
        // }

        $bid = new Bid();
        $bid->product_id = $product->id;
        $bid->user_id = $user->id;
        $bid->amount = $request->amount;
        $bid->save();

        $product->total_bid += 1;
        $product->save();

        $general = GeneralSetting::first();
        $admins = Admin::first();
        
        $emailTemplate = EmailTemplate::where('act', "BID_WINNER")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $emailTemplateAdmin = EmailTemplate::where('act', "BID_WINNER_ADMIN")->where('email_status', 1)->first();
        if ($general->en != 1 || !$emailTemplate) {
            return;
        }
        
        $message = shortCodeReplacer("{{username}}", $user->username, $emailTemplate->email_body);
        $message = shortCodeReplacer("{{product}}", $product->name, $message);
        $message = shortCodeReplacer("{{product_price}}", number_format($product->price, 0, '.', ''), $message);
        $message = shortCodeReplacer("{{amount}}", $request->amount, $message);
        $message = shortCodeReplacer("{{currency}}", $general->cur_text, $message);
        
        $messageAdmin = shortCodeReplacer("{{fullname}}", $user->firstname." ".$user->lastname, $emailTemplateAdmin->email_body);
        $messageAdmin = shortCodeReplacer("{{country}}", $user->address->country, $messageAdmin);
        $messageAdmin = shortCodeReplacer("{{product}}", $product->name, $messageAdmin);
        $messageAdmin = shortCodeReplacer("{{product_price}}", number_format($product->price, 0, '.', ''), $messageAdmin);
        $messageAdmin = shortCodeReplacer("{{amount}}", number_format($request->amount, 0, '.', ''), $messageAdmin);
        $messageAdmin = shortCodeReplacer("{{currency}}", $general->cur_text, $messageAdmin);
        
        $subject = $user->username." offer ".$request->amount." Euro on ".$product->name;
        $receive_mail = explode('@', $admins->email)[0];
        
        sendSellusEmail("offers@7-bids.com", $subject, $messageAdmin, $receive_mail);
        
        // sendSellusEmail($admins->email, $subject, $message, $receive_mail);
        
        if($product->admin){
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->user()->id;
            $adminNotification->title = 'A user has been bidden on your product';
            $adminNotification->click_url = urlPath('admin.product.bids',$product->id);
            $adminNotification->save();
            
            $notify[] = ['success', 'Price offer successfully sent.'];
            return back()->withNotify($notify);
        }


        $notify[] = ['success', 'Price offer successfully sent.'];
        return back()->withNotify($notify);

    }

    public function saveProductReview(Request $request)
    {

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'product_id' => 'required|integer'
        ]);

        Bid::where('user_id', auth()->id())->where('product_id', $request->product_id)->firstOrFail();


        $review = Review::where('user_id', auth()->id())->where('product_id', $request->product_id)->first();
        $product = Product::find($request->product_id);

        if(!$review){
            $review = new Review();
            $product->total_rating += $request->rating;
            $product->review_count += 1;
            $notify[] = ['success', 'Review given successfully'];
        }else{
            $product->total_rating = $product->total_rating - $review->rating + $request->rating;
            $notify[] = ['success', 'Review updated successfully'];
        }

        $product->avg_rating = $product->total_rating / $product->review_count;
        $product->save();

        $review->rating = $request->rating;
        $review->description = $request->description;
        $review->user_id = auth()->id();
        $review->product_id = $request->product_id;
        $review->save();

        return back()->withNotify($notify);

    }

    public function saveMerchantReview(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'merchant_id' => 'required|integer'
        ]);

        $merchant = Merchant::with('bids')->whereHas('bids', function($bid){
            $bid->where('user_id', auth()->id());
        })
        ->findOrFail($request->merchant_id);

        $review = Review::where('user_id', auth()->id())->where('merchant_id', $request->merchant_id)->first();

        if(!$review){
            $review = new Review();
            $merchant->total_rating += $request->rating;
            $merchant->review_count += 1;
            $notify[] = ['success', 'Review given successfully'];
        }else{
            $merchant->total_rating = $merchant->total_rating - $review->rating + $request->rating;
            $notify[] = ['success', 'Review updated successfully'];
        }

        $merchant->avg_rating = $merchant->total_rating / $merchant->review_count;
        $merchant->save();

        $review->rating = $request->rating;
        $review->description = $request->description;
        $review->user_id = auth()->id();
        $review->merchant_id = $request->merchant_id;
        $review->save();

        return back()->withNotify($notify);

    }
}
