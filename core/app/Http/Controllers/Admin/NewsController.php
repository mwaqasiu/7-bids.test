<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Frontend;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(){
        $pageTitle = 'News';
        // $newses = Frontend::where('data_keys','news.data')->firstOrFail();
        return view('admin.news.index',compact('pageTitle'));
    }

    // public function cookieSubmit(Request $request){
    //     $request->validate([
    //         'link'=>'required',
    //         'description'=>'required',
    //     ]);
    //     $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
    //     $cookie->data_values = [
    //         'link' => $request->link,
    //         'description' => $request->description,
    //         'status' => $request->status ? 1 : 0,
    //     ];
    //     $cookie->save();
    //     $notify[] = ['success','Cookie policy updated successfully'];
    //     return back()->withNotify($notify);
    // }
}
