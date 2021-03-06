<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users_Role;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Inventory_List;

use App\Http\Models\New_Inventory_Role;
use App\Http\Models\New_Inventory_Data;
use App\Http\Models\New_Inventory_Data_History;
use App\Http\Models\New_Inventory_Sub_Data;
use App\Http\Models\New_Map;
use App\Http\Models\New_Map_Images;

use App\Http\Models\Group1;
use App\Http\Models\Group2;
use App\Http\Models\Group3;
use App\Http\Models\Group4;

use Illuminate\Support\Facades\Auth;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Notification AS custom_notification;

use Illuminate\Support\Facades\Input;
use Excel;

 
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

        $role_specific_head = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi','=',$this->new_inventory_divisi_id)
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('new_inventory_role.user_id','=',Auth::user()->id)
                                ->where('inventory_level_id','=',2)->get();
        if(!$this->is_super_admin) {
            $role_specific_users = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi','=',$this->new_inventory_divisi_id)
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('new_inventory_role.user_id','=',Auth::user()->id)
                                ->get();

            


            if(count($role_specific_users) > 0) {

                $base_inventory_data->where(function ($query) use ($role_specific_users)  {
                    foreach($role_specific_users as $key_role=>$val_role) {
                        $query->Orwhere(function ($sub_query) use ($val_role) {
                        $sub_query->where('group1', '=', $val_role->group1)
                        ->where('group2', '=', $val_role->group2)
                        ->where('group3', '=', $val_role->group3)
                        ->where('group4', '=', $val_role->group4)
                        ->where('inventory_list_id', '=', $val_role->inventory_list_id);
                        });
                    }
                });

            } else {
                echo "ERROR in logic";die;
            }
        }



        if($request->search == "on") {
            if($request->search_nama != null) {
                //$base_inventory_data->where('new_inventory_data.inventory_name','=',$request->search_nama);
                $searchName = $request->search_nama;
                $base_inventory_data->where(function ($newquery) use ($searchName)  {
                    $newquery->Orwhere('new_inventory_data.inventory_name','LIKE',"%".$searchName."%")
                    ->Orwhere('new_inventory_data.kategori','=',$searchName)
                    ->Orwhere('new_inventory_data.kode_gambar','=',$searchName)
                    ->Orwhere('new_inventory_data.dvr','=',$searchName)
                    ->Orwhere('new_inventory_data.lokasi_site','=',$searchName)
                    ->Orwhere('new_inventory_data.kode_lokasi','=',$searchName)
                    ->Orwhere('new_inventory_data.jenis_barang','=',$searchName)
                    ->Orwhere('new_inventory_data.merk','=',$searchName)
                    ->Orwhere('new_inventory_data.tipe','=',$searchName)
                    ->Orwhere('new_inventory_data.model','=',$searchName)
                    ->Orwhere('new_inventory_data.serial_number','=',$searchName)

                    ->Orwhere('new_inventory_data.psu_adaptor','=',$searchName)
                    ->Orwhere('new_inventory_data.tahun_pembuatan','=',$searchName)
                    ->Orwhere('new_inventory_data.tahun_pengadaan','=',$searchName)
                    ->Orwhere('new_inventory_data.kondisi','=',$searchName)
                    ->Orwhere('new_inventory_data.deskripsi','=',$searchName)
                    ->Orwhere('new_inventory_data.asuransi','=',$searchName)
                    ->Orwhere('new_inventory_data.lampiran','=',$searchName)
                    ->Orwhere('new_inventory_data.tanggal_retired','=',$searchName)
                    ->Orwhere('new_inventory_data.po','=',$searchName)
                    ->Orwhere('new_inventory_data.keterangan','=',$searchName);
                });

            }

            if($request->search_kota != null) {
                $base_inventory_data->where('new_inventory_data.group1','=',$request->search_kota);            
            }

            if($request->search_gedung != null) {
                $base_inventory_data->where('new_inventory_data.group2','=',$request->search_gedung);            
            }

            if($request->search_divisi != null) {
                $base_inventory_data->where('new_inventory_data.group3','=',$request->search_divisi);            
            }

            if($request->search_sub_divisi != null) {
                $base_inventory_data->where('new_inventory_data.group4','=',$request->search_sub_divisi);            
            }


            if($request->search_category != null) {
                $base_inventory_data->where('new_inventory_data.inventory_list_id','=',$request->search_category);            
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
                        )
                        ->orderBy('new_inventory_data.inventory_name', 'ASC');
        $new_inventory_data = $base_inventory_data->paginate(5);
        
        
        $conditional_head = array();
        foreach($new_inventory_data as $key=>$val) {
            $conditional_head[$key] = false;

            if(count($role_specific_head) > 0) {
                foreach($role_specific_head as $key_head=>$val_head) {

                    if (
                        $val->group1 == $val_head->group1
                        && $val->group2 == $val_head->group2
                        && $val->group3 == $val_head->group3
                        && $val->group4 == $val_head->group4
                        && $val->inventory_list_id == $val_head->inventory_list_id
                    ) {
                        $conditional_head[$key] = true;
                    }
                }
                // dd($new_inventory_data);
                // dd($role_specific_head);
            }

            // pake foreach ya ambil semua
            // if(in_array($val->inventory_list_id,$head_role_inventory)) {
            //     $conditional_head[$key] = true;
            // }
        }

        $list_new_inventory_role = Users_Role::GetInventoryRoleById(Auth::user()->id)->get();
        $data = [
            'list_new_inventory_role'   => $list_new_inventory_role,
            'status_inventory'          => Status_Inventory::all(),
            'new_inventory_data'        => $new_inventory_data,
            'conditional_head'          => $conditional_head,
            'category'                  => Inventory_List::all(),
            'group1'                    => Group1::all(),
            'group2'                    => Group2::all(),
            'group3'                    => Group3::all(),
            'group4'                    => Group4::all(),
            'role_specific_head'                 => $role_specific_head
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

        $map_data = New_Map::where('new_inventory_data_id','=',$new_inventory_data->id)->get();
        $images_data = New_Map_Images::where('new_inventory_data_id','=',$new_inventory_data->id)->get();

        $data = [
            'new_inventory_data' => $new_inventory_data,
            'new_inventory_sub_data' => $new_inventory_sub_data,
            'token_main_uuid'            => $request->uuid,
            'map_data'              => $map_data,
            'images_data'           => $images_data
        ];
        //dd($data);
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
        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->uuid)
                ->first();
        if(count($new_inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'Reject failed, Inventory data not found');
            return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
        }

        $new_inventory_data->status  = $request->reject_options;
        $new_inventory_data->comment = $request->note; 
        
        if($new_inventory_data->save()) {
            if($request->reject_options == "1") {
                $request->session()->flash('alert-success', 'inventory has been rolled back');
            } else if ($request->reject_options == "2"){
                $request->session()->flash('alert-success', 'inventory has been rejected');
            } else {
                $request->session()->flash('alert-success', 'inventory has been updated');
            }

            $notify = new custom_notification;
            $notify_status = $notify->set_notify(2,$new_inventory_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            
        } else {
           $request->session()->flash('alert-danger', 'Reject failed, Data is not updated'); 
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$uuid)
    {
        $inventory_data = New_Inventory_Data::where('uuid',$uuid)->first();

        if(count($inventory_data) < 1 ) {
            $request->session()->flash('alert-danger', 'credentials not found');
            return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);
        }

        $new_inventory_sub_data = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$inventory_data->id)->get();

        $data = [
            'sub_data' => $new_inventory_sub_data
        ];

        return view('new_inventory/show',compact('data'));
        //dd($uuid);
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
            'inventory_name'    => strtolower(trim($request->inventory_name)),

            'group1'            => $grouping->group1,
            'group2'            => $grouping->group2,
            'group3'            => $grouping->group3,
            'group4'            => $grouping->group4,
            'inventory_list_id' => $grouping->inventory_list_id,
            'inventory_level_id' => $grouping->inventory_level_id,

            'status_data'=>1,
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


        try {
            DB::table('new_inventory_data')->insert($data);
            $new_inventory_data = New_Inventory_Data::where('uuid','=',$data['uuid'])->first();
            $request->session()->flash('alert-success', 'new inventory already registered');
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(2,$new_inventory_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
        } catch(Exception $e) {
            $request->session()->flash('alert-danger', $e->getMessage());
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$data['uuid']);
        
        
        //$this->notify($status_inventory,$uuid);
        

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


    public function get_inventory_history_detail_ajax(Request $request) {
        $response['status'] = false;

        $data = New_Inventory_Data_History::join('status_inventory',
                'status_inventory.id','=','new_inventory_data_history.status')
                ->leftjoin('users AS c_users','c_users.id','=','new_inventory_data_history.created_by')
                ->leftjoin('users AS u_users','u_users.id','=','new_inventory_data_history.updated_by')
                ->leftjoin('group1','group1.id','=','new_inventory_data_history.group1')
                ->leftjoin('group2','group2.id','=','new_inventory_data_history.group2')
                ->leftjoin('group3','group3.id','=','new_inventory_data_history.group3')
                ->leftjoin('group4','group4.id','=','new_inventory_data_history.group4')
                ->leftjoin('inventory_list','inventory_list.id','=','new_inventory_data_history.inventory_list_id')
                ->leftjoin('inventory_level','inventory_level.id','=','new_inventory_data_history.inventory_level_id')
                ->leftjoin('map_location','map_location.inventory_data_id','=','new_inventory_data_history.id')
                ->leftjoin('map','map.id','=','map_location.map_id')
                ->where('new_inventory_data_history.id',$request->uuid)
                ->select(
                    'new_inventory_data_history.*',
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
            $response['message'] = "Inventory Data History is not found!";
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
            $response['message'] = "Please set the sub inventory data first";
            return json_encode($response);
        }

        if($new_inventory_data->qty != count($new_inventory_sub_data)) {
            $response['message'] = "total qty and sub qty is not equal!";
            return json_encode($response);
        } 
        
        if($new_inventory_data->status == 1) {

            $user_divisi = \Request::get('user_divisi');
            if(in_array($this->admin,$user_divisi)) {
                $new_inventory_data->status = 3;
            } else {


                $inventory_role = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi','=',$this->new_inventory_divisi_id)
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('new_inventory_role.user_id','=',Auth::user()->id)
                                ->where('new_inventory_role.group1',$new_inventory_data->group1)
                                ->where('new_inventory_role.group2',$new_inventory_data->group2)
                                ->where('new_inventory_role.group3',$new_inventory_data->group3)
                                ->where('new_inventory_role.group4',$new_inventory_data->group4)
                                ->where('new_inventory_role.inventory_list_id',$new_inventory_data->inventory_list_id)
                                ->pluck('new_inventory_role.inventory_level_id')->toArray();


                if (count($inventory_role) < 1) {
                    $response['message'] = "inventory role is not found!";
                    return json_encode($response);
                }


                if (in_array(2,$inventory_role)) {
                    $new_inventory_data->status = 3;
                } else if (in_array(1,$inventory_role)) {
                    $new_inventory_data->status = 2;
                } else {
                    $response['message'] = "out of level scope!";
                    return json_encode($response);
                }

                // if($new_inventory_data->inventory_level_id == 1) {
                //     $new_inventory_data->status = 2;
                // } else if($new_inventory_data->inventory_level_id == 2) {
                //     $new_inventory_data->status = 3;
                // } else {
                //     $response['message'] = "out of level scope!";
                //     return json_encode($response);
                // }
            }
        } else if ($new_inventory_data->status == 2) {
            $new_inventory_data->status = 3;
        } else {
            $response['message'] = "out of scope!";
            return json_encode($response);
        }
        

        $new_inventory_data->updated_by = Auth::user()->id;
        $new_inventory_data->save();

        $notify = new custom_notification;
        $notify_status = $notify->set_notify(2,$new_inventory_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
        $request->session()->flash('alert-success', 'Update Success');

        $response['status'] = true;
        $response['data'] = $new_inventory_data; 
        return json_encode($response);
    }


    public function new_inventory_data_update_ajax(Request $request) {
        $response = array();
        $response['status'] = false;

        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->uuid)
                ->first();
        if(count($new_inventory_data) < 1) {
            $response['message'] = "Inventory data ID not found";
            return json_encode($response);
        }

        if($new_inventory_data->status != 3) {
            $response['message'] = "Inventory data is not active";
            return json_encode($response);
        }

        $new_inventory_data->status_data = 2;
        $new_inventory_data->status = 1;
        $new_inventory_data->updated_by = Auth::user()->id;
        $new_inventory_data->save();

        $notify = new custom_notification;
        $notify_status = $notify->set_notify(2,$new_inventory_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
        $request->session()->flash('alert-success', 'Inventory status changed');


        $response['status'] = true;
        $response['data'] = $new_inventory_data;
        return json_encode($response);
    }

    public function save_new_inventory_sub_data(Request $request) {
        $request->validate([
            'token_main_uuid'=>'required',
        ]);

        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->token_main_uuid)
                        ->first();

        if($new_inventory_data == null or count($new_inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'Data not found!');
            return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);
        }

        $new_inventory_sub_data = New_Inventory_Sub_Data::where('new_inventory_data_id','=',$new_inventory_data->id)->get();

        $data = array(
            'new_inventory_data_id' => $new_inventory_data->id,
            'sub_data_status'       => $request->sub_data_status,
            'comment'               => $request->sub_data_additional,
            "sub_data_uuid"         => $new_inventory_data->id.$this->faker->uuid,
            "created_by"            => Auth::user()->id,
            "updated_by"            => Auth::user()->id,
            "created_at"            => date("Y-m-d H:i:s"),
            "updated_at"            => date("Y-m-d H:i:s"), 
        );

    
        try {
            DB::table('new_inventory_sub_data')->insert($data);
            $request->session()->flash('alert-success', 'new sub data inventory already registered');
        } catch(Exception $e) {
            $request->session()->flash('alert-danger', $e->getMessage());
        }
        return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);
    }

    public function delete_new_inventory_sub_data(Request $request) {
        $response = array();
        $response['status'] = false;

        $data = New_Inventory_Sub_Data::where('sub_data_uuid','=',$request->sub_data_uuid)
                ->first()->delete();;
    

        $response['status'] = true;
        return json_encode($response);
    }

    public function new_inventory_add_new_map(Request $request) {
        $request->validate([
            'map_images' => 'required|image|mimes:jpeg,png,jpg|max:550',
            'token_main_uuid' => 'required',
            'map_name' => 'required|max:50',
        ]);


        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->token_main_uuid)
                        ->first();

        if($new_inventory_data == null or count($new_inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'Data not found!');
            return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);
        }


        $map = new New_Map;
        if ($request->hasFile('map_images')) {
            $image = $request->file('map_images');
            $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
            $path = "/images/new_map/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $map->map_images = $path.$file_name;
        }

        $map->new_inventory_data_id = $new_inventory_data->id;
        $map->map_name          = $request->map_name;
        $map->map_notes         = $request->map_notes;
        $map->uuid              = $new_inventory_data->id.$this->faker->uuid;
        $map->created_by        = Auth::user()->id;
        $map->updated_by        = Auth::user()->id;
        $check = $map->save();

        if($check) {
            $request->session()->flash('alert-success', 'new map has been registerred');
        } else {
            $request->session()->flash('alert-danger', 'failed to register new map');
        }

        return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);
    }

    public function new_inventory_add_new_images(Request $request) {
        $request->validate([
            'images' => 'required|image|mimes:jpeg,png,jpg|max:550',
            'token_main_uuid' => 'required',
            'images_name' => 'required|max:50',
        ]);

        $new_inventory_data = New_Inventory_Data::where('uuid','=',$request->token_main_uuid)
                        ->first();

        if($new_inventory_data == null or count($new_inventory_data) < 1) {
            $request->session()->flash('alert-danger', 'Data Inventory not found!');
            return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);
        }

        $images = new New_Map_Images;
        if ($request->hasFile('images')) {
            $image = $request->file('images');
            $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
            $path = "/images/new_map_images/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $images->images = $path.$file_name;
        }

        $images->new_inventory_data_id = $new_inventory_data->id;
        $images->images_name          = $request->images_name;
        $images->images_notes         = $request->images_notes;
        $images->uuid              = $new_inventory_data->id.$this->faker->uuid;
        $images->created_by        = Auth::user()->id;
        $images->updated_by        = Auth::user()->id;
        $check = $images->save();

        if($check) {
            $request->session()->flash('alert-success', 'new images has been registerred');
        } else {
            $request->session()->flash('alert-danger', 'failed to register new images');
        }

        return redirect($this->redirectTo."/create?uuid=".$request->token_main_uuid);

    }

    function new_inventory_checking_data(Request $request) {
        $response = array();
        $response['status'] = false;

        if($request->new_inventory_role_id == null) {
            $response['message'] = "Inventory credentials is required in server side";
            return json_encode($response);
        }

        if($request->inventory_name == null) {
            $response['message'] = "Inventory name is required in server side";
            return json_encode($response);
        }        

        $grouping = New_Inventory_Role::find($request->new_inventory_role_id);

        if($grouping == null || count($grouping) < 1) {
            $response['message'] = "Grouping Data is not found";
            return json_encode($response);
        }

        $inventory_name = strtolower(trim($request->inventory_name));

        $check_exist = New_Inventory_Data::where('inventory_name','=',$inventory_name)
                    ->where('group1','=',$grouping->group1)
                    ->where('group2','=',$grouping->group2)
                    ->where('group3','=',$grouping->group3)
                    ->where('group4','=',$grouping->group4)
                    ->where('inventory_list_id','=',$grouping->inventory_list_id)
                    ->get();

        if(count($check_exist) > 0) {
            $response['message'] = "Inventory name is exist in this grouping";
            return json_encode($response);   
        }


        $response['status'] = true;
        return json_encode($response);
    }


    function new_upload_excel(Request $request) {
        $allow = false;
        $request->validate([
            'excel_file' => 'required'
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
            return redirect($this->redirectTo);
        }


        if($request->excel_file){
            $path = Input::file('excel_file')->getRealPath();
            $data = Excel::selectSheets($request->sheet_name)->load($path, function($reader) {
                $reader->noHeading = true;
                })->get();
            //dd($data);
            // $data = Excel::load($path, function($reader) {
            //     $reader->noHeading = true;
            // })->get();
            //dd($data);
            if(count($data) <= 3) {
                $request->session()->flash('alert-danger', 'rows not found! for : '.$request->sheet_name." : ".count($data). " rows");
                return redirect($this->redirectTo);
            }

            if(count($data) > 2004) {
                $request->session()->flash('alert-danger', 'Limit rows maximum (2000 rows) issue!');
                return redirect($this->redirectTo);
            }

            $rollback = array(
                'status'    => False,
                'msg'       => ''
            );

            try {
                $file_name = $request->excel_file->getClientOriginalName();
                $full_new_inventory_data = array();
                //dd($data);
                foreach ($data as $key => $value) {
                    if($key < 4) {continue;}

                    $value_kota                 = trim($value['1']);
                    $value_gedung               = trim($value['2']);
                    $value_divisi_indosat       = trim($value['3']);
                    $value_sub_divisi_indosat   = trim($value['4']);
                    $value_inventory_category   = trim($value['5']);
                    $value_inventory_name       = trim($value['6']);


                    if($value_kota == "-" || $value_kota == "") {
                        $value_kota = null;
                    }

                    if($value_kota == null) {
                        $rollback['status'] = True;
                        $rollback['msg']    = "kota value is empty in rows : ". ($key + 2);
                        break;
                    }


                    if($value_gedung == "-" || $value_gedung == "") {
                        $value_gedung = null;
                    }

                    if($value_gedung == null) {
                        $rollback['status'] = True;
                        $rollback['msg']    = "gedung value is empty in rows : ". ($key + 2);
                        break;
                    }


                    if($value_divisi_indosat == "-" || $value_divisi_indosat == "") {
                        $value_divisi_indosat = null;
                    }

                    if($value_divisi_indosat == null) {
                        $rollback['status'] = True;
                        $rollback['msg']    = "divisi indosat value is empty in rows : ". ($key + 2);
                        break;
                    }

                    // for sub divisi
                    if($value_sub_divisi_indosat == "-" || $value_sub_divisi_indosat == "") {
                        $value_sub_divisi_indosat = null;
                    }
                    // for sub divisi


                    if($value_inventory_category == "-" || $value_inventory_category == "") {
                        $value_inventory_category = null;
                    }

                    if($value_inventory_category == null) {
                        $rollback['status'] = True;
                        $rollback['msg']    = "inventory category value is empty in rows : ". ($key + 2);
                        break;
                    }


                    if($value_inventory_name == "-" || $value_inventory_name == "") {
                        $value_inventory_name = null;
                    }

                    if($value_inventory_name == null) {
                        $rollback['status'] = True;
                        $rollback['msg']    = "inventory name value is empty in rows : ". ($key + 2);
                        break;
                    }



                    $additional_note_for_upload = "upload filename : " . $file_name." : ". date('Y-m-d H:i:s');
                    $group1 = Group1::where('group1_name','=',$value_kota)->first();
                    if($group1 == null || count($group1) < 1) {
                        $group1 = new Group1;
                        $group1->group1_name        = $value_kota;
                        $group1->group1_detail      = $additional_note_for_upload;
                        $group1->created_by         = Auth::user()->id;
                        $group1->updated_by         = Auth::user()->id;
                        $group1->save();
                    }

                    $group2 = Group2::where('group2_name','=',$value_gedung)->first();
                    if($group2 == null || count($group2) < 1) {
                        $group2 = new Group2;
                        $group2->group2_name        = $value_gedung;
                        $group2->group2_detail      = $additional_note_for_upload;
                        $group2->created_by         = Auth::user()->id;
                        $group2->updated_by         = Auth::user()->id;
                        $group2->save();
                    }

                    $group3 = Group3::where('group3_name','=',$value_divisi_indosat)->first();
                    if($group3 == null || count($group3) < 1) {
                        $group3 = new Group3;
                        $group3->group3_name        = $value_divisi_indosat;
                        $group3->group3_detail      = $additional_note_for_upload;
                        $group3->created_by         = Auth::user()->id;
                        $group3->updated_by         = Auth::user()->id;
                        $group3->save();
                    }


                    if($value_sub_divisi_indosat != null) {
                        $group4 = Group4::where('group4_name','=',$value_sub_divisi_indosat)->first();
                        if($group4 == null || count($group4) < 1) {
                            $group4 = new Group4;
                            $group4->group4_name        = $value_sub_divisi_indosat;
                            $group4->group4_detail      = $additional_note_for_upload;
                            $group4->created_by         = Auth::user()->id;
                            $group4->updated_by         = Auth::user()->id;
                            $group4->save();
                        }
                    } 

                    $inventory_list = Inventory_List::where('inventory_name','=',$value_inventory_category)->first();
                    if($inventory_list == null || count($inventory_list) < 1) {
                        $inventory_list = new Inventory_List;
                        $inventory_list->inventory_name        = $value_inventory_category;
                        $inventory_list->inventory_detail_name      = $additional_note_for_upload;
                        $inventory_list->created_by         = Auth::user()->id;
                        $inventory_list->updated_by         = Auth::user()->id;
                        $inventory_list->save();
                    }



                    $each_new_inventory_data = array(
                        'inventory_name'    => strtolower(trim($value_inventory_name)),
                        
                        'group1'            => $group1->id,
                        'group2'            => $group2->id,
                        'group3'            => $group3->id,
                        'group4'            => $value_sub_divisi_indosat != null ? $group4->id : null,
                        'inventory_list_id' => $inventory_list->id,
                        'inventory_level_id' => 1,

                        'status_data'=>1,
                        'status'=>1,
                        'created_by'=>Auth::user()->id,
                        'updated_by'=>Auth::user()->id,

                        'qty' => (int) $value['7'],
                        'file_name_upload' => $additional_note_for_upload,
                        'tanggal_update_data' => date('Y-m-d'),

                        'kategori'      => isset($value['8']) ? $value['8'] : "-",
                        'kode_gambar'   => isset($value['9']) ? $value['9'] : "-",
                        'dvr'           => isset($value['10']) ? $value['10'] : "-",
                        'lokasi_site'   => isset($value['11']) ? $value['11'] : "-",
                        'kode_lokasi'   => isset($value['12']) ? $value['12'] : "-",

                        'jenis_barang'  => isset($value['13']) ? $value['13'] : "-",
                        'merk'          => isset($value['14']) ? $value['14'] : "-",
                        'tipe'          => isset($value['15']) ? $value['15'] : "-",
                        'model'         => isset($value['16']) ? $value['16'] : "-",
                        'serial_number' => isset($value['17']) ? $value['17'] : "-",

                        'psu_adaptor'       => isset($value['18']) ? $value['18'] : "-",
                        'tahun_pembuatan'   => isset($value['19']) ? $value['19'] : "-",
                        'tahun_pengadaan'   => isset($value['20']) ? $value['20'] : "-",
                        'kondisi'           => isset($value['21']) ? $value['21'] : "-",
                        'deskripsi'         => isset($value['22']) ? $value['22'] : "-",

                        'asuransi'          => isset($value['23']) ? $value['23'] : "-",
                        'lampiran'          => isset($value['24']) ? $value['24'] : "-",
                        'tanggal_retired'   => isset($value['25']) ? $value['25'] : "-",
                        'po'                => isset($value['26']) ? $value['26'] : "-" ,
                        'keterangan'        => isset($value['27']) ? $value['27'] : "-",

                        'uuid'  => time().$this->faker->uuid,
                        'created_at'=> date('Y-m-d H:i:s'),
                        'updated_at'=> date('Y-m-d H:i:s'),
                        'sheet_detail'=> $request->sheet_name
                    );


                    array_push($full_new_inventory_data,$each_new_inventory_data);


                } // foreach


                if($rollback['status']) {
                    $request->session()->flash('alert-danger', $rollback['msg']);
                    return redirect($this->redirectTo);
                }

                if(New_Inventory_Data::insert($full_new_inventory_data)) {
                    $request->session()->flash('alert-success', 'upload success!');
                    return redirect($this->redirectTo);
                }

            } catch(Exception $e) {
                $request->session()->flash('alert-danger', $e);
                return redirect($this->redirectTo);
            }


        } else {
            $request->session()->flash('alert-danger', 'Out of scope!');
            return redirect($this->redirectTo);
        }

    }


    function checking_upload(Request $request) {
        $response = array(
            "error" => True,
            "messages" => "no-messages",
            "data"  => array()
        );
        
        
        $allow = false;
        $request->validate([
            'excel_file_event' => 'required'
        ]);

        if(
            $request->excel_file_event->getClientOriginalExtension() == 'xls' 
            ||
            $request->excel_file_event->getClientOriginalExtension() == 'xlsx'
          ) 
        {
            $allow = true;
        } 


        if(!$allow) {
            $response['messages'] = 'Extension file must in xls or xlsx';
            return json_encode($response);
        }


        if($request->excel_file_event){
            try { 
                $path = Input::file('excel_file_event')->getRealPath();
                $sheetNames = Excel::load($path)->getSheetNames();

                if (count($sheetNames) > 0) {
                    $response['data'] = $sheetNames;
                    $response['error'] = false;
                    return $response;
                } else {
                    $response['messages'] = "sheet not found!";
                    return json_encode($response);
                }
                
            } catch(Exception $e) {
                $response['messages'] = $e;
                return json_encode($response);
            }
        } else {
            $response['messages'] = 'out of scope, file must in xls or xlsx';
            return json_encode($response);
        }
    }


    function checking_history(Request $request) {
        $new_inventory_data = New_Inventory_Data::where('uuid',$request->log)->first();

        if($new_inventory_data == null) {
            echo "Inventory Data is not found";die;
        }


        $new_inventory_data_history = New_Inventory_Data_History::where('uuid',$request->log)
                                    ->orderBy('trigger_at','DESC')
                                    ->get();

        if(count($new_inventory_data_history) < 1) {
            echo "Inventory Data History is not found";die;
        }

        $data['history'] = $new_inventory_data_history;

        return view('new_inventory/history',compact('data'));
    }


    function download_data_inventory(Request $request) {
        $setting_list = 6;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($this->new_inventory_divisi_id,$user_divisi)
            || 
            in_array($setting_list,$user_setting)
            ) 
        {
            $allow = true;
            if(in_array($this->admin,$user_divisi)) {
                $this->is_super_admin = true;
            }
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to report features');
            return redirect($this->redirectTo);
        }


        $data = array(
            "group1"     => null,
            "group2"     => null,
            "group3"     => null,
            "group4"     => null,
            "inventory_list_id"     => null,

        );
        

        if($request->search_kota != null) {
            $data['group1'] = $request->search_kota;
        }

        if($request->search_gedung != null) {
            $data['group2'] = $request->search_gedung;
        }

        if($request->search_divisi != null) {
            $data['group3'] = $request->search_divisi;
        }

        if($request->search_sub_divisi != null) {
            $data['group4'] = $request->search_sub_divisi;
        }

        if($request->category != null) {
            $data['inventory_list_id'] = $request->category;
        }

        $new_inventory_data = $this->get_inventory_base_data($data);


        if( count($new_inventory_data->get()) < 1 ) {
            $request->session()->flash('alert-danger', 'Sorry you dont have any report to download');
            return redirect($this->redirectTo);
        }

        $main_data = $this->set_main_sheet($new_inventory_data);
        $sub_data = $this->set_sub_sheet($new_inventory_data);
        //dd($data);
        $type = "xls";
        //$data = Akses_Data::get()->toArray();
        Excel::create('inventory_reportv1', function($excel) use ($main_data,$sub_data) {
            $excel->sheet('main_data', function($sheet) use ($main_data)
            {
                $sheet->fromArray($main_data);
            });
            $excel->sheet('sub_data', function($sheet) use ($sub_data)
            {
                $sheet->fromArray($sub_data);
            });
        })->download($type);


        dd($new_inventory_data);

    }


    private function set_main_sheet($data) {
        return $data  = $data->select(
            'new_inventory_data.inventory_name AS inventory_name',
            'group1.group1_name as kota',
            'group2.group2_name as gedung',
            'group3.group3_name as divisi',
            'group4.group4_name as sub_divisi',
            'inventory_list.inventory_name AS inventory_category',
            'new_inventory_data.qty',
            'uc.name as created_by',
            'uu.name as updated_by',
            // 'inventory_list.inventory_detail_name AS inventory_detail_category',
            'new_inventory_data.tanggal_update_data',
            'new_inventory_data.kategori as ket1',
            'new_inventory_data.kode_gambar as ket2',
            'new_inventory_data.dvr as ket3',
            'new_inventory_data.lokasi_site as ket4',

            'new_inventory_data.kode_lokasi as ket5',
            'new_inventory_data.jenis_barang as ket6',
            'new_inventory_data.merk as ket7',
            'new_inventory_data.tipe as ket8',
            'new_inventory_data.model as ket9',

            'new_inventory_data.serial_number as ket10',
            'new_inventory_data.psu_adaptor as ket11',
            'new_inventory_data.tahun_pembuatan as ket12',
            'new_inventory_data.tahun_pengadaan as ket13',
            'new_inventory_data.kondisi as ket14',

            'new_inventory_data.deskripsi as ket15',
            'new_inventory_data.asuransi as ket16',
            'new_inventory_data.lampiran as ket17',
            'new_inventory_data.tanggal_retired as ket18',
            'new_inventory_data.po as ket19',

            'new_inventory_data.keterangan as ket20'
            // 'status_inventory.name'
            )
            ->orderBy('new_inventory_data.updated_at','desc')
            ->get()
            ->toArray();
    }


    private function set_sub_sheet($data) {
        $inventory_data_id_array = $data->select('new_inventory_data.id')->pluck('id');

        $data = New_Inventory_Sub_Data::join('new_inventory_data',
                        'new_inventory_data.id','=','new_inventory_sub_data.new_inventory_data_id')
                        ->join('users as c_user','c_user.id','=','new_inventory_sub_data.created_by')
                        ->join('users as u_user','u_user.id','=','new_inventory_sub_data.updated_by')
                        ->whereIn('new_inventory_data_id',$inventory_data_id_array)
                        ->select(
                        'new_inventory_data.inventory_name  AS inventory_name',
                        'new_inventory_sub_data.sub_data_status',
                        'new_inventory_sub_data.comment AS additional_note',
                        DB::raw('CONCAT(c_user.name, " =", new_inventory_sub_data.created_at) AS created_by'),
                        DB::raw('CONCAT(u_user.name, " =", new_inventory_sub_data.updated_at) AS updated_by'),
                        'new_inventory_sub_data.sub_data_uuid AS system id'
                        )
                        ->get()
                        ->toArray();

        return $data;
    }




    public function get_inventory_base_data($data) {
        $base_inventory_data = New_Inventory_Data::leftjoin('group1','group1.id','=','new_inventory_data.group1')
                ->leftjoin('group2','group2.id','=','new_inventory_data.group2')
                ->leftjoin('group3','group3.id','=','new_inventory_data.group3')
                ->leftjoin('group4','group4.id','=','new_inventory_data.group4')
                ->leftjoin('inventory_list','inventory_list.id','=','new_inventory_data.inventory_list_id')
                ->leftjoin('users as uc','uc.id','=','new_inventory_data.created_by')
                ->leftjoin('users as uu','uu.id','=','new_inventory_data.updated_by');


        if($data['group1'] != null ) {
            $base_inventory_data = $base_inventory_data->where('group1',$data['group1']);
        }

        if($data['group2'] != null ) {
            $base_inventory_data = $base_inventory_data->where('group2',$data['group2']);
        }

        if($data['group3'] != null ) {
            $base_inventory_data = $base_inventory_data->where('group3',$data['group3']);
        }

        if($data['group4'] != null ) {
            $base_inventory_data = $base_inventory_data->where('group4',$data['group4']);
        }

        if($data['inventory_list_id'] != null ) {
            $base_inventory_data = $base_inventory_data->where('inventory_list_id',$data['inventory_list_id']);
        }


        if(!$this->is_super_admin) {
            $role_specific_users = Users_Role::join('new_inventory_role','new_inventory_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi','=',$this->new_inventory_divisi_id)
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('new_inventory_role.user_id','=',Auth::user()->id)
                                ->get();


            if(count($role_specific_users) > 0) {


                $base_inventory_data->where(function ($query) use ($role_specific_users)  {
                    foreach($role_specific_users as $key_role=>$val_role) {
                        $query->Orwhere(function ($sub_query) use ($val_role) {
                        $sub_query->where('group1', '=', $val_role->group1)
                        ->where('group2', '=', $val_role->group2)
                        ->where('group3', '=', $val_role->group3)
                        ->where('group4', '=', $val_role->group4)
                        ->where('inventory_list_id', '=', $val_role->inventory_list_id);
                        });
                    }
                });

            } else {
                echo "ERROR in logic";die;
            }
        }

        return $base_inventory_data;
    }



}
