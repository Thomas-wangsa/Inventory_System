<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Divisi;
use App\Http\Models\Inventory_Level;

class AjaxController extends Controller
{
    public function get_akses_role(Request $request) {
    	$role = Akses_Role::all();
    	return json_encode($role);
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
        $roles = Users_Role::where('user_id',$data->id)->get();

        $response = array();
        foreach ($roles as $key => $value) {
            $response[$key] = $value;
            $response[$key]['divisi_name'] = Divisi::find($value['divisi'])->name;

            

            $response[$key]['data'] = "data";  
        }

        return json_encode($response);
    }
}
