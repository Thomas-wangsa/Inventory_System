<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users_Role;
use App\Http\Models\Status_Inventory;
use App\Http\Models\New_Inventory_Role;
use App\Http\Models\New_Inventory_Data;
use App\Http\Models\New_Inventory_Sub_Data;

use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

 
class NewInventoryController extends Controller
{   
    protected $redirectTo = 'new_inventory';
    protected $admin = 1;
    protected $new_inventory_divisi_id = 6;
    protected $faker;
    protected $is_super_admin = false;

     public function __construct() {
        $this->faker    = Faker::create();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

        $user_divisi = \Request::get('user_divisi');
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($this->new_inventory_divisi_id,$user_divisi)
            ) 
        {
            $allow = true;
            if(in_array($this->admin,$user_divisi)) {
                $this->is_super_admin = true;
            }
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to inventory page');
            return redirect('home');
        }


        $base_inventory_data = New_Inventory_Data::leftjoin('group1','group1.id','=','new_inventory_data.group1')
                            ->leftjoin('group2','group2.id','=','new_inventory_data.group2')
                            ->leftjoin('group3','group3.id','=','new_inventory_data.group3')
                            ->leftjoin('group4','group4.id','=','new_inventory_data.group4')
                            ->leftjoin('inventory_list','inventory_list.id','=','new_inventory_data.inventory_list_id')
                            ->leftjoin('status_inventory','status_inventory.id','=','new_inventory_data.status');

        if(!$this->is_super_admin) {
            $role_specific_users = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi','=',$this->new_inventory_divisi_id)
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->get();

            if(count($role_specific_users) > 0) {
                foreach($role_specific_users as $key_role=>$val_role) {
                    // echo $val_role;
                    $base_inventory_data->Orwhere(function ($query) use ($val_role) {
                        $query->where('group1', '=', $val_role->group1)
                        ->where('group2', '=', $val_role->group2)
                        ->where('group3', '=', $val_role->group3)
                        ->where('group4', '=', $val_role->group4)
                        ->where('inventory_list_id', '=', $val_role->inventory_list_id);
                    });
                }
            } else {
                echo "ERROR in logic";die;
            }
        }
        //dd($base_inventory_data->get());
        if($request->search == "on") {
            if($request->search_nama != null) {
                $base_inventory_data->where('new_inventory_data.inventory_name','like','%'.$request->search_nama."%");
            }

            if($request->search_filter != null) {
                $base_inventory_data->where('new_inventory_data.status',$request->search_filter);            
            } 

            if($request->search_uuid != null) {
                $base_inventory_data->where('new_inventory_data.uuid',$request->search_uuid);
            } 
        }
        
        $base_inventory_data->select('new_inventory_data.*'
                        ,'group1.group1_name'
                        ,'group2.group2_name'
                        ,'group3.group3_name'
                        ,'group4.group4_name'
                        ,'inventory_list.inventory_name AS inventory_list_name'
                        ,'status_inventory.name AS status_inventory_name'
                        ,'status_inventory.color AS status_inventory_color'
                        );
        $new_inventory_data = $base_inventory_data->paginate(2);

        
        $list_new_inventory_role = Users_Role::GetInventoryRoleById(Auth::user()->id)->get();
        $data = [
            'list_new_inventory_role'   => $list_new_inventory_role,
            'status_inventory'          => Status_Inventory::all(),
            'new_inventory_data'        => $new_inventory_data
        ];
        //dd($data);
        return view('new_inventory/index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $request->validate([
            'uuid'=>'required',
        ]);

        $new_inventory_data = New_Inventory_Data::leftjoin('group1','group1.id','=','new_inventory_data.group1')
                        ->leftjoin('group2','group2.id','=','new_inventory_data.group2')
                        ->leftjoin('group3','group3.id','=','new_inventory_data.group3')
                        ->leftjoin('group4','group4.id','=','new_inventory_data.group4')
                        ->leftjoin('inventory_list','inventory_list.id','=','new_inventory_data.inventory_list_id')
                        ->leftjoin('inventory_level','inventory_level.id','=','new_inventory_data.inventory_level_id')
                        ->where('uuid','=',$request->uuid)
                        ->select('new_inventory_data.*'
                            ,DB::raw('CONCAT(
                            inventory_level.inventory_level_name," ",inventory_list.inventory_name
                            ," ("
                            ,IFNULL(group1.group1_name,"undefined"),", "
                            ,IFNULL(group2.group2_name,"undefined"),", "
                            ,IFNULL(group3.group3_name,"undefined"),", "
                            ,IFNULL(group4.group4_name,"undefined")
                            ,")"
                            ) AS grouping_detail')
                        )
                        ->first();

        if($new_inventory_data == null or count($new_inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'Data not found!');
            return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
        }

        $new_inventory_sub_data = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$new_inventory_data->id)->get();

        if($new_inventory_sub_data == null or count($new_inventory_sub_data) < 1) {
            $full_array = [];
            for($i=1;$i<=$new_inventory_data->qty;$i++) {
                $array = array(
                    'new_inventory_data_id' => $new_inventory_data->id,
                    'sub_data_status'       => "good",
                    "sub_data_uuid"         => $new_inventory_data->id.$i.$this->faker->uuid,
                    "created_by"            => Auth::user()->id,
                    "updated_by"            => Auth::user()->id,
                    "created_at"            => date("Y-m-d H:i:s"),
                    "updated_at"            => date("Y-m-d H:i:s"), 
                );
                array_push($full_array,$array);
            }
            New_Inventory_Sub_Data::insert($full_array);
            $new_inventory_sub_data = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$new_inventory_data->id)->get();
        }


        $data = [
            'new_inventory_data' => $new_inventory_data,
            'new_inventory_sub_data' => $new_inventory_sub_data
        ];

        return view('new_inventory/create',compact('data'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function set_draft_data(Request $request) {
        $request->validate([
            'new_inventory_role_id'=>'required',
            'inventory_name'=>'required'
        ]);

        $uuid = time().$this->faker->uuid;
        $status_inventory = 1;


        $grouping = New_Inventory_Role::find($request->new_inventory_role_id);

        $data = array(
            'inventory_name'    => $request->inventory_name,

            'group1'            => $grouping->group1,
            'group2'            => $grouping->group2,
            'group3'            => $grouping->group3,
            'group4'            => $grouping->group4,
            'inventory_list_id' => $grouping->inventory_list_id,
            'inventory_level_id' => $grouping->inventory_level_id,


            'status'=>$status_inventory,
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


        DB::table('new_inventory_data')->insert($data);
        
        //$this->notify($status_inventory,$uuid);
        $request->session()->flash('alert-success', 'new inventory already registered');
        return redirect($this->redirectTo."?search=on&search_uuid=".$data['uuid']);

    }

    public function get_inventory_detail_ajax(Request $request) {
        $response['status'] = false;

        $data = New_Inventory_Data::join('status_inventory',
                'status_inventory.id','=','new_inventory_data.status')
                ->leftjoin('users AS c_users','c_users.id','=','new_inventory_data.created_by')
                ->leftjoin('users AS u_users','u_users.id','=','new_inventory_data.updated_by')
                ->leftjoin('group1','group1.id','=','new_inventory_data.group1')
                ->leftjoin('group2','group2.id','=','new_inventory_data.group2')
                ->leftjoin('group3','group3.id','=','new_inventory_data.group3')
                ->leftjoin('group4','group4.id','=','new_inventory_data.group4')
                ->leftjoin('inventory_list','inventory_list.id','=','new_inventory_data.inventory_list_id')
                ->leftjoin('inventory_level','inventory_level.id','=','new_inventory_data.inventory_level_id')
                ->leftjoin('map_location','map_location.inventory_data_id','=','new_inventory_data.id')
                ->leftjoin('map','map.id','=','map_location.map_id')
                ->where('uuid',$request->uuid)
                ->select(
                    'new_inventory_data.*',
                    'group1.group1_name',
                    'group2.group2_name',
                    'group3.group3_name',
                    'group4.group4_name',
                    'inventory_list.inventory_name AS inventory_list_name',
                    'inventory_level.inventory_level_name AS inventory_level_name',
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

    public function get_inventory_detail_ajax_by_uuid(Request $request) {
        $response = array();
        $response['status'] = false;

        $data = New_Inventory_Data::leftjoin('inventory_list',
                'inventory_list.id','=','new_inventory_data.inventory_list_id')
                ->leftjoin('group1','group1.id','=','new_inventory_data.group1')
                ->leftjoin('group2','group2.id','=','new_inventory_data.group2')
                ->leftjoin('group3','group3.id','=','new_inventory_data.group3')
                ->leftjoin('group4','group4.id','=','new_inventory_data.group4')
                ->leftjoin('inventory_level','inventory_level.id','=','new_inventory_data.inventory_level_id')
                ->where('uuid',$request->uuid)
                ->select('new_inventory_data.*'
                    ,DB::raw('CONCAT(
                    inventory_level.inventory_level_name," ",inventory_list.inventory_name
                    ," ("
                    ,IFNULL(group1.group1_name,"undefined"),", "
                    ,IFNULL(group2.group2_name,"undefined"),", "
                    ,IFNULL(group3.group3_name,"undefined"),", "
                    ,IFNULL(group4.group4_name,"undefined")
                    ,")"
                    ) AS grouping_detail')
                )
                ->first();
        if(count($data) < 1) {
            $response['message'] = "Inventory data ID not found";
            return json_encode($response);
        }

        $response['status'] = true;
        $response['data'] = $data; 
        return json_encode($response);
    }

    public function new_inventory_update_data(Request $request) {
        $request->validate([
                'uuid' => 'required',
        ]);

        $inventory_data = New_Inventory_Data::where('status',1)
                ->where('uuid',$request->uuid)
                ->first();

        if(count($inventory_data) < 1 ) {
            $request->session()->flash('alert-danger', 'credentials not found');
            return redirect($this->redirectTo);
        }

        $inventory_data->inventory_name = $request->inventory_name;
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


    public function new_inventory_sub_data_update_ajax(Request $request) {
        $response = array();
        $response['status'] = false;

        $data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                ->first();
        if(count($data) < 1) {
            $response['message'] = "Sub Inventory data ID not found";
            return json_encode($response);
        }

        $data->comment = $request->sub_data_comment;
        $data->sub_data_status  = $request->sub_data_status;
        $data->updated_by   = Auth::user()->id;
        $data->save();

        $response['status'] = true;
        $response['data'] = $data; 
        return json_encode($response);
    }

    public function new_inventory_data_approve_ajax(Request $request) {
        $response = array();
        $response['status'] = false;

        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->uuid)
                ->first();
        if(count($new_inventory_data) < 1) {
            $response['message'] = "Inventory data ID not found";
            return json_encode($response);
        }

        $new_inventory_sub_data = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$new_inventory_data->id)
                ->get();

        if(count($new_inventory_sub_data) < 1) {
            $response['message'] = "Sub Inventory data ID not found";
            return json_encode($response);
        }

        if($new_inventory_data->qty != count($new_inventory_sub_data)) {
            $response['message'] = "total qty and sub qty is not equal!";
            return json_encode($response);
        } 
        
        if($new_inventory_data->inventory_level_id == 1) {
            $new_inventory_data->status = 2;
        } else if($new_inventory_data->inventory_level_id == 2) {
            $new_inventory_data->status = 3;
        } else {
            $response['message'] = "out of scope!";
            return json_encode($response);
        }

        $new_inventory_data->updated_by = Auth::user()->id;
        $new_inventory_data->save();
        $request->session()->flash('alert-success', 'Update Success');

        $response['status'] = true;
        $response['data'] = $new_inventory_data; 
        return json_encode($response);
    }
}
