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
use App\Http\Models\Users_Role;
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
        $pic_division = 2;
        $setting_list = 5;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin_division,$user_divisi)
            ||
            in_array($pic_division,$user_divisi)
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
        $data['current_date'] = date('Y-m-d');

        $date = strtotime("-7 day");
        $data['from_date'] =  date('Y-m-d', $date);
        //dd($data);
        // // add 30 days to the current time
        // $last_date = Carbon::now()->addDays(-7);

        $akses_data = Akses_Data::join('status_akses',
                    'status_akses.id','=','akses_data.status_akses')
                    ->whereDate('akses_data.created_at','>=',$data['from_date'])
                    ->whereDate('akses_data.created_at','<=',$data['current_date']);

        if( !in_array($this->admin_division,$user_divisi) 
            &&
            !in_array($setting_list,$user_setting)
            &&
            in_array($pic_division,$user_divisi)
            ) {

            $pic_list_id_array = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi',2)
                        ->where('users_role.user_id',Auth::user()->id)
                        ->where('pic_role.user_id',Auth::user()->id)
                        ->pluck('pic_list_id');
            
            $akses_data = $akses_data->whereIn('pic_list_id',$pic_list_id_array);
        }

        $akses_data  = $akses_data->select('status_akses.name AS status_name',
                        'akses_data.status_akses'
                        ,DB::raw('count(akses_data.id) as total'))
                    ->groupBy('status_akses')
                    ->get();
        $data['total'] = 0;
        $data['report'] = array();
        foreach ($akses_data as $key => $value) {
            $data['report'][$value->status_name] = $value->total;
            $data['total'] += $value->total;
        }

        $data['color'] = Status_Akses::all();
        return view('setting/report',compact('data'));
    }

    public function report_download() {
        dd("REPORT");
    }

    public function show_background(Request $request) {
        $restrict_setting = 1;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin_division,$user_divisi)
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
