<?php
namespace App\Http\Controllers\Seller\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function showLoginForm()
    {
        $pageTitle = "Merchant Login";
        return view('merchant.auth.login', compact('pageTitle'));
    }

    public function showRegistrationForm(){
        $pageTitle = 'Merchant Registration Page';
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('merchant.auth.register', compact('pageTitle','mobile_code','countries'));
    }
}