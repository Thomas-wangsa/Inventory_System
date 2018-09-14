<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Sub_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Setting_Data;
use App\Http\Models\Akses_Data;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Map;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Divisi;
use App\Notifications\Inventory_Notification;
use Illuminate\Support\Facades\Input;
use Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Notification AS notify;

use Illuminate\Support\Facades\Notification;

use Faker\Factory as Faker;


class InventoryController extends Controller
{	
	protected $redirectTo = 'inventory';
    protected $credentials;
    protected $faker;
    protected $admin = 1;
    protected $env = "production";
    public function __construct() {
        $this->faker    = Faker::create();
        $this->env      = env("ENV_STATUS", "development");
        if($this->env == "production") {
            
            // sleep(5);
            // echo "AAA";die;

        }
    }


    public function index(Request $request) {
        $user_divisi = \Request::get('user_divisi');
        $restrict_divisi_inventory = 4;
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($restrict_divisi_inventory,$user_divisi)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to inventory page');
            return redirect('home');
        }

        $data = array();
        $data['insert_inventory_data'] = false;

        //Check Inventory Data
        $count_level_staff = Users_Role::join('inventory_role',
                'inventory_role.id','=','users_role.jabatan')
                ->where('users_role.divisi',$restrict_divisi_inventory)
                ->where('users_role.user_id',Auth::user()->id)
                ->where('inventory_role.user_id',Auth::user()->id)
                ->where('inventory_role.inventory_level_id',1)
                ->count();
        //dd($count_level_staff);
        if($count_level_staff > 0) {
            $data['insert_inventory_data'] = true;
        }

        // for search status inventory
        $data['search_status'] = Status_Inventory::all();

        // inventory list auth
        if(in_array($this->admin,$user_divisi)) {
            $data['inventory_list_id'] = Inventory_List::all();
        } else {
            $roles = Users_Role::join('inventory_role',
                    'inventory_role.id','=','users_role.jabatan')
                    ->where('users_role.divisi','=',4)
                    ->where('users_role.user_id','=',Auth::user()->id)
                    ->where('inventory_role.user_id','=',Auth::user()->id)
                    ->pluck('inventory_list_id')
                    ->toArray();
            //dd(count($roles));
            if(count($roles) < 1) {
                $request->session()->flash('alert-danger', 'Sorry you dont have roles to inventory page');
                return redirect('home');
            }

            $data['inventory_list_id'] = Inventory_List::whereIn('id',$roles)
                                    ->get();
        }

        // For the data
        $inventory_data = Inventory_Data::join('inventory_list',
                    'inventory_list.id','=','inventory_data.inventory_list_id')
                    ->join('status_inventory',
                    'status_inventory.id','=','inventory_data.status_inventory')
                    ->join('users AS c_users',
                    'c_users.id','=','inventory_data.created_by')
                    ->join('users AS u_users',
                    'u_users.id','=','inventory_data.updated_by')
                    ->leftjoin('map_location',
                    'map_location.inventory_data_id','=','inventory_data.id');

        $data['map']        = Map::all();

        $head_role_inventory = array();
        if(!in_array($this->admin,$user_divisi)) {

            $head_role_inventory = Users_Role::join('inventory_role',
                'inventory_role.id','=','users_role.jabatan')
                ->where('users_role.divisi',$restrict_divisi_inventory)
                ->where('users_role.user_id',Auth::user()->id)
                ->where('inventory_role.user_id',Auth::user()->id)
                ->where('inventory_role.inventory_level_id',2)
                ->pluck('inventory_role.inventory_list_id')
                ->toArray();


            $inventory_list_users = Users_Role::join('inventory_role',
                        'inventory_role.id','=','users_role.jabatan')
                        ->join('inventory_list',
                        'inventory_list.id','=','inventory_role.inventory_list_id')
                        ->where('users_role.divisi',$restrict_divisi_inventory)
                        ->where('users_role.user_id','=',Auth::user()->id)
                        ->where('inventory_role.user_id','=',Auth::user()->id)
                        ->pluck('inventory_list.id')->toArray();
            
            $inventory_data = $inventory_data->whereIn('inventory_list_id',$inventory_list_users);

            $data['map']        = Map::whereIn('inventory_list_id',$inventory_list_users)->get();           
        }

        if($request->search == "on") {
            if($request->search_nama != null) {

            } 
            else if($request->search_filter != null) {
                $inventory_data = $inventory_data->where('inventory_data.status_inventory',$request->search_filter);            
            } else if($request->search_uuid != null) {
                $inventory_data = $inventory_data->where('inventory_data.uuid',$request->search_uuid);
            }
        }

        $inventory_data = $inventory_data->select(
                'inventory_list.inventory_name AS inventory_list_name',
                'status_inventory.name AS status_inventory_name',
                'status_inventory.color AS status_inventory_color',
                'c_users.name AS users_created_by',
                'inventory_data.inventory_list_id',
                'inventory_data.dvr AS inventory_data_dvr',
                'inventory_data.lokasi_site',
                'inventory_data.qty AS inventory_data_qty',
                'inventory_data.status_inventory AS inventory_data_status',
                'inventory_data.comment',
                'inventory_data.uuid',
                'map_location.id AS map_location_id'
            )
            ->orderBy('inventory_data.created_at','desc')
            ->paginate(10);

        $conditional_head = array();
        foreach($inventory_data as $key=>$val) {
            $conditional_head[$key] = false;
            if(in_array($val->inventory_list_id,$head_role_inventory)) {
                $conditional_head[$key] = true;
            }
        }

        $data['inventory_data']     = $inventory_data;
        $data['conditional_head']   = $conditional_head;
        $data['faker']              = $this->faker;

        //dd($data);
    	return view('inventory/index',compact('data'));
    }

    public function get_inventory_data_ajax(Request $request) {
        $response['status'] = false;

        $data = Inventory_Data::join('status_inventory',
                'status_inventory.id','=','inventory_data.status_inventory')
                ->join('users AS c_users',
                'c_users.id','=','inventory_data.created_by')
                ->join('users AS u_users',
                'u_users.id','=','inventory_data.updated_by')
                ->leftjoin('map_location',
                'map_location.inventory_data_id','=','inventory_data.id')
                ->leftjoin('map',
                'map.id','=','map_location.map_id')
                ->where('uuid',$request->uuid)
                ->select(
                    'inventory_data.*',
                    'status_inventory.name AS status_name',
                    'status_inventory.color AS status_color',
                    'c_users.name AS c_username',
                    'u_users.name AS u_username',
                    'map_location.id AS map_location_id',
                    'map_location.image_location',
                    'map.map_images'
                )
                ->first();
        if(count($data) < 1 ) {
            $response['message'] = "Inventory Data is not found!";
            return json_encode($response);
        }
        $response['status'] = true;
        $response['data']   = $data;
        return json_encode($response);
    }
    public function inventory_insert_data(Request $request) {
        $request->validate([
            'inventory_list_id'=>'required'
        ]);

        $uuid = time().$this->faker->uuid;
        $status_inventory = 1;
        $data = array(
            'inventory_list_id' => $request->inventory_list_id,
            'status_inventory'=>$status_inventory,
            'created_by'=>Auth::user()->id,
            'updated_by'=>Auth::user()->id,


            'tanggal_update_data' => $request->tanggal_update_data,
            'kategori' => $request->kategori,
            'kode_gambar' => $request->kode_gambar,
            'dvr' => $request->dvr,
            'lokasi_site' => $request->lokasi_site,

            'kode_lokasi' => $request->kode_lokasi,
            'jenis_barang' => $request->jenis_barang,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'model' => $request->model,

            'serial_number' => $request->serial_number,
            'psu_adaptor' => $request->psu_adaptor,
            'tahun_pembuatan' => $request->tahun_pembuatan,
            'tahun_pengadaan' => $request->tahun_pengadaan,
            'kondisi' => $request->kondisi,


            'deskripsi' => $request->deskripsi,
            'asuransi' => $request->asuransi,
            'lampiran' => $request->lampiran,
            'tanggal_retired' => $request->tanggal_retired,
            'po' => $request->po,

            'qty' => $request->qty,
            'keterangan' => $request->keterangan,

            'uuid'  => $uuid,
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        );

        DB::table('inventory_data')->insert($data);
        
        $this->notify($status_inventory,$uuid);
        $request->session()->flash('alert-success', 'new inventory already registered');
        return redirect($this->redirectTo."?search=on&search_uuid=".$data['uuid']);
    }


    public function notify($status,$uuid) {
        $inventory_data = Inventory_Data::where('status_inventory',$status)
                    ->where('uuid',$uuid)->first();

        if(count($inventory_data) < 1) {
            echo "Inventory Data not found";die;
            //return redirect($this->redirectTo);
        } 
        
        $notify = array();

        $user_updated     = Auth::user()->id;
        array_push($notify,$user_updated);

        $user_created = $inventory_data->created_by;
        if(!in_array($user_created,$notify)) {
            array_push($notify,$user_created);
        }

        $next_user   = array();
        switch($status) {
            case "1" :
                $next_user = Users_Role::join('inventory_role',
                    'inventory_role.id','=','users_role.jabatan')
                    ->where('users_role.divisi','=',4)
                    ->where('inventory_list_id',$inventory_data->inventory_list_id)
                    ->where('inventory_level_id',2)
                    ->pluck('inventory_role.user_id');
                break;
            case "2" :
                $next_user = Users_Role::where('divisi',1)
                ->pluck('user_id');
                break;
            default :
                break;
        }

        foreach ($next_user as $key => $val) {
            if(!in_array($val,$notify)) {
                    array_push($notify,$val);
                }
        }


        $notify_status = 5;
        $notify_category = 4;
        if($status == 1) {
            $notify_status = 4;
        }

        foreach($notify as $key=>$val) {
            $data_notify = array(
            'user_id'           => $val,
            'category'          => $notify_category,
            'data_id'           => $inventory_data->id,
            'status_data_id'    => $status,
            'sub_notify_id'     => $notify_status,
            );

            notify::firstOrCreate($data_notify);
        }

        if($this->env != "development") {
            $data['notify_user']        = $notify;
            $data['data']               = $inventory_data;
            //$this->send($data);
        }
        
    }

    public function inventory_get_info_by_uuid(Request $request) {
        $response = array();
        $response['status'] = false;

        $data = Inventory_Data::join('inventory_list',
                'inventory_list.id','=','inventory_data.inventory_list_id')
                ->where('uuid',$request->uuid)
                ->select('inventory_data.*','inventory_list.inventory_name AS inventory_list_name')
                ->first();
        if(count($data) < 1) {
            $response['message'] = "Inventory data ID not found";
            return json_encode($response);
        }

        $response['status'] = true;
        $response['data'] = $data; 
        return json_encode($response);
    }

    public function inventory_update_data(Request $request) {
        $request->validate([
                'uuid' => 'required',
        ]);

        $inventory_data = Inventory_Data::where('status_data',1)
                ->where('uuid',$request->uuid)
                ->first();

        if(count($inventory_data) < 1 ) {
            $request->session()->flash('alert-danger', 'credentials not found');
            return redirect($this->redirectTo);
        }

        $inventory_data->tanggal_update_data = $request->tanggal_update_data;
        $inventory_data->kategori = $request->kategori;
        $inventory_data->kode_gambar = $request->kode_gambar;
        $inventory_data->dvr = $request->dvr;
        $inventory_data->lokasi_site = $request->lokasi_site;

        $inventory_data->kode_lokasi = $request->kode_lokasi;
        $inventory_data->jenis_barang = $request->jenis_barang;
        $inventory_data->merk = $request->merk;
        $inventory_data->tipe = $request->tipe;
        $inventory_data->model = $request->model;


        $inventory_data->serial_number = $request->serial_number;
        $inventory_data->psu_adaptor = $request->psu_adaptor;
        $inventory_data->tahun_pembuatan = $request->tahun_pembuatan;
        $inventory_data->tahun_pengadaan = $request->tahun_pengadaan;
        $inventory_data->kondisi = $request->kondisi;


        $inventory_data->deskripsi = $request->deskripsi;
        $inventory_data->asuransi = $request->asuransi;
        $inventory_data->lampiran = $request->lampiran;
        $inventory_data->tanggal_retired = $request->tanggal_retired;
        $inventory_data->po = $request->po;

        $inventory_data->qty = $request->qty;
        $inventory_data->keterangan = $request->keterangan;
        $inventory_data->updated_by     = Auth::user()->id;

        $bool = $inventory_data->save();

        if(!$bool) {
            $request->session()->flash('alert-danger', 'Update Failed');
        } else {
            $request->session()->flash('alert-success', 'Update Success');
        }

        return redirect($this->redirectTo."?search=on&search_uuid=".$inventory_data->uuid);
    }

    public function map_location() {
        $data['credentials']    = $this->credentials;
        return view('inventory/map',compact('data'));
    }


    public function upload_excel(Request $request) {
        $allow = false;
        $request->validate([
            'excel_file' => 'required|max:10000',
            'inventory_list_id'=>'required'
        ]);

        
        if(
            $request->excel_file->getClientOriginalExtension() == 'xls' 
            ||
            $request->excel_file->getClientOriginalExtension() == 'xlsx'
          ) 
        {
            $allow = true;
        } 


        if(!$allow) {
            $request->session()->flash('alert-danger', 'Extension file must in xls or xlsx');
            return redirect('inventory');
        }
        
        if(Input::hasFile('excel_file')){
            $path = Input::file('excel_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            if(!empty($data) && $data->count()){
                $file_name = $request->excel_file->getClientOriginalName();
                $uuid = $this->faker->uuid;
                foreach ($data as $key => $value) {
                    $insert[] = array(
                        'inventory_list_id'=>$request->inventory_list_id,
                        'status_inventory'=>1,
                        'created_by'=>Auth::user()->id,
                        'updated_by'=>Auth::user()->id,

                        'file_name_upload'=>$file_name,

                        'tanggal_update_data'=>$value->tanggal_update_data,
                        'kategori'=>$value->kategori,
                        'kode_gambar'=>$value->kode_gambar,
                        'dvr'=>$value->dvr,
                        'lokasi_site'=>$value->lokasi_site,

                        'kode_lokasi'=>$value->kode_lokasi,
                        'jenis_barang'=>$value->jenis_barang,
                        'merk'=>$value->merk,
                        'tipe'=>$value->tipe,
                        'model'=>$value->model,

                        'serial_number'=>$value->serial_number,
                        'psu_adaptor'=>$value->psu_adaptor,
                        'tahun_pembuatan'=>$value->tahun_pembuatan,
                        'tahun_pengadaan'=>$value->tahun_pengadaan,
                        'kondisi'=>$value->kondisi,

                        'deskripsi'=>$value->deskripsi,
                        'asuransi'=>(int)$value->asuransi,
                        'lampiran'=>$value->lampiran,
                        'tanggal_retired'=>$value->tanggal_retired,
                        'po'=>$value->po,

                        'qty'=>(int) $value->qty,
                        'keterangan'=>$value->keterangan,

                        'uuid'=>time().$this->faker->uuid,
                        'created_at'=>date('Y-m-d H:i:s'),
                        'updated_at'=>date('Y-m-d H:i:s')
                    );
                    //dd($value);
                    // $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                if(!empty($insert)){
                    $check = Inventory_Data::insert($insert);
                    if($check) {
                        $request->session()->flash('alert-success', 'Upload Successed!');
                        return redirect('inventory');
                    } else {
                        $request->session()->flash('alert-danger', 'Insert Failed!');
                        return redirect('inventory');
                    }
                } else {
                    $request->session()->flash('alert-danger', 'no rows found!');
                    return redirect('inventory');
                }
            } else {
              $request->session()->flash('alert-danger', 'no data excel found!');
                return redirect('inventory');  
            }
        } else {
            $request->session()->flash('alert-danger', 'Excel file not found!');
            return redirect('inventory');
        }
        
    }
    public function add_inventory(Request $request) {
        $request->validate([
            'inventory_name'  => 'required|max:30',
            'inventory_detail_name' => 'max:50',
        ]);

        $data = array(
        'inventory_name' => trim(strtolower($request->inventory_name)),
        'inventory_detail_name'=>trim(strtolower($request->inventory_detail_name)),
        'updated_by'=>Auth::user()->id
        );

        $count_exist = Inventory_List::where('inventory_name',$data['inventory_name'])->count();

        if($count_exist > 0) {
            $request->session()->flash('alert-danger', 'category already exists in system');
            return redirect('inventory');
        }


        Inventory_List::firstOrCreate($data);
        $request->session()->flash('alert-success', 'inventory category has been registered');
        return redirect('inventory');
    }

    public function inventory_approval(Request $request) {
        $data = Inventory_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();
        //dd($data);
        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            
            if($data->status_inventory == 1) {
                if($request->next_status == 2) {
                    $data->status_inventory = $request->next_status;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Error: approved by head inventory is error');
                    return redirect($this->redirectTo);
                }
            } else if($data->status_inventory == 2) {
                if($request->next_status == 3) {
                    $data->status_inventory = $request->next_status;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Error: approved by administrator is error');
                    return redirect($this->redirectTo);
                }
            } 
            
        }

        $this->notify($data->status_inventory,$data->uuid);
        $request->session()->flash('alert-success', 'inventory has been updated');
        return redirect($this->redirectTo."?search=on&search_uuid=".$data->uuid);
        // return view('inventory/approval');
    }

    public function inventory_reject(Request $request) {
        $data = Inventory_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();

        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } 
        return view('inventory/reject',compact("data"));
    }

    public function proses_reject(Request $request) {
        //dd($request);
        $data = Inventory_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();

        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            if($data->status_data == 1) {

                switch ($data->status_inventory) {
                    case 1:
                        $data->status_inventory = 4;
                        break;
                    case 2:
                        $data->status_inventory = 5;
                        break;
                    default:
                        # code...
                        break;
                }
                
                $data->status_data  = 2;
                $data->updated_by   = Auth::user()->id;
                $data->comment      = $request->desc;
                $data->save();
            } else {
                return redirect($this->redirectTo);
            }
            
        }

        $this->notify($data->status_inventory,$data->uuid);
        $request->session()->flash('alert-success', 'inventory has been rejected');
        return redirect($this->redirectTo."?search=on&search_uuid=".$data->uuid);
    }

    public function create_new_inventory(Request $request) {

    	$sub_list = Inventory_Sub_List::where('inventory_sub_list_name', strtolower($request->nama_barang))->first();
    	if(count($sub_list) < 1 ) {
    		$sub_list = Inventory_Sub_List::firstOrCreate([
    			'inventory_list_id' 		=> $request->kategori,
    			'inventory_sub_list_name'	=> strtolower($request->nama_barang)
    		]);
    	}

    	$inventory = Inventory_Data::firstOrCreate([
    		'count'						=> $request->jumlah,
    		'inventory_sub_list_id'		=> $sub_list->id,
    		'comment'					=> $request->keterangan,
    		'serial_number'				=> $request->SN,
    		'location'					=> $request->tempat,
    		'status_inventory'			=> 1,
            'uuid'                      => time().$this->faker->uuid,
            'created_by'                => Auth::id(),
    		'updated_by'				=> Auth::id()
    	]);

        $request->session()->flash('alert-success', 'Inventory berhasil di request !');
        //$this->send($inventory);
    	return redirect($this->redirectTo);
    	
    }


    public function approve_by_head(Request $request) {
        Inventory_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    => Auth::id(),
            "status_inventory"  => 2
        ]);
        return redirect($this->redirectTo);
    }

    public function approve_by_admin(Request $request) {
        Inventory_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    => Auth::id(),
            "status_inventory"  => 3
        ]);
        return redirect($this->redirectTo);
    }

    public function send($data){
        dd($data);
        $error = false;
        switch($inventory_data->status_inventory) {
            case 1 :
                $user    = Users_Role::GetInventoryDecisionMaker(2)->first();
                $subject = "Pendaftaran Inventory";
                $desc    = "baru saja mendaftarkan inventory";
                break;
            case 2 :
                $user    = Users_Role::GetInventoryDecisionMaker(3)->first();
                $subject = "Setuju Inventory";
                $desc    = "membutuhkan approval inventory";
                break;
            default : 
                $error = true;
                break;
        }

        if(!$error) {
            $data = array(
                "subject"   => $subject,
                "head"      => $user->username,
                "staff"     => Users::find($inventory_data->updated_by)->name,
                "desc"      => $desc,
                "nama_barang" => Inventory_Sub_List::find($inventory_data->inventory_sub_list_id)->inventory_sub_list_name,
                "kategori"    => Inventory_List::find(Inventory_Sub_List::find($inventory_data->inventory_sub_list_id)->inventory_list_id)->inventory_name,
                "count"       => $inventory_data->count,
                "uuid"      => $inventory_data->uuid    
            );
            $user->notify(new Inventory_Notification($data));
        } 
        
    }
}
