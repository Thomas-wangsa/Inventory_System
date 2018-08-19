<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Divisi;
use App\Http\Models\Pic_Level;
use App\Http\Models\Pic_List;
use App\Http\Models\Pic_Role;
use App\Http\Models\Inventory_Level;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Inventory_List;

class AjaxController extends Controller
{
    public function get_akses_role(Request $request) {
    	$role = Akses_Role::all();
    	return json_encode($role);
    }

    public function get_pic_level(Request $request) {
        $inventory_level = Pic_Level::all();
        return json_encode($inventory_level);
    }

    public function get_inventory_level(Request $request) {
    	$inventory_level = Inventory_Level::all();
    	return json_encode($inventory_level);
    }

    public function get_data_user(Request $request) {
    	$data = Users::GetDetailByUUID($request->uuid)->first();
    	return json_encode($data);
    }

    public function get_role_user(Request $request) {
        $data = Users::GetDetailByUUID($request->uuid)->first();
        $roles = Users_Role::where('user_id',$data->id) 
                    ->orderBy('divisi', 'asc')
                    ->orderBy('jabatan', 'asc')
                    ->get();

        $response = array();
        foreach ($roles as $key => $value) {
            $response[$key] = $value;
            $response[$key]['uuid']        = $data->uuid;
            $response[$key]['divisi_name'] = Divisi::find($value['divisi'])->name;
            $response[$key]['sub_level']   = "-";

            switch ($value['divisi']) {
                case 1:
                    $response[$key]['jabatan_name'] = "super admin";
                break;
                case 2:
                    $jabatan = Pic_Role::find($value['jabatan']);

                    if(count($jabatan) < 1 ) {
                        $response[$key]['jabatan_name'] = "null";
                    } else {
                        $inv_name = Pic_List::find($jabatan->pic_list_id);
                        $pos_name = Pic_level::find($jabatan->pic_level_id);

                        if(count($inv_name) > 0 && count($pos_name) > 0) {
                            $response[$key]['sub_level']   = $inv_name->vendor_name;
                            $response[$key]['jabatan_name'] = $pos_name->pic_level_name." ".$inv_name->vendor_name;
                        } else {
                            $response[$key]['jabatan_name'] = "null";
                        }

                        
                    }
                break;
                case 3:
                    $jabatan = Akses_Role::find($value['jabatan']);
                    if(count($jabatan) < 1 ) {
                        $response[$key]['jabatan_name'] = "null";
                    } else {
                        $response[$key]['jabatan_name'] = $jabatan->name;
                    }
                break;
                case 4:
                    $jabatan = Inventory_Role::find($value['jabatan']);

                    if(count($jabatan) < 1 ) {
                        $response[$key]['jabatan_name'] = "null";
                    } else {
                        $inv_name = Inventory_List::find($jabatan->inventory_list_id);
                        $pos_name = inventory_level::find($jabatan->inventory_level_id);

                        if(count($inv_name) > 0 && count($pos_name) > 0) {
                            $response[$key]['sub_level']   = $inv_name->inventory_name;
                            $response[$key]['jabatan_name'] = $pos_name->inventory_level_name." ".$inv_name->inventory_name;
                        } else {
                            $response[$key]['jabatan_name'] = "null";
                        }

                        
                    }
                break;
                
                default:
                    $response[$key]['jabatan_name'] = "Null";
                    break;
            }

            
        }
        return json_encode($response);
    }


    function delete_role_user(Request $request) {
        $response = "";
        if(Users_Role::find($request->role_id)->delete()) {
            $response = "Role telah di delete";
        } else {
            $response = "Role tidak aktif";
        }

        return json_encode($response);
    }
}
