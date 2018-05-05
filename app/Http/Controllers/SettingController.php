<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Inventory_List;

use App\Http\Models\Users;


class SettingController extends Controller {

    protected $credentials;

    public function __construct() {

        $this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            return $next($request);
        });
        
    }

    public function index(Request $request) {
        if($this->credentials->divisi != 1) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur ini');
            return redirect('home'); 
        }
        $data = array(
            'credentials' => $this->credentials
        );
    	return view('setting/index',compact("data"));
    }


    public function show_inventory() {
        if($this->credentials->divisi == 1) {
            $access = true;
        } else {
            
        }
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
