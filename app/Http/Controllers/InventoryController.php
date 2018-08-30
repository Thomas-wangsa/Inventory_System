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


use Faker\Factory as Faker;


class InventoryController extends Controller
{	
	protected $redirectTo = 'inventory';
    protected $credentials;
    protected $faker;

    public function __construct() {
        $this->faker    = Faker::create();

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
        
    	return view('inventory/index');
    }


    public function map_location() {
        $data['credentials']    = $this->credentials;
        return view('inventory/map',compact('data'));
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
