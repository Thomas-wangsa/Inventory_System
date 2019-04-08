<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Models\Group1;
use App\Http\Models\Group2;
use App\Http\Models\Group3;
use App\Http\Models\Group4;
use App\Http\Models\Inventory_List;
use App\Http\Models\Pic_List;
use App\Http\Models\Admin_Room_List;



class HelperController extends Controller
{
    protected $redirectTo = 'helper';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $restrict_setting = 9;
        $user_divisi = \Request::get('user_divisi');
        $user_setting = \Request::get('user_setting');
        $allow = false;
        if(
            in_array(1,$user_divisi)
            || 
            in_array($restrict_setting,$user_setting)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have access to helper features');
            return redirect('home');
        }

        $config = array(
            "Kota"    => 1,
            "Gedung"    => 2,
            "Divisi Indosat"    => 3,
            "Sub Divisi Indosat"    => 4,
            "Inventory" => 5,
            "PIC"       => 6,
            "Admin Room"=> 7,
        );
        

        $data = array(
            'config'    => $config,
            'rows'      => null
        );

        if($request->search == "on") {
            $rows = null;
            
            

            if($request->search_filter != null) {
                switch ($request->search_filter) {
                    case '1':
                        if($request->search_nama != null) {$rows = Group1::GetConfig($request->search_nama)->get();}
                        else {$rows = Group1::GetConfig()->get();}
                        break;
                    case '2':
                        if($request->search_nama != null) {$rows = Group2::GetConfig($request->search_nama)->get();}
                        else {$rows = Group2::GetConfig()->get();}
                        break;
                    case '3' :
                        if($request->search_nama != null) {$rows = Group3::GetConfig($request->search_nama)->get();}
                        else {$rows = Group3::GetConfig()->get();}
                        break;
                    case '4' :
                        if($request->search_nama != null) {$rows = Group4::GetConfig($request->search_nama)->get();}
                        else {$rows = Group4::GetConfig()->get();}
                        break;
                    case '5' :
                        if($request->search_nama != null) {$rows = Inventory_List::GetConfig($request->search_nama)->get();}
                        else {$rows = Inventory_List::GetConfig()->get();}
                        break;
                    case '6' :
                        if($request->search_nama != null) {$rows = Pic_List::GetConfig($request->search_nama)->get();}
                        else {$rows = Pic_List::GetConfig()->get();}
                        break;
                    case '7' :
                        if($request->search_nama != null) {$rows = Admin_Room_List::GetConfig($request->search_nama)->get();}
                        else {$rows = Admin_Room_List::GetConfig()->get();}
                        break;
                    default:
                        $request->session()->flash('alert-danger', "Out Of Scope Category value : $request->config_category");
                        return redirect($this->redirectTo);
                        break;
                }
            }



            $data['rows'] = $rows;
            //dd($data);
        }
        return view('helper/index',compact('data')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $request->validate([
            'config_main'  => 'required|max:30',
            'config_additional'  => 'required|max:30',
            'config_category'  => 'required|max:30',
        ]);

        switch ($request->config_category) {
            case '1':
                $data_exists = Group1::where('group1_name', $request->config_main)->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Group1 : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Group1;
                $data->group1_name      = $request->config_main;
                $data->group1_detail    = $request->config_additional;

                break;
            case '2':
                $data_exists = Group2::where('group2_name', $request->config_main)->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Group2 : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Group2;
                $data->group2_name      = $request->config_main;
                $data->group2_detail    = $request->config_additional;

                break;
            case '3':
                $data_exists = Group3::where('group3_name', $request->config_main)->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Group3 : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Group3;
                $data->group3_name      = $request->config_main;
                $data->group3_detail    = $request->config_additional;

                break;
            case '4':
                $data_exists = Group4::where('group4_name', $request->config_main)->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Group4 : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Group4;
                $data->group4_name      = $request->config_main;
                $data->group4_detail    = $request->config_additional;

                break;
            case '5':
                $data_exists = Inventory_List::where('inventory_name', strtolower($request->config_main))->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Inventory List : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Inventory_List;
                $data->inventory_name      = strtolower($request->config_main);
                $data->inventory_detail_name    = $request->config_additional;

                break;
            case '6':
                $data_exists = Pic_List::where('vendor_name', strtolower($request->config_main))->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Inventory List : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Pic_List;
                $data->vendor_name      = strtolower($request->config_main);
                $data->vendor_detail_name    = $request->config_additional;

                break;
            case '7':
                $data_exists = Admin_Room_List::where('admin_room', strtolower($request->config_main))->first();

                if($data_exists) {
                    $request->session()->flash('alert-danger', "Data already exists in Inventory List : $request->config_main");
                    return redirect($this->redirectTo);
                }


                $data  = new Admin_Room_List;
                $data->admin_room      = strtolower($request->config_main);
                $data->admin_room_detail    = $request->config_additional;

                break;
            default:
                $request->session()->flash('alert-danger', "Out Of Scope Category value : $request->config_category");
                return redirect($this->redirectTo);
                break;
        }

        $data->created_by = Auth::user()->id;
        $data->updated_by = Auth::user()->id;
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_at = date('Y-m-d H:i:s');

        if($data->save()) {
            $request->session()->flash('alert-success', "the $request->config_main have been added to category");
        } else {
            $request->session()->flash('alert-danger', "Data is not save, Please contact your administator");
        }
        
        return redirect($this->redirectTo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $data = null;
        switch ($id) {
            case '1':
                $data = Group1::find($request->edit_config_uuid);
                $data->group1_name      = $request->config_main;
                $data->group1_detail    = $request->config_additional;
                break;
            case '2':
                $data = Group2::find($request->edit_config_uuid);
                $data->group2_name      = $request->config_main;
                $data->group2_detail    = $request->config_additional;
                break;
            case '3':
                $data = Group3::find($request->edit_config_uuid);
                $data->group3_name      = $request->config_main;
                $data->group3_detail    = $request->config_additional;
                break;
            case '4':
                $data = Group4::find($request->edit_config_uuid);
                $data->group4_name      = $request->config_main;
                $data->group4_detail    = $request->config_additional;
                break;
            case '5':
                $data = Inventory_List::find($request->edit_config_uuid);
                $data->inventory_name      = $request->config_main;
                $data->inventory_detail_name    = $request->config_additional;
                break;
            case '6':
                $data = Pic_List::find($request->edit_config_uuid);
                $data->vendor_name      = $request->config_main;
                $data->vendor_detail_name    = $request->config_additional;
                break;
            case '7':
                $data = Admin_Room_List::find($request->edit_config_uuid);
                $data->admin_room      = $request->config_main;
                $data->admin_room_detail    = $request->config_additional;
                break;                
            default:
                # code...
                break;
        }

        if($data->save()) {
            $request->session()->flash('alert-success', "data already updated");
        } else {
            $request->session()->flash('alert-danger', "Data is not updated, Please contact your administator");
        }
        return redirect($this->redirectTo);
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
}
