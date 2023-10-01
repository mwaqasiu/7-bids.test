<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Slider;
use App\Models\Frontslider;
use App\Models\Paymentmethod;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $general = GeneralSetting::first();
        $pageTitle = 'General Setting';
        $timezones = json_decode(file_get_contents(resource_path('views/admin/partials/timezone.json')));
        return view('admin.setting.general_setting', compact('pageTitle', 'general','timezones'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'timezone' => 'required'
        ]);


        $general = GeneralSetting::first();
        $general->ev = $request->ev ? 1 : 0;
        $general->en = $request->en ? 1 : 0;
        $general->sv = $request->sv ? 1 : 0;
        $general->sn = $request->sn ? 1 : 0;
        $general->force_ssl = $request->force_ssl ? 1 : 0;
        $general->secure_password = $request->secure_password ? 1 : 0;
        $general->registration = $request->registration ? 1 : 0;
        $general->agree = $request->agree ? 1 : 0;
        $general->sitename = $request->sitename;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->base_color = $request->base_color;
        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = '.$request->timezone.' ?>';
        file_put_contents($timezoneFile, $content);
        $notify[] = ['success', 'General setting has been updated.'];
        return back()->withNotify($notify);
    }


    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo' => ['image',new FileTypeValidate(['jpg','jpeg','png'])],
            'favicon' => ['image',new FileTypeValidate(['png'])],
        ]);
        if ($request->hasFile('logo')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                Image::make($request->logo)->save($path . '/logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Logo could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                $path = imagePath()['logoIcon']['path'];
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                $size = explode('x', imagePath()['favicon']['size']);
                Image::make($request->favicon)->resize($size[0], $size[1])->save($path . '/favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Favicon could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon has been updated.'];
        return back()->withNotify($notify);
    }

    public function customCss(){
        $pageTitle = 'Custom CSS';
        $file = activeTemplate(true).'css/custom.css';
        $file_content = @file_get_contents($file);
        return view('admin.setting.custom_css',compact('pageTitle','file_content'));
    }


    public function customCssSubmit(Request $request){
        $file = activeTemplate(true).'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file,$request->css);
        $notify[] = ['success','CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function optimize(){
        Artisan::call('optimize:clear');
        $notify[] = ['success','Cache cleared successfully'];
        return back()->withNotify($notify);
    }

    public function charity() {
        $pageTitle = "Charity Project";
        $charitydata = Frontend::where('data_keys','charity.data')->firstOrFail();
        return view('admin.charity.index', compact('pageTitle', 'charitydata'));
    }

    public function charitySubmit(Request $request){
        $request->validate([
            'imageurl'=>'required',
            'amount'=>'required',
            'subamount'=>'required',
            'description'=>'required',
            'german_description'=>'required',
        ]);
        $charitys = Frontend::where('data_keys','charity.data')->firstOrFail();
        $charitys->data_values = [
            'pageflag' => $request->pageflag ? 1 : 0,
            'blogpageflag' => $request->blogpageflag ? 1 : 0,
            'url' => $request->imageurl,
            'description' => $request->description,
            'german_description' => $request->german_description,
            'amount' => $request->amount,
            'curamount' => $request->status ? $charitys->data_values->curamount + $request->subamount : $charitys->data_values->curamount - $request->subamount
        ];
        $charitys->save();
        $notify[] = ['success','Data updated successfully'];
        return back()->withNotify($notify);
    }

    public function paymentmethod() {
        $pageTitle = "Payment Methods";
        $emptyMessage = "Empty Data";
        $paymentmethods = Paymentmethod::latest()->paginate(getPaginate());
        return view('admin.payment.method',compact('pageTitle', 'paymentmethods', 'emptyMessage'));
    }

    public function paymentmethodstore(Request $request, $id=0) {
        $request->validate([
            'imageurl'=>'required',
            'name'=>'required',
            'imageiconname'=>'required',
        ]);

        $paymentmethod = new Paymentmethod();
        $notification =  'Payment added successfully';

        if($id){
            $paymentmethod = Paymentmethod::findOrFail($id);
            $paymentmethod->status = $request->status ? 1 : 0;
            $notification = 'Payment updated successfully';
        }

        $paymentmethod->name = $request->name;
        $paymentmethod->icon = $request->imageurl;
        $paymentmethod->filename = $request->imageiconname;
        $paymentmethod->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function deletePaymentmethod(Request $request)
    {
        $paymentmethod = Paymentmethod::findOrFail($request->paymentmethod_id);
        $paymentmethod->delete();

        $notify[] = ['success', 'Payment Deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function news() {
        $pageTitle = "News";
        $newsdata = Frontend::where('data_keys','news.data')->firstOrFail();
        return view('admin.news.index',compact('pageTitle', 'newsdata'));
    }

    public function newsSubmit(Request $request){
        $request->validate([
            'description'=>'required',
            'description_german'=>'required',
        ]);
        $news = Frontend::where('data_keys','news.data')->firstOrFail();
        $news->data_values = [
            'description' => $request->description,
            'description_german' => $request->description_german,
        ];
        $news->save();
        $notify[] = ['success','News updated successfully'];
        return back()->withNotify($notify);
    }

    public function sliders() {
        $pageTitle = "Slider";
        $sliders = Slider::get();
        $emptyMessage = "No Data";
        return view('admin.sliders.index', compact('pageTitle', 'emptyMessage', 'sliders'));
    }

    public function addSliders(Request $request) {
        $request->validate([
            'sellimagereplaceinputid' => 'required',
        ]);
        $sliders = new Slider();
        $sliders->url = $request->sellimagereplaceinputid;
        $sliders->save();
        $notify[] = ['success','Add image success'];
        return back()->withNotify($notify);
    }

    public function delSliders(Request $request) {
        $sliders = Slider::findOrFail($request->slider_id);
        $sliders->delete();
        $notify[] = ['success', 'Item Deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function pendingSliders(Request $request) {
        $sliders = Slider::findOrFail($request->slider_id);
        $sliders->status = 0;
        $sliders->save();
        $notify[] = ['success', 'Successfully'];
        return back()->withNotify($notify);
    }

    public function liveSliders(Request $request) {
        $sliders = Slider::findOrFail($request->slider_id);
        $sliders->status = 1;
        $sliders->save();
        $notify[] = ['success', 'Successfully'];
        return back()->withNotify($notify);
    }

    public function frontsliders() {
        $pageTitle = "Front Slider";
        $sliders = Frontslider::get();
        $emptyMessage = "No Data";
        return view('admin.sliders.frontindex', compact('pageTitle', 'emptyMessage', 'sliders'));
    }

    public function addFrontsliders(Request $request) {
        $request->validate([
            'sellimagereplaceinputid' => 'required',
            'main_heading' => 'required',
            'sub_text' => 'required',
            'slider_link' => 'required'
        ]);
        $sliders = new Frontslider();
        $sliders->url = $request->sellimagereplaceinputid;
        $sliders->main_heading = $request->main_heading;
        $sliders->sub_text = $request->sub_text;
        $sliders->slider_link = $request->slider_link;
        $sliders->save();
        $notify[] = ['success','Add image success'];
        return back()->withNotify($notify);
    }

    public function updateFrontSlider(Request $request, $id) {
        $sliders = Frontslider::findOrFail($id);
        $request->validate([
//            'sellimagereplaceinputid' => 'required',
            'main_heading' => 'required',
            'sub_text' => 'required',
            'slider_link' => 'required'
        ]);

        $sliders->update($request->all());

        $notify = ['success', 'Update image success'];
        return back()->withNotify($notify);
    }

    public function delFrontsliders(Request $request) {
        $sliders = Frontslider::findOrFail($request->slider_id);
        $sliders->delete();
        $notify[] = ['success', 'Item Deleted Successfully'];
        return back()->withNotify($notify);
    }

    public function pendingFrontsliders(Request $request) {
        $sliders = Frontslider::findOrFail($request->slider_id);
        $sliders->status = 0;
        $sliders->save();
        $notify[] = ['success', 'Successfully'];
        return back()->withNotify($notify);
    }

    public function liveFrontsliders(Request $request) {
        $sliders = Frontslider::findOrFail($request->slider_id);
        $sliders->status = 1;
        $sliders->save();
        $notify[] = ['success', 'Successfully'];
        return back()->withNotify($notify);
    }

    public function sliderCreate() {
        $pageTitle = "Add Slider Item";
        return view('admin.sliders.create', compact('pageTitle'));
    }

    public function cookie(){
        $pageTitle = 'GDPR Cookie';
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        return view('admin.setting.cookie',compact('pageTitle','cookie'));
    }

    public function cookieSubmit(Request $request){
        $request->validate([
            'link'=>'required',
            'description'=>'required',
            'german_description'=>'required',
        ]);
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        $cookie->data_values = [
            'link' => $request->link,
            'description' => $request->description,
            'german_description' => $request->german_description,
            'status' => $request->status ? 1 : 0,
        ];
        $cookie->save();
        $notify[] = ['success','Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }

    public function merchantProfile(){
        $pageTitle = 'Merchant Profile for Admin';

        return view('admin.setting.merchant_profile',compact('pageTitle'));
    }

    public function merchantProfileSubmit(Request $request){
        $request->validate([
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
            'cover_image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);

        $general = GeneralSetting::first();

        $merchantProfile = [];

        $merchantProfile['name'] = $request->merchant_name;
        $merchantProfile['mobile'] = $request->merchant_mobile;
        $merchantProfile['address'] = $request->merchant_address;

        if ($request->hasFile('image')) {
            try {
                $old = @$general->merchant_profile->image;
                $merchantProfile['image'] = uploadImage($request->image, imagePath()['profile']['admin']['path'], imagePath()['profile']['admin']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }else{
            $merchantProfile['image'] = @$general->merchant_profile->image;
        }

        if ($request->hasFile('cover_image')) {
            try {
                $old = @$general->merchant_profile->cover_image;
                $merchantProfile['cover_image'] = uploadImage($request->cover_image, imagePath()['profile']['admin_cover']['path'], imagePath()['profile']['admin_cover']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
        }else{
            $merchantProfile['cover_image'] = @$general->merchant_profile->cover_image;
        }

        $general->merchant_profile = $merchantProfile;
        $general->save();


        $notify[] = ['success', 'Merchant profile has been updated.'];
        return back()->withNotify($notify);
    }
}
