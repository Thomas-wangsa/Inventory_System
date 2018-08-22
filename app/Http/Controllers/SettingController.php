<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Inventory_List;
use App\Http\Models\Setting_Role;
use App\Http\Models\Design;
use App\Http\Models\Akses_Data;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Users;

use Carbon\Carbon;


class SettingController extends Controller {

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
        $restrict_divisi = 2;
        $restrict_setting = 5;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->restrict_admin,$user_divisi)
            ||
            in_array($restrict_divisi,$user_divisi)
            || 
            in_array($restrict_setting,$user_setting)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to report features');
            return redirect('home');
        }


        // get the current time
        $current = date('Y-m-d');
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



    public function show_background(Request $request) {
        $restrict_setting = 1;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->restrict_admin,$user_divisi)
            || 
            in_array($restrict_setting,$user_setting)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have access to report features');
            return redirect('home');
        }

        $data = array(
            'background' => Design::first()
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
