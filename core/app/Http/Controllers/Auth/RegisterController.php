<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\UserNotification;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\Merchant;
use App\Models\UserLogin;
use App\Models\Shopping;
use App\Models\Wishlist;
use App\Models\Auctionwishlist;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('regStatus')->except('registrationNotAllowed');

        $this->activeTemplate = activeTemplate();
    }
    
    public function showSellerRegistrationForm(){
        $pageTitle = 'Seller Registration Page';
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'user.auth.sellerregister', compact('pageTitle','mobile_code','countries'));
    }

    public function showRegistrationForm()
    {
        $pageTitle = "Sign Up";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        if(auth()->check()) {
            $shopping_count = Shopping::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
            $wishlist_count = Wishlist::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->count() + Auctionwishlist::where('user_id', auth()->user()->id)->orWhere('ip_address', getenv('REMOTE_ADDR'))->count();
        } else {
            $shopping_count = Shopping::where('ip_address', getenv('REMOTE_ADDR'))->where('status', 1)->count();
            $wishlist_count = Wishlist::where('ip_address', getenv('REMOTE_ADDR'))->count() + Auctionwishlist::where('ip_address', getenv('REMOTE_ADDR'))->count();
        }
        return view($this->activeTemplate . 'user.auth.register', compact('pageTitle','mobile_code','countries', 'shopping_count', 'wishlist_count'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:users',
            'email' => 'required|string|email|max:90|unique:merchants',
            'mobile' => 'required|string|max:50|unique:users',
            'mobile' => 'required|string|max:50|unique:merchants',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:users|min:6',
            'username' => 'required|alpha_num|unique:merchants|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $exist = User::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $general = GeneralSetting::first();
        
        // Merchant Create
        $merchant = new Merchant();
        $merchant->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $merchant->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $merchant->email = strtolower(trim($data['email']));
        $firstname = isset($data['firstname']) ? $data['firstname'] : "";
        $lastname = isset($data['lastname']) ? $data['lastname'] : "";
        $merchant->shopname = $firstname." ".$lastname;
        $merchant->password = Hash::make($data['password']);
        $merchant->username = trim($data['username']);
        $merchant->country_code = $data['country_code'];
        $merchant->mobile = $data['mobile_code'].$data['mobile'];
        $merchant->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $merchant->status = 0;
        $merchant->ev = $general->ev ? 0 : 1;
        $merchant->sv = $general->sv ? 0 : 1;
        $merchant->ts = 0;
        $merchant->tv = 1;
        $merchant->save();

        // User Create
        $user = new User();
        $user->firstname = isset($data['firstname']) ? $data['firstname'] : null;
        $user->lastname = isset($data['lastname']) ? $data['lastname'] : null;
        $user->email = strtolower(trim($data['email']));
        $user->password = Hash::make($data['password']);
        $user->username = trim($data['username']);
        $user->country_code = $data['country_code'];
        $user->mobile = $data['mobile_code'].$data['mobile'];
        $user->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $user->status = 1;
        $user->bonuspoint = $data['bonuspoint'];
        $user->ev = $general->ev ? 0 : 1;
        $user->sv = $general->sv ? 0 : 1;
        $user->ts = 0;
        $user->tv = 1;
        $user->save();
        
        $userNotification = new UserNotification();
        $userNotification->user_id = $user->id;
        $userNotification->n_user_id = $user->id;
        $userNotification->auction_id = 0;
        $userNotification->title = 'Welcome, thank you for joining! We recommend 2FA Authentication and don\'t forget to complete your profile.';
        $userNotification->click_url = "";
        $userNotification->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'New member registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$user->id);
        $adminNotification->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = UserLogin::where('user_ip',$ip)->first();
        $userLogin = new UserLogin();

        //Check exist or not
        if ($exist) {
            $userLogin->longitude =  $exist->longitude;
            $userLogin->latitude =  $exist->latitude;
            $userLogin->city =  $exist->city;
            $userLogin->country_code = $exist->country_code;
            $userLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $userLogin->longitude =  @implode(',',$info['long']);
            $userLogin->latitude =  @implode(',',$info['lat']);
            $userLogin->city =  @implode(',',$info['city']);
            $userLogin->country_code = @implode(',',$info['code']);
            $userLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $userLogin->user_id = $user->id;
        $userLogin->user_ip =  $ip;

        $userLogin->browser = @$userAgent['browser'];
        $userLogin->os = @$userAgent['os_platform'];
        $userLogin->save();


        return $user;
    }

    public function checkUser(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = User::where('email',$request->email)->first();
            $exist['type'] = 'email';
            if($exist['data'] != null) {
                return response($exist);
            }
            else {
                $exist['data'] = Merchant::where('email',$request->email)->first();
                return response($exist);
            }
        }
        if ($request->mobile) {
            $exist['data'] = User::where('mobile',$request->mobile)->first();
            $exist['type'] = 'mobile';
            if($exist['data'] != null) {
                return response($exist);
            }
            else {
                $exist['data'] = Merchant::where('mobile',$request->mobile)->first();
                return response($exist);
            }
        }
        if ($request->username) {
            $exist['data'] = User::where('username',$request->username)->first();
            $exist['type'] = 'username';
            if($exist['data'] != null) {
                return response($exist);
            }
            else {
                $exist['data'] = Merchant::where('username',$request->username)->first();
                return response($exist);
            }
        }
    }

    public function registered()
    {
        return redirect()->route('user.home');
    }

}
