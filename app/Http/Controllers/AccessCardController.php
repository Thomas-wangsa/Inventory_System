<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Akses_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
use App\Http\Models\Pic_List;
use App\Http\Models\Pic_Role;
use App\Http\Models\Pic_Level;
use App\Http\Models\Admin_Room_List;
use App\Http\Models\Admin_Room_Role;
use App\Http\Models\Status_Akses;
use App\Http\Models\Akses_Role;
use App\Http\Models\Notification AS notify;
use App\Http\Models\AccessCardRequest;
use App\Http\Models\AccessCardRegisterStatus;



use App\Http\Models\Divisi;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Setting_Data;

use Illuminate\Support\Facades\DB;

use App\Mail\AksesMail;
use Illuminate\Support\Facades\Mail;

use App\Notifications\Akses_Notifications;
//use App\Notifications\Access_Notification;
use Illuminate\Support\Facades\Notification;

use Faker\Factory as Faker;

class AccessCardController extends Controller
{
    protected $redirectTo      = '/accesscard';
    protected $url;
    protected $credentials;
    protected $faker;
    
    protected $admin    = 1;
    protected $env = "production";
    protected $indosat_path;

    public function __construct(UrlGenerator $url){
        // $this->url      = $url;
        $this->faker    = Faker::create();
        $this->env      = env("ENV_STATUS", "development");

        if($this->env == "production") {
            
            // sleep(5);
            // echo "AAA";die;

        }
    }

    public function index(Request $request) {

        // Restrict
        $restrict_divisi_pic = 2;
        $restrict_divisi_access = 3;
        $restrict_divisi_admin_room = 5;
        $user_divisi = \Request::get('user_divisi');
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($restrict_divisi_pic,$user_divisi)
            || 
            in_array($restrict_divisi_access,$user_divisi) 
            ||
            in_array($restrict_divisi_admin_room,$user_divisi)  
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to access page');
            return redirect('home');
        }
        // Restrict


        // Data && pic list
        if(in_array($this->admin,$user_divisi) 
            || 
            in_array($restrict_divisi_access, $user_divisi)
            ) 
        {
            $akses_data = Akses_Data::join('status_akses'
                ,'status_akses.id','=','akses_data.status_akses')
            ->join('users','users.id','=','akses_data.created_by')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id');

            $pic_list_dropdown = Pic_List::all();
        } else if(in_array(2,$user_divisi)) {
            $pic_list_id_data = Users_Role::join('pic_role','pic_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',$restrict_divisi_pic)
                            ->where('pic_role.user_id',Auth::user()->id)
                            // ->select('users_role.user_id AS user_id',
                            //         'pic_role.user_id AS pic_user_id',
                            //         'users_role.jabatan','pic_role.id AS pic_id',
                            //         'pic_role.pic_list_id','pic_role.pic_level_id'
                            //         )
                            ->groupBy('pic_role.pic_list_id')
                            ->pluck('pic_list_id');
            
            $akses_data = Akses_Data::join('status_akses'
                ,'status_akses.id','=','akses_data.status_akses')
            ->join('users','users.id','=','akses_data.created_by')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id')
            ->whereIn('akses_data.pic_list_id',$pic_list_id_data);
            $pic_list_dropdown = Pic_List::whereIn('id',$pic_list_id_data)->get();
        }




    	$data = array(
        'status_akses'  => Status_Akses::all(),
        'request_type'  => AccessCardRequest::all(),
        'register_type'  => AccessCardRegisterStatus::all(),
        'pic_list'      => $pic_list_dropdown,
        'faker'         => $this->faker,
        );
        return view('accesscard/index',compact('data'));
    }

    public function accesscardrequest(Request $request) {
        $data = array();
        return view('accesscard/request',compact('data'));
    }

    public function new_pic_list(Request $request) {
        $vendor_name = strtolower($request->vendor_name);
        $vendor_detail_name = $request->vendor_detail_name;

        $exist = Pic_List::where('vendor_name',$vendor_name)->count();

        if($exist > 0) {
            $request->session()->flash('alert-danger', 'Register Failed,Pic Category already exist');
            return redirect($this->redirectTo);
        } else {
            $pic_list = new Pic_List;
            $pic_list->vendor_name          = $vendor_name;
            $pic_list->vendor_detail_name   = $vendor_detail_name;
            $pic_list->updated_by           = Auth::user()->id;

            if($pic_list->save()) {
                $request->session()->flash('alert-success', 'Register Pic category success');
            } else {
                $request->session()->flash('alert-danger', 'Register Failed,Please contact your administrator');
            }
        }
        return redirect($this->redirectTo);
    }


    public function new_admin_room_list(Request $request) {
        $vendor_name = strtolower($request->vendor_name);
        $vendor_detail_name = $request->vendor_detail_name;

        $exist = Admin_Room_List::where('admin_room',$vendor_name)->count();

        if($exist > 0) {
            $request->session()->flash('alert-danger', 'Register Failed,Admin Room already exist');
            return redirect($this->redirectTo);
        } else {
            $pic_list = new Admin_Room_List;
            $pic_list->admin_room          = $vendor_name;
            $pic_list->admin_room_detail   = $vendor_detail_name;
            $pic_list->updated_by           = Auth::user()->id;

            if($pic_list->save()) {
                $request->session()->flash('alert-success', 'Register Admin Room success');
            } else {
                $request->session()->flash('alert-danger', 'Register Failed,Please contact your administrator');
            }
        }
        return redirect($this->redirectTo);
    }
}


