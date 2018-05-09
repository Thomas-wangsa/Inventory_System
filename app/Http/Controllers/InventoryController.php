<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Sub_List;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Users;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Divisi;

use Faker\Factory as Faker;


class InventoryController extends Controller
{	
	protected $redirectTo = 'inventory';
    protected $credentials;
    protected $faker;

    public function __construct() {
        $this->faker    = Faker::create();

        $this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            return $next($request);
        });
        
    }


    public function index() {
    	$data['inventory'] = Inventory_List::all();
    	$data['inventory_data'] = Inventory_Data::GetDetailInventory()->get();
        $data['credentials']    = $this->credentials;
    	return view('inventory/index',compact('data'));
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
    		'updated_by'				=> Auth::id()
    	]);

        $request->session()->flash('alert-success', 'Inventory berhasil di request !');
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
}
