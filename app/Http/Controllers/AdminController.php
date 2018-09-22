<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
use App\Http\Models\Divisi;
use App\Http\Models\Pic_List;
use App\Http\Models\Pic_Role;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Akses_Data;
use App\Http\Models\Setting_Role;
use App\Http\Models\Setting_List;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Notifications\New_User;

use Faker\Factory as Faker;

class AdminController extends Controller
{	
	protected $restrict = 1;
    protected $faker;
    protected $env = "production";

	public function __construct() {
        $this->faker    = Faker::create();
        $this->env      = env("ENV_STATUS", "development");
    }


    public function index(Request $request) {
        if(!in_array($this->restrict,\Request::get('user_divisi'))) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur admin');
            return redirect('home');
        }
        
        $users = Users::get();
        
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
        
        $users_id   = $users->pluck('id');
        $users      = Users::join('users_detail','users_detail.user_id','=','users.id')
                ->select('users.id','users.name','users.email'
                    ,'users.mobile','users_detail.nik','users_detail.foto',
                    'users_detail.company','users_detail.uuid')
                ->whereIn('users.id', $users_id)
                ->withTrashed();
        if($request->search_order != null) {
                $users = $users->orderBy($request->search_order, 'asc');
                //dd($users->get());
            }

        $users = $users->paginate(5);
        $level_authorization = array();
        
        foreach ($users as $key => $value) {
            $user_role = Users_Role::GetAllRoleById($value->id)->orderBy('divisi')->get();
            $level_authorization[$key] = $user_role;        
        }
        


    	$data	= array(
    		'users'				=> $users,
    		'divisi'			=> Divisi::all(),
    		'inventory_list'	=> Inventory_List::all(),
            'pic_list'          => Pic_List::all(),
            'setting_list'      => Setting_List::all(),
            'level_authorization'=> $level_authorization
    	);
        
    	return view('admin/admin',compact('data'));
    }

    public function create_new_users(Request $request) {
        
        $request->validate([
            'name'  => 'required|max:50',
            'nik'   => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'divisi' => 'required',
            'Personal_Identity' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'company' => 'required|max:50',
        ]);

    	if (!preg_match("/^[a-zA-Z ]*$/",$request->name)) {
    		$request->session()->flash('alert-danger', 'Only letters and white space allowed!');
    		return redirect('admin');
		}

		if (!preg_match("/^[0-9]*$/",$request->mobile)) {
			$request->session()->flash('alert-danger', 'Only numbers allowed!');
    		return redirect('admin'); 
		}


        if (!$request->hasFile('Personal_Identity')) {
            $request->session()->flash('alert-danger', 'Personal_Identity is required'); 
            return redirect('admin');            
        }

        $check_user_exist = Users::where('email',strtolower($request->email))
                    ->withTrashed()
                    ->first();
        if(count($check_user_exist) > 0) {
            $request->session()->flash('alert-danger', 'failed,email already registerred in our system'); 
            return redirect('admin');
        }

                        
        DB::beginTransaction();
        try {
            $generated_password = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);

            $this->env == "development" ? $define_password = bcrypt(123456) : $define_password = $generated_password; 
            

            $array_users = array(
                "name"      => strtolower($request->name),
                "email"     => strtolower($request->email),
                "mobile"    => strtolower($request->mobile),
                "password"  => bcrypt($define_password)
            );

            $new_users = Users::firstOrCreate($array_users);



            $image      = $request->file('Personal_Identity');
            $img_path   = '/images/user/';
            $img_name   = $this->faker->uuid().".".$image->getClientOriginalExtension();
            $destinationPath = public_path($img_path);
            $image->move($destinationPath, $img_name);

            $user_detail    = new Users_Detail;
            $user_detail->user_id     = $new_users->id;
            $user_detail->nik         = $request->nik;
            $user_detail->email_2     = strtolower($request->email_second);
            $user_detail->uuid        = time().$this->faker->uuid();
            $user_detail->foto        = $img_path.$img_name;
            $user_detail->company     = $request->company;
        
    		switch($request->divisi) {
    			
    			case 1 :
    				$user_role      = new Users_Role;
            		$user_role->user_id 	= $new_users->id;
            		$user_role->divisi 		= $request->divisi;
    			break;
    			
                case 2 :
                    //dd($request); 
                    $pic_role_array = array(
                        "user_id"              => $new_users->id,
                        "pic_list_id"     => $request->pic_list,
                        "pic_level_id"    => $request->position
                    );

                    $new_pic_role = Pic_Role::firstOrCreate($pic_role_array);

                    $user_role      = new Users_Role;
                    $user_role->user_id     = $new_users->id;
                    $user_role->divisi      = $request->divisi;
                    $user_role->jabatan     = $new_pic_role->id;
                break;

    			case 3 :
                    $user_role      = new Users_Role;
                    $user_role->user_id     = $new_users->id;
                    $user_role->divisi      = $request->divisi;
                    $user_role->jabatan     = $request->position;
    			break;

    			case 4 : 
    				$inventory_role_array = array(
                        "user_id"              => $new_users->id,
    					"inventory_list_id"		=> $request->inventory_list,
    					"inventory_level_id"	=> $request->position
    				);

    				$new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);


                    $user_role      = new Users_Role;
                    $user_role->user_id     = $new_users->id;
                    $user_role->divisi      = $request->divisi;
                    $user_role->jabatan     = $new_inventory_role->id;
    			break;

    			default : 
    				$request->session()->flash('alert-danger', 'Please contact your administrator !,Failed in Transaction Data Process');
        			return redirect('admin');
        		break;
    		}

            $user_role->save();
            $user_detail->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            echo "Please Contact your administrator";
            dd($e);
            // something went wrong
        }


        if($this->env == "development") {
            $request->session()->flash('alert-warning', 'Development Mode');
            return redirect('admin');
        }

		$new_users->notify(new New_User($generated_password,$new_users->email));
		$request->session()->flash('alert-success', 'new user has been created');
		return redirect('admin');
    }

    public function add_role_user(Request $request) {
        $response['status']   = false;
        $response['message']  = "";

        $user = Users::GetDetailByUUID($request->uuid)->first();
        if(count($user) < 1) {
            $response['message']  = "User data is not found";
            return json_encode($response);
        }

        $count_exist = 0;
        switch($request->divisi_role) {
            case "1" :
                $count_exist = Users_Role::where('user_id',$user->id)
                        ->where('divisi',$request->divisi_role)
                        ->withTrashed()
                        ->count();
            break;
            case "2" :
                $count_exist = Pic_Role::where('user_id',$user->id)
                        ->where('pic_list_id',$request->pic_role)
                        ->where('pic_level_id',$request->jabatan_role)
                        ->count();
            break;
            case "3" :
                $count_exist = Users_Role::where('user_id',$user->id)
                        ->where('divisi',$request->divisi_role)
                        ->where('jabatan',$request->jabatan_role)
                        ->withTrashed()
                        ->count();
            break;
            case "4" :
                $count_exist = Inventory_Role::where('user_id',$user->id)
                        ->where('inventory_list_id',$request->inv_role)
                        ->where('inventory_level_id',$request->jabatan_role)
                        ->count();
            break;
            default : 
                $count_exist = 1;
            break;
        }

        if($count_exist > 0) {
            $response['message']  = "Failed,level authority already exist";
            return json_encode($response);
        }


        
        switch($request->divisi_role) {
            case "1" :
                $param_jabatan = "0";
                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan = $param_jabatan;
                $new_user_role->save();
            break;
            case "2" :
                $pic_role_array = array(
                    "user_id"               => $user->id,
                    "pic_list_id"           => $request->pic_role,
                    "pic_level_id"          => $request->jabatan_role
                );

                $new_pic_role = Pic_Role::firstOrCreate($pic_role_array);

                $param_jabatan = $new_pic_role->id;

                $new_user_role          = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan = $param_jabatan;
                $new_user_role->save();
            break;
            case "3" :
                $param_jabatan = $request->jabatan_role;

                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan = $param_jabatan;
                $new_user_role->save();
            break;
            case "4" :
                $inventory_role_array = array(
                    "user_id"               => $user->id,
                    "inventory_list_id"     => $request->inv_role,
                    "inventory_level_id"    => $request->jabatan_role
                );

                $new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);

                $param_jabatan = $new_inventory_role->id;

                $new_user_role = new Users_Role;
                $new_user_role->user_id = $user->id;
                $new_user_role->divisi  = $request->divisi_role;
                $new_user_role->jabatan     = $param_jabatan;
                $new_user_role->save();
            break;
            default :
                $response['message']  = "outside of scope level authority";
                return json_encode($response);
            break;
        }

        $user_role_id = Users_Role::where('user_id',$user->id)
                        ->where('divisi',$request->divisi_role)
                        ->where('jabatan',$param_jabatan)
                        ->select('users_role.role_id')
                        ->first();
        if(count($user_role_id) < 1) {
            $response['message']  = "level authority error,user role id is not found";
            return json_encode($response);
        }

        $response['status']   = true;
        $response['message']  = "level authority has been added";
        $response['role_id']  = $user_role_id->role_id;
        return json_encode($response); 
    }


    public function add_role_special_user(Request $request) {
        $response['status']   = false;
        $response['message']  = "";

        $user = Users::GetDetailByUUID($request->uuid)->first();
        if(count($user) < 1) {
            $response['message']  = "User data is not found";
            return json_encode($response);
        }

        $count_exist = Setting_Role::where('user_id',$user->id)
            ->where('setting_list_id',$request->feature_role)
            ->withTrashed()->count();

        if($count_exist > 0) {
            $response['message']  = "Failed,features already exist";
            return json_encode($response);
        }

        $setting_role = new Setting_Role;
        $setting_role->user_id = $user->id;
        $setting_role->setting_list_id  = $request->feature_role ;
        $setting_role->created_by = Auth::user()->id;
        $setting_role->updated_by= Auth::user()->id;
        $setting_role->save();


        $response['status']   = true;
        $response['message']  = "new features has been added";
        $response['role_id']  = $setting_role->id;
        return json_encode($response); 
    }

    public function edit_user(Request $request) {

        $request->validate([
            'name'  => 'required|max:50',
            'nik'   => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'company' => 'required|max:50',
        ]);

        if (!preg_match("/^[a-zA-Z ]*$/",$request->name)) {
            $request->session()->flash('alert-danger', 'Only letters and white space allowed! in name field');
            return redirect('admin');
        }

        if (!preg_match("/^[0-9]*$/",$request->mobile)) {
            $request->session()->flash('alert-danger', 'Only numbers allowed! in mobile field');
            return redirect('admin'); 
        }

        DB::beginTransaction();
        try {
            $user_data = Users::GetDetailByUUID($request->uuid)->first();
            $check_exist   = Users::where('email',$request->email)
                            ->where('id','!=',$user_data->id)
                            ->first();

            if(count($check_exist) > 0) {
                $request->session()->flash('alert-danger', 'email already exist in another user');
                return redirect('admin');
            }

            $user           = Users::find($user_data->id);
            $user->name     = strtolower($request->name);
            $user->email    = strtolower($request->email);
            $user->mobile   = strtolower($request->mobile);

            $user_detail    = Users_Detail::find($user_data->id);
            $user_detail->nik = $request->nik;
            $user_detail->company = $request->company;
            $user_detail->email_2 = $request->email_second;


            $user->save();
            $user_detail->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            echo "Please Contact your administrator";
            dd($e);
            // something went wrong
        }

        $request->session()->flash('alert-success', 'updated user success');
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
        $request->session()->flash('alert-success', 'user status already active');
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
    	$request->session()->flash('alert-warning', 'user status already inactive');
    	return redirect('admin');
    }


    function restore_role_user(Request $request) {
        $status     = false;
        $message    = "Error : Position of users is available";
        $status = Users_Role::onlyTrashed()->find($request->role_id)->restore();
            if($status) {
                $message = "Position has been restored";
            }
        $response = array(
            "status"=>$status,
            "message"=>$message
        );

        return json_encode($response);
    }


    function restore_role_special_user(Request $request) {
        $status     = false;
        $message    = "Error : Position of features is available";
        $status = Setting_Role::onlyTrashed()->find($request->role_id)->restore();
            if($status) {
                $message = "Position has been restored";
            }
        $response = array(
            "status"=>$status,
            "message"=>$message
        );

        return json_encode($response);
    }

    function delete_role_user(Request $request) {
        $user_id = Users_Role::find($request->role_id)->user_id;

        $count_data = Users_Role::where('user_id',$user_id)->count();


        if($count_data <= 1) {
            $status  = false;
            $message = "at least each user level authority must be selected";
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


    function delete_role_special_user(Request $request) {
        $status = Setting_Role::find($request->role_id)->delete();

        
        $response = array(
            "status"=>$status
            //"message"=>$message
        );

        return json_encode($response);
    }

    public function delete_role_notif(Request $request) {
        $request->session()->flash('alert-warning', 'user level authority status already inactive');
        return redirect('admin');
    }

    public function add_role_notif(Request $request) {
        $request->session()->flash('alert-success', 'level authority has been added');
        return redirect('admin');
    }

    public function shorcut_insert_inventory(Request $request) {

        $inv_list   = strtolower($request->inv_list);
        $inv_detail = strtolower($request->inv_detail);

        $response['status'] = false;

        if($inv_list == null || $inv_list == "") {
            $response['message'] = "Inventory List is not found";
            return json_encode($response);
        } else if($inv_detail == null || $inv_detail == "") {
            $response['message'] = "Inventory Detail is not found";
            return json_encode($response);
        }

        $check_exist = Inventory_List::where('inventory_name',$inv_list)
                        ->first();
        if(count($check_exist) > 0) {
            $response['message'] = "Failed, inventory list already registerred!";
            return json_encode($response);
        }


        $inventory_list_data = new Inventory_List;
        $inventory_list_data->inventory_name = $inv_list;
        $inventory_list_data->inventory_detail_name = $inv_detail;
        $inventory_list_data->updated_by = Auth::user()->id;
        $inventory_list_data->save();
        

        $response['status'] = true;
        $response['data']   = $inventory_list_data;        
        return json_encode($response);
    }

    public function shorcut_insert_pic(Request $request) {

        $pic_list   = strtolower($request->pic_list);
        $pic_detail = strtolower($request->pic_detail);

        $response['status'] = false;

        if($pic_list == null || $pic_list == "") {
            $response['message'] = "Pic List is not found";
            return json_encode($response);
        } else if($pic_detail == null || $pic_detail == "") {
            $response['message'] = "Pic Detail is not found";
            return json_encode($response);
        }

        $check_exist = Pic_List::where('vendor_name',$pic_list)
                        ->first();
        if(count($check_exist) > 0) {
            $response['message'] = "Failed, pic list already registerred!";
            return json_encode($response);
        }


        $pic_list_data = new Pic_List;
        $pic_list_data->vendor_name    = $pic_list;
        $pic_list_data->vendor_detail_name = $pic_detail;
        $pic_list_data->updated_by = Auth::user()->id;
        $pic_list_data->save();
        

        $response['status'] = true;
        $response['data']   = $pic_list_data;        
        return json_encode($response);
    }
}
