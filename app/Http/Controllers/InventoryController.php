<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Sub_List;
use App\Http\Models\Inventory_Data;
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

        $this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            return $next($request);
        });
        
    }


    public function index(Request $request) {
        $allow =array(1,3);

        if(!in_array($this->credentials->divisi, $allow)) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur inventory');
            return redirect('home');
        }

    	$data['inventory'] = Inventory_List::all();
    	$data['inventory_data'] = Inventory_Data::GetDetailInventory()->paginate(5);
        $data['credentials']    = $this->credentials;

        if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
        } else if ($this->credentials->divisi == 2) {
            switch ($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Akses_Data::where('status_akses',1)->count();
                    break;
                case 3:
                    $data['notify']         = Akses_Data::where('status_akses',2)->count();
                    break;
                case 4:
                    $data['notify']         = Akses_Data::where('status_akses',3)->count();
                    break;
                case 5:
                    $data['notify']         = Akses_Data::where('status_akses',4)->count();
                    break;
                case 6:
                    $data['notify']         = Akses_Data::where('status_akses',5)->count();
                    break;
                
                default:
                    # code...
                    break;
            }

        } else if($this->credentials->divisi == 3) {
            switch($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                    break;
            }
            
        }
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
                $this->send($data);
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

        if($this->credentials->divisi == 1 
            || ($this->credentials->divisi == 3 
                && $this->credentials->id_jabatan == 1 )
        ) {
            $allow = true;
        } else {
            $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur menambahkan inventory');
            return redirect($this->redirectTo);
        }

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
        $this->send($inventory);
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
