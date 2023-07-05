<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Image;

class CharityProjectController extends Controller
{
    public function index()
    {
        $pageTitle = 'Charity Project';
        return view('admin.charity.index', compact('pageTitle'));
    }
}
