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
use App\Http\Models\Status_Akses;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class SettingController extends Controller {

    protected $admin_division = 1;

    public function __construct() {
        
    }

    public function index(Request $request) {
        return redirect('setting/show-background');    
    }

    public function report(Request $request) {
        $access_division = 2;
        $setting_list = 5;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin_division,$user_divisi)
            ||
            in_array($access_division,$user_divisi)
            || 
            in_array($setting_list,$user_setting)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to report features');
            return redirect('home');
        }


        $data = array();
        // // get the current time
        $current_date = date('Y-m-d');

        $date = strtotime("-7 day");
        $from_date =  date('M d, Y', $date);
        // // add 30 days to the current time
        // $last_date = Carbon::now()->addDays(-7);

        $akses_data = Akses_Data::join('status_akses',
                    'status_akses.id','=','akses_data.status_akses')
                    ->whereBetween('akses_data.created_at',array($from_date,$current_date))
                    ->select('status_akses.name AS status_name','akses_data.status_akses',DB::raw('count(akses_data.id) as total')
                            )
                    ->groupBy('status_akses')
                    ->get();
        
        foreach ($akses_data as $key => $value) {
            $data[$value->status_name] = $value->total;
        }


        $color = Status_Akses::all();
        //dd($color);
        return view('setting/report',compact('data','color'));
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
