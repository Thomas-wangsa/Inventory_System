<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
use App\Http\Models\Divisi;
use App\Http\Models\Pic_List;
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
        $users = Users::where('id','!=',Auth::user()->id);
        
        $deleted = false;
        if($request->search == "on") {
            if($request->search_nama != null) {
                $users = $users->where('users.name','like',$request->search_nama."%");
            } else if($request->search_filter != null) {
                if($request->search_filter == "is_deleted") {
                    $users =  Users::onlyTrashed();
                    $deleted = true;
                } else {
                    $roles = $users->join('users_role','users_role.user_id','=','users.id')
                    ->where('users_role.divisi',$request->search_filter)
                    ->select('users.id')
                    ->distinct()
                    ->get()
                    ->pluck('id');

                    $users = Users::whereIn('id', $roles);
                }
            } 
        }
        
        $users_id   = $users->get()->pluck('id');
        $users      = Users::join('users_detail','users_detail.user_id','=','users.id')
                ->select('users.id','users.name','users.email','users.mobile',
            'users_detail.foto','users_detail.uuid')
                ->whereIn('users.id', $users_id)
                ->withTrashed();
        if($request->search_order != null) {
                $users = $users->orderBy($request->search_order, 'asc');
                //dd($users->get());
            }
        $users = $users->paginate(5);


        if(!in_array($this->restrict,\Request::get('user_divisi'))) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur admin');
            return redirect('home');
        }

    	$data	= array(
    		'users'				=> $users,
    		'divisi'			=> Divisi::all(),
    		'inventory_list'	=> Inventory_List::all(),
            'pic_list'          => Pic_List::all(),
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
        $user_detail->email_2     = strtolower($request->staff_email2);
        $user_detail->uuid        = $this->faker->uuid();
        $user_detail->foto        = "/images/template/default.png";
        
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

    public function add_role_user(Request $request) {
        $response['status']   = false;
        $response['message']  = "";

        $user = Users::GetDetailByUUID($request->uuid)->first();
        if(count($user) < 1) {
            $response['message']  = "Data User tidak di temukan";
            return json_encode($response);
        }

        $count_exist = 0;
        switch($request->divisi_role) {
            case "1" :
                $count_exist = Users_Role::where('user_id',$user->id)
                        ->where('divisi',$request->divisi_role)
                        ->count();
            break;
            case "2" :
            case "3" :
                $count_exist = Users_Role::where('user_id',$user->id)
                        ->where('divisi',$request->divisi_role)
                        ->where('jabatan',$request->jabatan_role)
                        ->count();
            break;
            default : 
                $count_exist = 1;
            break;
        }

        if($count_exist > 0) {
            $response['message']  = "Gagal,Role Sudah Terdaftar";
            return json_encode($response);
        }


        
        switch($request->divisi_role) {
            case "1" :
                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan = "0";
                $new_user_role->save();
            break;
            case "2" :
                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan = $request->jabatan_role;
                $new_user_role->save();
            break;
            case "3" :
                $inventory_role_array = array(
                    "user_id"               => $user->id,
                    "inventory_list_id"     => $request->inv_role,
                    "inventory_level_id"    => $request->jabatan_role
                );

                $new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);

                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan     = $new_inventory_role->id;
                $new_user_role->save();
            break;
            default :
                $new_user_role->divisi = "0";
            break;
        }

        $response['status']   = true;
        $response['message']  = "Role telah di tambahkan";
        return json_encode($response); 
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
        $user->name     = strtolower($request->staff_nama);
        $user->email    = strtolower($request->staff_email);
        $user->mobile   = strtolower($request->staff_mobile);

        $user_detail    = Users_Detail::find($user_data->id);
        $user_detail->email_2 = $request->staff_email2;


        $user->save();
        $user_detail->save();

        $request->session()->flash('alert-success', 'Akun Berhasil di Edit!');
        return redirect('admin');

    }

    public function aktifkan_user(Request $request) {
        $users = Users::withTrashed()->where('id',$request->id)->restore();
        $response = array(
            "status"=>$users
        );
        return json_encode($response);
    }

    public function aktif_user_notif(Request $request) {
        $request->session()->flash('alert-success', 'Akun Berhasil di Restore!');
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
        $user_id = Users_Role::find($request->role_id)->user_id;

        $count_data = Users_Role::where('user_id',$user_id)->count();


        if($count_data <= 1) {
            $status  = false;
            $message = "Minimal 1 user 1 role";
        } else {
            $status = Users_Role::find($request->role_id)->delete();
            $message = "";            
        }
        
        $response = array(
            "status"=>$status,
            "message"=>$message
        );

        return json_encode($response);
    }

    public function delete_role_notif(Request $request) {
        $request->session()->flash('alert-warning', 'Role Berhasil di Delete!');
        return redirect('admin');
    }

    public function add_role_notif(Request $request) {
        $request->session()->flash('alert-success', 'Role Berhasil di Tambahkan!');
        return redirect('admin');
    }
}
