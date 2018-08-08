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

use Carbon\Carbon;


class SettingController extends Controller {

    protected $restrict_divisi = 2;
    protected $restrict_admin = 1;
    public function __construct() {
        // $this->middleware(function ($request, $next) {
        //     $this->credentials = Users::GetRoleById(Auth::id())->first();
        //     $this->setting     = Setting_Data::where('user_id',Auth::id())
        //                             ->where('status',1)
        //                             ->select('setting_list_id')
        //                             ->pluck('setting_list_id')->all();
        //     return $next($request);
        // });
        
    }

    public function index(Request $request) {
        return redirect('setting/show-background');    
    }

    public function report(Request $request) {
        $user_divisi = \Request::get('user_divisi');
        $allow = false;
        if(
            in_array($this->restrict_divisi,$user_divisi)
            ||
            in_array($this->restrict_admin,$user_divisi)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur report');
            return redirect('home');
        }


        // get the current time
        $current = date('Y-m-d H:i:s');
        // add 30 days to the current time
        $last_date = Carbon::now()->addDays(-7);

        

        $data['pending_daftar'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',1)->count();
        $data['pending_cetak'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',2)->count();
        $data['pending_aktif'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',3)->count();
        $data['kartu_aktif'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',4)->count();
        $data['tolak_daftar'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',5)->count();
        $data['tolak_cetak'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',6)->count();
        $data['tolak_aktif'] = Akses_Data::whereBetween('created_at',array($last_date,$current))->where('status_akses',7)->count();
        $data['period']      = $current." to ".$last_date;
        return view('setting/report',compact('data'));
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
    	Inventory_List::firstOrCreate([
    		"inventory_name"=>strtolower($request->inventory),
    		"updated_by"=>$request->updated_by
    	]);
        $request->session()->flash('alert-success', 'Inventory list telah di tambahkan');
    	return redirect('inventory');
    }


    public function show_background(Request $request) {
        $request->session()->flash('alert-danger', 'Maaf fitur edit background akan ada di tanggal 10 Agustus');
            return redirect('home');
        if($this->credentials->divisi == 4 || in_array('1',$this->setting)) {
            $data = array(
            'credentials'   => $this->credentials,
            'background'    => Design::first(),
            'setting'       => $this->setting
            );
            return view('setting/show_background',compact("data"));
        } else {
            $request->session()->flash('alert-danger', 'Maaf tidak ada akses untuk fitur setting');
        return redirect('home');
        }        
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
