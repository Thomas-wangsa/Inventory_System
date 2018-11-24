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
use App\Http\Models\Admin_Room_List;
use App\Http\Models\Admin_Room_Role;
use App\Http\Models\Setting_List;
use App\Http\Models\Setting_Role;

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

    public function get_pic_list(Request $request) {
        $pic_list = Pic_List::all();
        return json_encode($pic_list);
    }

    public function get_admin_room_list(Request $request) {
        $pic_list = Admin_Room_List::all();
        return json_encode($pic_list);
    }

    public function get_inventory_level(Request $request) {
    	$inventory_level = Inventory_Level::all();
    	return json_encode($inventory_level);
    }

    public function get_inventory_list(Request $request) {
        $inventory_list = Inventory_List::all();
        return json_encode($inventory_list);
    }

    public function get_data_user(Request $request) {
    	$data = Users::GetDetailByUUID($request->uuid)->first();
    	return json_encode($data);
    }

    public function get_special_level(Request $request) {
        $response = array();
        $response['status'] = false;

        $data = Users::GetDetailByUUID($request->uuid)->first();
        if(count($data) < 1) {
            $response['message'] = "User ID not found";
            return json_encode($response);
        }

        $response['data'] = Setting_Role::join('setting_list','setting_list.id','=','setting_role.setting_list_id')
        ->where('setting_role.user_id',$data->id)
        ->select('setting_list.setting_name','setting_role.id AS setting_role_id','setting_role.deleted_at AS setting_role_deleted')
        ->withTrashed()
        ->get();

        $response['status'] = true;

        return json_encode($response);
    }

    public function get_role_user(Request $request) {
        $data = Users::GetDetailByUUID($request->uuid)->first();
        $roles = Users_Role::where('user_id',$data->id) 
                    ->orderBy('divisi', 'asc')
                    ->orderBy('jabatan', 'asc')
                    ->withTrashed()
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
                    $detail_jabatan = Pic_Role::join('pic_list',
                        'pic_list.id','=','pic_role.pic_list_id')
                        ->join('pic_level',
                        'pic_level.id','=','pic_role.pic_level_id')
                        ->where('pic_role.id',$value['jabatan'])
                        ->where('pic_role.user_id',$data->id)
                        ->select('pic_role.id','pic_list.vendor_name','pic_level.pic_level_name')
                        ->first();

                    if(count($detail_jabatan) < 1 ) {
                        $response[$key]['jabatan_name'] = "null";
                    } else {
                        if(
                            $detail_jabatan->vendor_name != null &&
                            $detail_jabatan->pic_level_name != null
                        ) {
                            $response[$key]['sub_level']   = $detail_jabatan->vendor_name;
                            $response[$key]['jabatan_name'] = $detail_jabatan->pic_level_name." ".$detail_jabatan->vendor_name;
                        } else {
                            $response[$key]['jabatan_name'] = "undefined";
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
                    $detail_jabatan = Inventory_Role::join('inventory_list',
                        'inventory_list.id','=','inventory_role.inventory_list_id')
                        ->join('inventory_level',
                        'inventory_level.id','=','inventory_role.inventory_level_id')
                        ->where('inventory_role.id',$value['jabatan'])
                        ->where('inventory_role.user_id',$data->id)
                        ->select('inventory_role.id','inventory_list.inventory_name','inventory_level.inventory_level_name')
                        ->first();

                    if(count($detail_jabatan) < 1 ) {
                        $response[$key]['jabatan_name'] = "null";
                    } else {
                        if(
                            $detail_jabatan->inventory_name != null &&
                            $detail_jabatan->inventory_level_name != null
                        ) {
                            $response[$key]['sub_level']   = $detail_jabatan->inventory_name;
                            $response[$key]['jabatan_name'] = $detail_jabatan->inventory_level_name." ".$detail_jabatan->inventory_name;
                        } else {
                            $response[$key]['jabatan_name'] = "undefined";
                        }      
                    }
                break;
                case 5:
                    //$detail_jabatan = Admin_Room_Role::find($value['jabatan']);

                    $detail_jabatan = Admin_Room_Role::join('admin_room_list',
                        'admin_room_list.id',
                        '=','admin_room_role.admin_room_list_id')
                        ->where('admin_room_role.id','=',$value['jabatan'])
                        ->select('admin_room_list.admin_room')
                        ->first();

                    $response[$key]['jabatan_name'] = '-';
                    if(count($detail_jabatan) < 1 ) {
                        $response[$key]['sub_level'] = "null";
                    } else {
                        $response[$key]['sub_level'] = $detail_jabatan->admin_room;
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
