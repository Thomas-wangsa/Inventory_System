<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;

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
	protected $credentials;
    protected $faker;

	public function __construct() {
            $this->faker    = Faker::create();


		$this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            return $next($request);
        });
        
    }


    public function index() {
    	if($this->credentials->divisi != 4) {
    		return redirect('home'); 
    	}

    	$data	= array(
    		'credentials'		=> $this->credentials,
    		'users'				=> Users::GetJabatan()->paginate(5),
    		'divisi'			=> Divisi::all(),
    		'inventory_list'	=> Inventory_List::all(),
            'setting_list'      => Setting_List::all()
    	);

    	return view('admin/admin',compact('data'));
    }

    public function create_new_users(Request $request) {
    	if($this->credentials->divisi != 4) {
            $request->session()->flash('alert-danger', 'Not allowed');
    		return redirect('home'); 
    	}

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
		switch($request->select_divisi) {
			
			case 1 :
            case 4 :  
				$user_role = new Users_Role;
        		$user_role->user_id 	= $new_users->id;
        		$user_role->divisi 		= $request->select_divisi;
                $user_role->uuid        = $this->faker->uuid();
                $user_role->foto        = "images/user/default.png";
        		$user_role->save();
			break;
			
			case 2 : 
				$user_role = new Users_Role;
        		$user_role->user_id 	= $new_users->id;
        		$user_role->divisi 		= $request->select_divisi;
        		$user_role->jabatan 	= $request->select_posisi;
                $user_role->uuid        = $this->faker->uuid();
                $user_role->foto        = "images/user/default.png";
        		$user_role->save();
			break;

			case 3 : 
				$inventory_role_array = array(
					"inventory_list_id"		=> $request->inventory_list,
					"inventory_level_id"	=> $request->select_posisi
				);

				$new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);

				$user_role = new Users_Role;
        		$user_role->user_id 	= $new_users->id;
        		$user_role->divisi 		= $request->select_divisi;
        		$user_role->jabatan 	= $new_inventory_role->id;
                $user_role->foto        = "images/user/default.png";
                $user_role->uuid        = $this->faker->uuid();
        		$user_role->save();
			break;

			default : 
				$request->session()->flash('alert-danger', 'Please contact your administrator !');
    			return redirect('admin');
    		break;
		}

		$new_users->notify(new New_User($generated_password));

		$request->session()->flash('alert-success', 'Akun Berhasil di Tambahkan!');
		return redirect('admin');
    }


    public function delete_user(Request $request) {
    	$users = Users::join('users_role','users_role.user_id','=','users.id')
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
}
