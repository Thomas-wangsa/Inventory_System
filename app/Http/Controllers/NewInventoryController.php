<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users_Role;
use App\Http\Models\Status_Inventory;
use App\Http\Models\New_Inventory_Role;
use App\Http\Models\New_Inventory_Data;

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
        $new_inventory_data = $base_inventory_data->get();

        
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
    public function create()
    {
        //
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
        echo "AAA";die;
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
}
