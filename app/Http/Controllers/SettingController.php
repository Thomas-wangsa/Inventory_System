<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Inventory_List;
use App\Http\Models\Setting_Data;
use App\Http\Models\Design;
use App\Http\Models\Akses_Data;
use App\Http\Models\Inventory_Data;

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
        return redirect('setting/show-background');
        if($this->credentials->divisi == 1) {
            $access = true;
        } else {
            $check = Setting_Data::where('user_id',$this->credentials->id)->first();

            if(count($check) > 0) {
                $access = true;
            } else {
                $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur setting');
                return redirect('home'); 
            }  
        }

        if($access) {
            $data = array(
            'credentials' => $this->credentials
            );

            if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
            } else if ($this->credentials->divisi == 2) {
                switch ($this->credentials->id_jabatan) {
                    case 2:
                        $data['notify']         = Akses_Data::where('status_akses',1)->count();
                        break;
                    case 3:
                        $data['notify']         = Akses_Data::where('status_akses',2)->count();
                        break;
                    case 4:
                        $data['notify']         = Akses_Data::where('status_akses',3)->count();
                        break;
                    case 5:
                        $data['notify']         = Akses_Data::where('status_akses',4)->count();
                        break;
                    case 6:
                        $data['notify']         = Akses_Data::where('status_akses',5)->count();
                        break;
                    
                    default:
                        # code...
                        break;
                }

            } else if($this->credentials->divisi == 3) {
                switch($this->credentials->id_jabatan) {
                    case 2:
                        $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                        break;
                }
                
            }
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
                return redirect('setting'); 
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


    public function show_background(Request $request) {

        $data = array(
            'credentials'   => $this->credentials,
            'background'    => Design::first()
            );
            return view('setting/show_background',compact("data"));



        
    }

    public function update_background(Request $request) {
        $request->validate([
        'background' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('background')) {
            $image = $request->file('background');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/template/');
            $image->move($destinationPath, $name);

            $design = Design::find(1);
            $design->logo = "/images/template/".$name;
            $design->save();

            $request->session()->flash('alert-success', 'Background telah di update');
            
        } else {
            $request->session()->flash('alert-danger', 'Please contact your administrator');
        }

        return redirect('setting/show-background');
    }
}
