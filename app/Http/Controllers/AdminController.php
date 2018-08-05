<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
use App\Http\Models\Divisi;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Akses_Data;
use App\Http\Models\Setting_List;

use Illuminate\Support\Facades\Auth;
use App\Notifications\New_User;

use Faker\Factory as Faker;

class AdminController extends Controller
{	
	protected $restrict = 1;
    protected $faker;

	public function __construct() {
        $this->faker    = Faker::create();
    }


    public function index(Request $request) {
        if(!in_array($this->restrict,\Request::get('user_divisi'))) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur admin');
            return redirect('home');
        }

    	$data	= array(
    		'users'				=> Users::GetDetailAll()->paginate(5),
    		'divisi'			=> Divisi::all(),
    		'inventory_list'	=> Inventory_List::all(),
            'setting_list'      => Setting_List::all()
    	);

    	return view('admin/admin',compact('data'));
    }

    public function create_new_users(Request $request) {

    	if (!preg_match("/^[a-zA-Z ]*$/",$request->staff_nama)) {
    		$request->session()->flash('alert-danger', 'Only letters and white space allowed!');
    		return redirect('admin');
		}

		if (!preg_match("/^[0-9]*$/",$request->staff_mobile)) {
			$request->session()->flash('alert-danger', 'Only numbers allowed!');
    		return redirect('admin'); 
		}

		$generated_password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

		$array_users = array(
			"name"		=> strtolower($request->staff_nama),
			"email"		=> strtolower($request->staff_email),
			"mobile"	=> strtolower($request->staff_mobile),
			"password"	=> bcrypt($generated_password)
		);

		$new_users = Users::firstOrCreate($array_users);


        $user_detail    = new Users_Detail;
        $user_detail->user_id     = $new_users->id;
        $user_detail->email_2     = $request->staff_email2;
        $user_detail->uuid        = $this->faker->uuid();
        $user_detail->foto        = "images/user/default.png";
        
		switch($request->select_divisi) {
			
			case 1 :
				$user_role      = new Users_Role;
        		$user_role->user_id 	= $new_users->id;
        		$user_role->divisi 		= $request->select_divisi;
                
			break;
			
			case 2 :
                $user_role      = new Users_Role;
                $user_role->user_id     = $new_users->id;
                $user_role->divisi      = $request->select_divisi;
                $user_role->jabatan     = $request->select_posisi;
			break;

			case 3 : 
				$inventory_role_array = array(
                    "user_id"              => $new_users->id,
					"inventory_list_id"		=> $request->inventory_list,
					"inventory_level_id"	=> $request->select_posisi
				);

				$new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);


                $user_role      = new Users_Role;
                $user_role->user_id     = $new_users->id;
                $user_role->divisi      = $request->select_divisi;
                $user_role->jabatan     = $new_inventory_role->id;
			break;

			default : 
				$request->session()->flash('alert-danger', 'Please contact your administrator !');
    			return redirect('admin');
    		break;
		}

        $user_role->save();
        $user_detail->save();

		$new_users->notify(new New_User($generated_password));

		$request->session()->flash('alert-success', 'Akun Berhasil di Tambahkan!');
		return redirect('admin');
    }

    public function edit_user(Request $request) {
        if (!preg_match("/^[a-zA-Z ]*$/",$request->staff_nama)) {
            $request->session()->flash('alert-danger', 'Only letters and white space allowed! in name field');
            return redirect('admin');
        }

        if (!preg_match("/^[0-9]*$/",$request->staff_mobile)) {
            $request->session()->flash('alert-danger', 'Only numbers allowed! in phone field');
            return redirect('admin'); 
        }

        $user_data = Users::GetDetailByUUID($request->uuid)->first();
        $check_exist   = Users::where('email',$request->staff_email)
                        ->where('id','!=',$user_data->id)
                        ->first();

        if(count($check_exist) > 0) {
            $request->session()->flash('alert-danger', 'Email telah terdaftar di Akun lain');
            return redirect('admin');
        }

        $user           = Users::find($user_data->id);
        $user->name     = $request->staff_nama;
        $user->email    = $request->staff_email;
        $user->mobile   = $request->staff_mobile;

        $user_detail    = Users_Detail::find($user_data->id);
        $user_detail->email_2 = $request->staff_email2;


        $user->save();
        $user_detail->save();

        $request->session()->flash('alert-success', 'Akun Berhasil di Edit!');
        return redirect('admin');

    }

    public function delete_user(Request $request) {
    	$users = Users::join('users_detail','users_detail.user_id','=','users.id')
    				->where('uuid',$request->uuid)->first()->delete();
    	$response = array(
    		"status"=>$users
    	);
    	return json_encode($response);
    }

    public function delete_user_notif(Request $request) {
    	$request->session()->flash('alert-warning', 'Akun Berhasil di Delete!');
    	return redirect('admin');
    }


    function delete_role_user(Request $request) {
        $role = Users_Role::find($request->role_id)->delete();
        $response = array(
            "status"=>$role
        );
        return json_encode($response);
    }

    public function delete_role_notif(Request $request) {
        $request->session()->flash('alert-warning', 'Role Berhasil di Delete!');
        return redirect('admin');
    }
}
