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
use App\Http\Models\Status_Inventory;
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
        $inventory_division = 4;
        $setting_list = 6;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin_division,$user_divisi)
            ||
            in_array($inventory_division,$user_divisi)
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
        $data['report_for'] = "inventory";
        // // get the current time
        $data['current_date'] = date('Y-m-d');

        $date = strtotime("-7 day");
        $data['from_date'] =  date('Y-m-d', $date);


        $inventory_data = Inventory_Data::join('inventory_list',
                'inventory_list.id','=','inventory_data.inventory_list_id')
                ->whereDate('inventory_data.updated_at','>=',$data['from_date'])
                ->whereDate('inventory_data.updated_at','<=',$data['current_date']);

        if( !in_array($this->admin_division,$user_divisi) 
            &&
            !in_array($setting_list,$user_setting)
            &&
            in_array($inventory_division,$user_divisi)
            ) {

            $inventory_list_id_array = Users_Role::join('inventory_role',
                        'inventory_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi',4)
                        ->where('users_role.user_id',Auth::user()->id)
                        ->where('inventory_role.user_id',Auth::user()->id)
                        ->pluck('inventory_list_id');
            
            $inventory_data = $inventory_data->whereIn('inventory_list_id',$inventory_list_id_array);
        }
        

        $searching_for = "merk";        
        $inventory_data    = $inventory_data->groupBy('inventory_list.id')
                    ->select(
                    'inventory_list.id AS inventory_list_id',
                    'inventory_list.inventory_name AS inventory_list_name',
                    DB::raw('count(inventory_data.id) as count_data'),
                    DB::raw('sum(inventory_data.qty) as sum_data'),
                    DB::raw('"'.$searching_for.'" as search')
                    //'count(inventory_data.id) AS total'
                )
                ->get();
        
        if(count($inventory_data) < 1) {
            $request->session()->flash('alert-warning', 'there is no inventory data in this moving weekly report');
            return redirect('home');
        }


        $report_inventory_data = array();
        $total = 0;
        foreach($inventory_data as $key=>$val) {
            $report_inventory_data[$key] = $val;
            $report_inventory_data[$key]['data'] = Inventory_Data::where('inventory_list_id',$val['inventory_list_id'])
            ->groupBy($searching_for)
            ->select(
                'merk',
                DB::raw('count(inventory_data.id) as count_data'),
                DB::raw('sum(inventory_data.qty) as sum_data')
            )
            ->get();

            $total += $val['count_data'];
        }


        $data['total'] = $total;
        $data['report_inventory_data'] = $report_inventory_data;
        //dd($data);
        return view('setting/inventory_report',compact('data'));

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
        $data['report_for'] = "access";
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
                        'akses_data.status_akses',
                        'status_akses.color AS status_color',
                        DB::raw('count(akses_data.id) as total'))
                    ->groupBy('status_akses')
                    ->orderBy('status_akses.id','ASC')
                    ->get();

        if(count($akses_data) < 1) {
            $request->session()->flash('alert-warning', 'there is no access card data in this moving weekly report');
            return redirect('home');
        }

        $data['total'] = 0;
        $data['report'] = array();
        $color_report = array();
        foreach ($akses_data as $key => $value) {
            $data['report'][$value->status_name] = $value->total;
            $data['total'] += $value->total;
            array_push($color_report, $value->status_color);

        }

        //$data['color'] = Status_Akses::all();
        $data['color']  = $color_report;
        //dd($data);
        return view('setting/report',compact('data'));
    }

    public function inventory_report_download(Request $request) {
        //dd($request);
        $inventory_division = 4;
        $setting_list = 6;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin_division,$user_divisi)
            ||
            in_array($inventory_division,$user_divisi)
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

        $inventory_data = Inventory_Data::join('status_inventory',
                    'status_inventory.id','=','inventory_data.status_inventory')
                    ->join('inventory_list',
                    'inventory_list.id','=','inventory_data.inventory_list_id')
                    ->whereDate('inventory_data.updated_at','>=',$data['from_date'])
                    ->whereDate('inventory_data.updated_at','<=',$data['current_date']);

        if( !in_array($this->admin_division,$user_divisi) 
            &&
            !in_array($setting_list,$user_setting)
            &&
            in_array($inventory_division,$user_divisi)
            ) {

            $inventory_list_id_array = Users_Role::join('inventory_role',
                        'inventory_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi',4)
                        ->where('users_role.user_id',Auth::user()->id)
                        ->where('inventory_role.user_id',Auth::user()->id)
                        ->pluck('inventory_list_id');
            
            $inventory_data = $inventory_data->whereIn('inventory_list_id',$inventory_list_id_array);
        }

        if($request->category != null) {
            $inventory_data = $inventory_data->where('inventory_list_id',$request->category);
        }

        $data  = $inventory_data->select(
            'inventory_list.inventory_name AS inventory_category',
            'inventory_list.inventory_detail_name AS inventory_detail_category',
            'inventory_data.tanggal_update_data',
            'inventory_data.kategori',
            'inventory_data.kode_gambar',
            'inventory_data.dvr',
            'inventory_data.lokasi_site',

            'inventory_data.kode_lokasi',
            'inventory_data.jenis_barang',
            'inventory_data.merk',
            'inventory_data.tipe',
            'inventory_data.model',

            'inventory_data.serial_number',
            'inventory_data.psu_adaptor',
            'inventory_data.tahun_pembuatan',
            'inventory_data.tahun_pengadaan',
            'inventory_data.kondisi',

            'inventory_data.deskripsi',
            'inventory_data.asuransi',
            'inventory_data.lampiran',
            'inventory_data.tanggal_retired',
            'inventory_data.po',

            'inventory_data.qty',
            'inventory_data.keterangan',
            'status_inventory.name'
            )
            ->orderBy('inventory_data.updated_at','desc')
            ->get()
            ->toArray();
        //dd($data);
        $type = "xls";
        //$data = Akses_Data::get()->toArray();
        return Excel::create('inventory_report', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
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
        return Excel::create('access_card_report', function($excel) use ($data) {
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
