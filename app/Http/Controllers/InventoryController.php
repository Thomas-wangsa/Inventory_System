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
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Divisi;
use App\Notifications\Inventory_Notification;
use Illuminate\Support\Facades\Input;
use Excel;


use Faker\Factory as Faker;


class InventoryController extends Controller
{	
	protected $redirectTo = 'inventory';
    protected $credentials;
    protected $faker;
    protected $admin = 1;

    public function __construct() {
        $this->faker    = Faker::create();
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


        // dd($data);
    	return view('inventory/index',compact('data'));
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

        $file_name = $request->excel_file->getClientOriginalName();
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
                dd($data);
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                if(!empty($insert)){
                    DB::table('items')->insert($insert);
                    dd('Insert Record successfully.');
                }
            }
        }
        return back();
        
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
        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            if($data->status_inventory == 1 || $data->status_inventory == 2) {

                switch ($data->status_inventory) {
                    case 1:
                        $data->status_inventory = 2;
                        break;
                    case 2:
                        $data->status_inventory = 3;
                        $data->status_data      = 3;
                        break;
                    default:
                        # code...
                        break;
                }
                
                $data->updated_by   = $this->credentials->id;
                $data->save();
                //$this->send($data);
            } else {
                return redirect($this->redirectTo);
            }
            
        }
        return view('inventory/approval');
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
        $data = Inventory_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();

        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            if($data->status_inventory == 1 || $data->status_inventory == 2 ) {

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
                $data->updated_by   = $this->credentials->id;
                $data->comment      = $request->desc;
                $data->save();
            } else {
                return redirect($this->redirectTo);
            }
            
        }
        return redirect($this->redirectTo);
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
            'uuid'                      => $this->faker->uuid,
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

    public function send($inventory_data){
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
