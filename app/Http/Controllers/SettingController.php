<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Inventory_List;


class SettingController extends Controller
{
    public function index() {
    	$user = Auth::user();
    	return view('setting/index',compact("user"));
    }

    public function add_inventory(Request $request) {
    	//dd($request);
    	Inventory_List::firstOrCreate([
    		"inventory_name"=>strtolower($request->inventory),
    		"updated_by"=>$request->updated_by
    	]);
    	return redirect('setting');
    }
}
