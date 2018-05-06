<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Inventory_List;
use App\Http\Models\Setting_Data;

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
        if($this->credentials->divisi == 1) {
            $access = true;
        } else {
            $check = Setting_Data::where('user_id',$this->credentials->id)->first();

            if(count($check) > 0) {
                $access = true;
            } else {
                $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur ini');
                return redirect('home'); 
            }
            
        }

        if($access) {
            $data = array(
            'credentials' => $this->credentials
            );
            return view('setting/index',compact("data"));
        } else {
            $request->session()->flash('alert-danger', 'Please contact your administrator');
            return redirect('home');
        }
        
    }


    public function show_inventory(Request $request) {
        $setting_list_id = 1;
        if($this->credentials->divisi == 1) {
            $access = true;
        } else {
            $check = Setting_Data::where('user_id',$this->credentials->id)
            ->where('setting_list_id',$setting_list_id)
            ->first();

            if(count($check) > 0) {
                $access = true;
            } else {
                $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur ini');
                return redirect('home'); 
            }
        }

        if($access) {
            $data = array(
            'credentials' => $this->credentials
            );
            return view('setting/setting_inventory',compact("data"));
        } else {
            $request->session()->flash('alert-danger', 'Please contact your administrator');
            return redirect('setting');
        }
    }

    public function add_inventory(Request $request) {
    	//dd($request);
    	Inventory_List::firstOrCreate([
    		"inventory_name"=>strtolower($request->inventory),
    		"updated_by"=>$request->updated_by
    	]);
        $request->session()->flash('alert-success', 'Inventory list telah di tambahkan');
    	return redirect('setting');
    }
}
