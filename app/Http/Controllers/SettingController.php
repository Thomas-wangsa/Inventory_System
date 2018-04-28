<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SettingController extends Controller
{
    public function index() {
    	$user = Auth::user();
    	return view('setting/index',compact("user"));
    }

    public function add_inventory(Request $request) {
    	dd($request);
    }
}
