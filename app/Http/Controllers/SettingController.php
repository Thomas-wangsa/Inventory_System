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
use Excel;


class SettingController extends Controller {

    protected $admin_division = 1;

    public function __construct() {
        
    }

    public function index(Request $request) {
        return redirect('setting/show-background');    
    }

    public function inventory_report(Request $request) {
        echo "invntory report";
    }
    public function access_report(Request $request) {
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
                    ->whereDate('akses_data.updated_at','>=',$data['from_date'])
                    ->whereDate('akses_data.updated_at','<=',$data['current_date']);

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

        
        $akses_data = Akses_Data::leftjoin('pic_list',
                        'pic_list.id','=','akses_data.pic_list_id')
                        ->join('status_akses',
                        'status_akses.id','=','akses_data.status_akses')
                        ->join('users as c_user',
                        'c_user.id','=','akses_data.created_by')
                        ->join('users as u_user',
                        'u_user.id','=','akses_data.updated_by')
                        ->whereDate('akses_data.updated_at','>=',$data['from_date'])
                        ->whereDate('akses_data.updated_at','<=',$data['current_date']);                        

                        // ->select('akses_data.*',
                        //     'pic_list.vendor_name AS pic_list_vendor_name',
                        //     'pic_list.vendor_detail_name AS pic_list_vendor_detail_name',
                        //     'status_akses.name AS status_name',
                        //     'status_akses.color AS status_color',
                        //     'c_user.name AS created_by_username',
                        //     'u_user.name AS updated_by_username',)
                        // ->first();

        $data  = $akses_data->select('akses_data.name',
                'akses_data.email',
                'akses_data.nik',
                'akses_data.no_access_card AS no access card',
                'akses_data.date_start AS start access card active',
                'akses_data.date_end AS end access card active',
                'akses_data.type_daftar AS type',
                DB::raw('CONCAT(pic_list.vendor_name, " (", pic_list.vendor_detail_name,")") AS pic_category'),
                'akses_data.floor',
                'akses_data.divisi AS division',
                'akses_data.jabatan AS position',
                'status_akses.name AS status',
                DB::raw('CONCAT(c_user.name, " =", akses_data.created_at) AS created_by'),
                DB::raw('CONCAT(u_user.name, " =", akses_data.updated_at) AS updated_by'),
                'akses_data.additional_note AS note',
                'akses_data.comment AS comment'
                )
                ->orderBy('akses_data.updated_at','desc')
                ->get()
                ->toArray();

        //dd($akses_data);

        $type = "xls";
        //$data = Akses_Data::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
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
