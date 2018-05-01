<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;

use App\Http\Models\Divisi;
use App\Http\Models\Inventory_List;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index() {
    	$data 	= Users::GetJabatan()->get();
    	$user 	= Auth::user();
    	$divisi = Divisi::all();
    	$inventory_list = Inventory_List::all();
    	return view('admin/admin',compact('data','user','divisi','inventory_list'));
    }

    public function create_new_users(Request $request) {
    	if (!preg_match("/^[a-zA-Z ]*$/",$request->staff_nama)) {
  			$error = "Only letters and white space allowed";
  			return json_encode($error); 
		}

		if (!preg_match("/^[0-9]*$/",$request->staff_mobile)) {
  			$error = "Only numbers allowed";
  			return json_encode($error); 
		}

		$array_users = array(
			"name"		=> strtolower($request->staff_nama),
			"email"		=> strtolower($request->staff_email),
			"mobile"	=> strtolower($request->staff_mobile),
			"password"	=> bcrypt("123456")
		);

		switch($request->select_divisi) {
			
			case 1 : 
				$new_users = Users::firstOrCreate($array_users);
				
				$user_role = new Users_Role;

        		$user_role->user_id 	= $new_users->id;
        		$user_role->divisi 		= $request->select_divisi;

        		$user_role->save();
			break;
			
			case 2 : 
				echo "2";
			break;

			case 3 : 
				echo "3";
			break;

			default : echo "Please contact your administrator";die;
		}


    	

    	dd($request);
    }
}
