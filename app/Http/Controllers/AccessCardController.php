<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Notification AS custom_notification;
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
use App\Notifications\PhotoSchedule_Notification;
use App\Notifications\PickupAccessCard_Notification;

//use App\Notifications\Access_Notification;
use Illuminate\Support\Facades\Notification;

use Illuminate\Support\Facades\Input;
use Excel;

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


        // for add new acess 
        $insert_access_data = false;
        if(in_array($this->admin,$user_divisi)) {
            //echo "only admin";
            $insert_access_data = true;
        } else if(in_array($restrict_divisi_pic,$user_divisi)) {
            //echo "only pic";
            $data_pic = Users_Role::join('pic_role','pic_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',$restrict_divisi_pic)
                            ->where('pic_role.user_id',Auth::user()->id)
                            ->where('pic_role.pic_level_id',1)
                            ->select('users_role.user_id AS user_id',
                                    'pic_role.user_id AS pic_user_id',
                                    'users_role.jabatan','pic_role.id AS pic_id',
                                    'pic_role.pic_list_id','pic_role.pic_level_id'
                                    )
                            ->get();
            if(count($data_pic) > 0) {
                $insert_access_data = true;
            }
        }

        

        // Sponsor
        $sponsor_access_data = array();

        if(in_array($restrict_divisi_pic,$user_divisi)) {
            $sponsor_access_data = Users_Role::join('pic_role','pic_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',$restrict_divisi_pic)
                            ->where('pic_role.user_id',Auth::user()->id)
                            ->where('pic_role.pic_level_id',2)
                            ->pluck('pic_list_id')->toArray();
        }


        // Admin Room
        $admin_room_access_data = array();

        if(in_array($restrict_divisi_admin_room,$user_divisi)) {
            $admin_room_access_data = Users_Role::join('admin_room_role','admin_room_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',$restrict_divisi_admin_room)
                            ->where('admin_room_role.user_id',Auth::user()->id)
                            ->pluck('admin_room_list_id')->toArray();
            //dd($admin_room_access_data);
        }
        


        // Access Card Entity
        $verification     = false;
        $approval_verification      = false;
        $card_printing    = false;
        $approval_activation     = false;
        $staff_activation   = false;

        if(in_array($restrict_divisi_access,$user_divisi)) {
            $access_role_array = Users_Role::where('user_id',Auth::user()->id)
                            ->where('divisi',$restrict_divisi_access)
                            ->pluck('jabatan')->toArray();
            if(in_array(1,$access_role_array)) {
                $verification = true;
            }

            if(in_array(2,$access_role_array)) {
                $approval_verification = true;
            } 

            if(in_array(3,$access_role_array)) {
                $card_printing = true;
            } 

            if(in_array(4,$access_role_array)) {
                $approval_activation = true;
            }

            if(in_array(5,$access_role_array)) {
                $staff_activation = true;
            } 
        }
        // Access Card Entity




        // Data && pic list
        $pic_list_dropdown = array();
        if(in_array($this->admin,$user_divisi) 
            || 
            in_array($restrict_divisi_access, $user_divisi)
            ) 
        {
            $akses_data = Akses_Data::join('status_akses'
                ,'status_akses.id','=','akses_data.status_akses')
            ->join('users','users.id','=','akses_data.created_by')
            ->join('access_card_register_status',
            'access_card_register_status.id','=','akses_data.register_type')
            ->join('access_card_request',
            'access_card_request.id','=','akses_data.request_type')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id');

            $pic_list_dropdown = Pic_List::all();
        } else if(in_array($restrict_divisi_admin_room,$user_divisi) && in_array(2,$user_divisi)) {
            // owner & pic
            //echo "ONLY OWNER & PIC";die;
            $pic_list_id_data = Users_Role::join('pic_role','pic_role.id','=','users_role.jabatan')
            ->where('users_role.user_id',Auth::user()->id)
            ->where('users_role.divisi',2)
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
            ->join('access_card_register_status',
            'access_card_register_status.id','=','akses_data.register_type')
            ->join('access_card_request',
            'access_card_request.id','=','akses_data.request_type')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id')
            ->where(function ($query) {
                $pic_list_id_data = Users_Role::join('pic_role','pic_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',2)
                            ->where('pic_role.user_id',Auth::user()->id)
                            // ->select('users_role.user_id AS user_id',
                            //         'pic_role.user_id AS pic_user_id',
                            //         'users_role.jabatan','pic_role.id AS pic_id',
                            //         'pic_role.pic_list_id','pic_role.pic_level_id'
                            //         )
                            ->groupBy('pic_role.pic_list_id')
                            ->pluck('pic_list_id');

                $admin_room_access_data_pluck = Users_Role::join('admin_room_role','admin_room_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',5)
                            ->where('admin_room_role.user_id',Auth::user()->id)
                            ->pluck('admin_room_list_id');

                $query->whereIn('akses_data.pic_list_id',$pic_list_id_data)
                      ->orWhereIn('akses_data.admin_room_list_id',$admin_room_access_data_pluck);
            });
            //dd($akses_data->count());
            // ->whereIn('akses_data.pic_list_id',$pic_list_id_data)
            // ->orWhereIn('akses_data.admin_room_list_id',$admin_room_access_data_pluck);



            $pic_list_dropdown = Pic_List::whereIn('id',$pic_list_id_data)->get();
        } else if(in_array($restrict_divisi_admin_room,$user_divisi) && !in_array(2,$user_divisi)) {
            // only owner
            //echo "ONLY OWNER";

            $admin_room_access_data_pluck = Users_Role::join('admin_room_role','admin_room_role.id','=','users_role.jabatan')
                            ->where('users_role.user_id',Auth::user()->id)
                            ->where('users_role.divisi',$restrict_divisi_admin_room)
                            ->where('admin_room_role.user_id',Auth::user()->id)
                            ->pluck('admin_room_list_id');
            //dd($admin_room_access_data);die;

            
            
            $akses_data = Akses_Data::join('status_akses'
                ,'status_akses.id','=','akses_data.status_akses')
            ->join('users','users.id','=','akses_data.created_by')
            ->join('access_card_register_status',
            'access_card_register_status.id','=','akses_data.register_type')
            ->join('access_card_request',
            'access_card_request.id','=','akses_data.request_type')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id')
            ->whereIn('akses_data.admin_room_list_id',$admin_room_access_data_pluck);
           
        } else if(!in_array($restrict_divisi_admin_room,$user_divisi) && in_array(2,$user_divisi)) {
            // only pic
            //echo "ONLY PIC";die;
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
            ->join('access_card_register_status',
            'access_card_register_status.id','=','akses_data.register_type')
            ->join('access_card_request',
            'access_card_request.id','=','akses_data.request_type')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id')
            ->whereIn('akses_data.pic_list_id',$pic_list_id_data);

            //dd($akses_data->count());

            $pic_list_dropdown = Pic_List::whereIn('id',$pic_list_id_data)->get();
        }


        //
        // FILTER
        if($request->search == "on") {
            if($request->search_nama != null) {
                $akses_data = $akses_data->where('akses_data.name','like','%'.$request->search_nama."%");
            } 
            
            if($request->request_type_filter != null) {
                $akses_data = $akses_data->where('akses_data.request_type',$request->request_type_filter);
            }   
                     
            if($request->search_filter != null) {
                $akses_data = $akses_data->where('akses_data.status_akses',$request->search_filter);            
            } 

            if($request->search_pic != null) {
                $akses_data = $akses_data->where('akses_data.pic_list_id',$request->search_pic);
            }   

            if($request->search_uuid != null) {
                $akses_data = $akses_data->where('akses_data.uuid',$request->search_uuid);
            }
        }


        // if(in_array($this->admin,$user_divisi)) {
        //     //$akses_data = $akses_data->whereIn('akses_data.status_akses',[1,2,3,4,5,6,7,8]);    
        // } else if(in_array($restrict_divisi_access, $user_divisi) 
        //     && count($user_divisi) == 1) {
        //     $akses_data = $akses_data->whereIn('akses_data.status_akses',[2,3,4,5,6,7,8]);
        // } else if(in_array($restrict_divisi_pic,$user_divisi) 
        //         && count($user_divisi) == 1) {
        //     $akses_data = $akses_data->whereIn('akses_data.status_akses',[1]);
        // } 
            
        
        //dd($akses_data->count());

        $akses_data->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','pic_list.vendor_name','pic_list.vendor_detail_name','users.name AS created_by_name','access_card_register_status.register_name AS register_name','access_card_request.request_name AS request_name')
            ->orderBy('akses_data.id', 'DESC');

        // if($request->search_order != null) {
        //     $akses_data =    $akses_data->orderBy($request->search_order,'asc');
        // } else {
        //     $akses_data =    $akses_data->orderBy('akses_data.id','DESC');
        // }
        
        $final_akses_data = $akses_data->paginate(10);


        $conditional_sponsor    = array();
        $conditional_admin_room = array();
        foreach($final_akses_data as $key=>$val) {
            $is_data_sponsor = false;
            if(in_array($val->pic_list_id,$sponsor_access_data)) {
                $is_data_sponsor = true;
            }
            array_push($conditional_sponsor,$is_data_sponsor);

            $is_admin_room = false;
            if(in_array($val->admin_room_list_id,$admin_room_access_data)) {
                $is_admin_room = true;
            }
            array_push($conditional_admin_room,$is_admin_room);
            //echo $val->pic_list_id;
        }
    	$data = array(
        'data'         => $final_akses_data,
        'status_akses'  => Status_Akses::all(),
        'request_type'  => AccessCardRequest::all(),
        'register_type'  => AccessCardRegisterStatus::all(),
        'admin_room_drop_down' => Admin_Room_List::all(),
        'pic_list'      => $pic_list_dropdown,
        'faker'         => $this->faker,
        'insert_access_data'       => $insert_access_data,
        'sponsor_access_data'      => $conditional_sponsor,
        'verification'   => $verification,
        'approval_verification'   => $approval_verification,
        'card_printing'   => $card_printing,
        'approval_activation'   => $approval_activation,
        'staff_activation'   => $staff_activation,
        'admin_room'        => $conditional_admin_room
        );
        //dd($data);
        return view('accesscard/index',compact('data'));
    }

    public function post_leveling_access_card_number(Request $request) {
        $request->validate([
            'leveling_create_uuid'                => 'required|max:150',
            'leveling_create_register_status'     => 'required|max:50',
            'leveling_create_full_name'           => 'required|max:100',
            'leveling_create_accesscard'          => 'required|max:150',
            'leveling_location_activities'        => 'required|max:80',
        ]);

        // First Validation 
        $old_data = Akses_Data::where('no_access_card',$request->leveling_create_accesscard)
                        ->where('status_akses',9)
                        ->where('uuid',$request->leveling_create_uuid)
                        ->first();
        if(count($old_data) < 1) {
            $request->session()->flash('alert-danger', 'Access Card not Found!');
            return redirect($this->redirectTo);
        }
        // First Validation is failed

        $access_data = new Akses_Data;
        $bool = false;
        // status in akses data
        $uuid = time().$this->faker->uuid;
        // status request type
        $request_type = 5;

        if($request->leveling_create_register_status == 1) {
            //echo "PERMANENT";die;

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->leveling_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->leveling_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->leveling_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            

            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $request->leveling_location_activities;

            $access_data->status_akses      = 5;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        } elseif(($request->leveling_create_register_status == 2)) {
           
            $request->validate([
            'new_leveling_po'  => 'required|image|mimes:jpeg,png,jpg|max:550',
            ]);


            if ($request->hasFile('new_leveling_po')) {
                $image = $request->file('new_leveling_po');
                $file_name = time().$this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->po      = $path.$file_name;
            }

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->leveling_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->leveling_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->leveling_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            
            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $request->leveling_location_activities;

            $access_data->status_akses      = 1;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();            

        }


        if($bool) {
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(1,$access_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            $request->session()->flash('alert-success', 'extending access card has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);

    }
    public function post_lost_access_card_number(Request $request) {
        $request->validate([
            'lost_create_uuid'                => 'required|max:150',
            'lost_create_register_status'     => 'required|max:50',
            'lost_create_full_name'           => 'required|max:100',
            'lost_create_accesscard'          => 'required|max:150',
            'lost_document'                   => 'required|image|mimes:jpeg,png,jpg|max:550',
        ]);

        // First Validation 
        $old_data = Akses_Data::where('no_access_card',$request->lost_create_accesscard)
                        ->whereIn('status_akses',array(9,19))
                        ->where('uuid',$request->lost_create_uuid)
                        ->first();
        if(count($old_data) < 1) {
            $request->session()->flash('alert-danger', 'Access Card not Found!');
            return redirect($this->redirectTo);
        }
        // First Validation is failed

        $access_data = new Akses_Data;
        $bool = false;
        // status in akses data
        $conditional_status_akses  = 1;
        $uuid = time().$this->faker->uuid;
        // status request type
        $request_type = 4;



        if ($request->hasFile('lost_document')) {
            $image = $request->file('lost_document');
            $file_name = time().$this->faker->uuid.".".$image->getClientOriginalExtension();
            $path = "/images/akses/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $access_data->document      = $path.$file_name;
        }

        if($request->lost_create_register_status == 1) {

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->lost_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->lost_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->lost_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            

            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $old_data->floor;

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        } elseif(($request->lost_create_register_status == 2)) {
            //echo "NON PERMANENT"; die;

            $access_data->foto              = $old_data->foto;
            $access_data->po                = $old_data->po;
            $access_data->register_type     = $request->lost_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->lost_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->lost_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            

            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $old_data->floor;

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();
         

        }

        if($bool) {
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(1,$access_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            $request->session()->flash('alert-success', 'restore access card has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed restore access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);
    }
    public function post_broken_access_card_number(Request $request) {
        $request->validate([
            'broken_create_uuid'                => 'required|max:150',
            'broken_create_register_status'     => 'required|max:50',
            'broken_create_full_name'           => 'required|max:100',
            'broken_create_accesscard'          => 'required|max:150',
            'broken_document'                   => 'required|image|mimes:jpeg,png,jpg|max:550',
        ]);

        // First Validation 
        $old_data = Akses_Data::where('no_access_card',$request->broken_create_accesscard)
                        ->whereIn('status_akses',array(9,19))
                        ->where('uuid',$request->broken_create_uuid)
                        ->first();
        if(count($old_data) < 1) {
            $request->session()->flash('alert-danger', 'Access Card not Found!');
            return redirect($this->redirectTo);
        }
        // First Validation is failed

        $access_data = new Akses_Data;
        $bool = false;
        // status in akses data
        $conditional_status_akses  = 1;
        $uuid = time().$this->faker->uuid;
        // status request type
        $request_type = 3;



        if ($request->hasFile('broken_document')) {
            $image = $request->file('broken_document');
            $file_name = time().$this->faker->uuid.".".$image->getClientOriginalExtension();
            $path = "/images/akses/";
            $destinationPath = public_path($path);
            $image->move($destinationPath, $file_name);
            $access_data->document      = $path.$file_name;
        }

        if($request->broken_create_register_status == 1) {

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->broken_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->broken_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->broken_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            

            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $old_data->floor;

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        } elseif(($request->broken_create_register_status == 2)) {
            //echo "NON PERMANENT"; die;

            $access_data->foto              = $old_data->foto;
            $access_data->po                = $old_data->po;
            $access_data->register_type     = $request->broken_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->broken_create_accesscard;
            $access_data->date_start        = $old_data->date_start;
            $access_data->date_end          = $old_data->date_end;
            $access_data->additional_note   = $request->broken_additional_note;

            $access_data->request_type      = $request_type;
            
            
            $access_data->email         = $old_data->email;
            $access_data->nik           = $old_data->nik;
            $access_data->pic_list_id   = $old_data->pic_list_id;
            

            $access_data->divisi        = $old_data->divisi;
            $access_data->jabatan       = $old_data->jabatan;
            $access_data->floor         = $old_data->floor;

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();
         

        }

        if($bool) {
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(1,$access_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            $request->session()->flash('alert-success', 'restore access card has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed restore access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);

        //dd($request);
    }
    public function post_extending_access_card_number(Request $request) {
        //dd($request);

        $request->validate([
            'extend_create_uuid'                => 'required|max:150',
            'extend_create_register_status'     => 'required|max:50',
            'extend_create_full_name'           => 'required|max:100',
            'extend_create_accesscard'          => 'required|max:150',
            'new_extend_date_start'             => 'required|max:50',
            'new_extend_date_end'               => 'required|max:50',
        ]);

        // First Validation 
        $old_data = Akses_Data::where('no_access_card',$request->extend_create_accesscard)
                        ->whereIn('status_akses',array(9,19))
                        ->where('uuid',$request->extend_create_uuid)
                        ->first();
        if(count($old_data) < 1) {
            $request->session()->flash('alert-danger', 'Access Card not Found!');
            return redirect($this->redirectTo);
        }
        // First Validation is failed

        $access_data = new Akses_Data;
        $bool = false;
        // status in akses data
        $conditional_status_akses  = 1;
        $uuid = $old_data->uuid;
        // status request type
        $request_type = 2;

        if($request->extend_create_register_status == 1) {


            $old_data->date_start        = $request->new_extend_date_start;
            $old_data->date_end          = $request->new_extend_date_end;
            $old_data->additional_note   = $request->new_extend_additional_note;

            $old_data->ktp_detail   = $request->extend_create_ktp_detail;

            $old_data->request_type      = $request_type;
            $old_data->status_akses      = $conditional_status_akses;
            $old_data->updated_by        = Auth::user()->id;
            
            $bool = $old_data->save();

        } elseif(($request->extend_create_register_status == 2)) {
            //echo "NON PERMANENT"; die;
            $request->validate([
            'new_extend_po'  => 'required|image|mimes:jpeg,png,jpg|max:550',
            ]);


            if ($request->hasFile('new_extend_po')) {
                $image = $request->file('new_extend_po');
                $file_name = time().$this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $old_data->po      = $path.$file_name;
            }


            $old_data->date_start        = $request->new_extend_date_start;
            $old_data->date_end          = $request->new_extend_date_end;
            $old_data->additional_note   = $request->new_extend_additional_note;

            $old_data->request_type      = $request_type;


            $old_data->ktp_detail   = $request->extend_create_ktp_detail;
            $old_data->po_detail   = $request->extend_create_po_detail;
            
            

            $old_data->status_akses      = $conditional_status_akses;
            $old_data->updated_by        = Auth::user()->id;
            
            $bool = $old_data->save();            

        }


        if($bool) {
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(1,$old_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            $request->session()->flash('alert-success', 'extending access card has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);

    } 


    private function schedule_notification_email($from,$data) {
        $response = array(
            'error'  => true,
            'message'=> '' 
        );

        $user  = Users::find(Auth::user()->id);
        $requester = Users::find($data->created_by);

        $param = array(
            "from"              => "notification@indosatooredoo.com",
            "replyTo"           => "notification@indosatooredoo.com",
            "subject"           => "-",
            "cc_email"          => [$data->email,$requester['email']],
            "description"       => "-",
            "note"              => "-",
        );
        if($from == 1) {
            $param['subject']       = "Schedule photo for ".$data->name;
            $param['description']   = "Please come to take a photo for access card : ".
                                        $data->schedule_photo_date;
            $param['note']  = $data->schedule_photo_note;

            $user->notify(new PhotoSchedule_Notification($param));
        } else if ($from == 2) {
            $param['subject']       = "Schedule pick up access card for ".$data->name;
            $param['description']   = "Please come to pick up the access card : ".
                                        $data->pick_up_access_card_date;
            $param['note']          = $data->pick_up_access_card_note;

            $user->notify(new PickupAccessCard_Notification($param));
        } else {
            $response['message'] = "out of category scope in scheduling";
            return $response;
        }

        $response['error'] = false;
        return $response;
    }

    public function post_custome_set_photo_schedule(Request $request) {
        $request->validate([
            'modal_set_photo_schedule_date' => 'required|max:200',
            'uuid' => 'required|max:200',
        ]);

        $data = Akses_Data::where('status_akses',4)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Failed to set schedule photo!,Data is not found or status has been changed!');
            return redirect($this->redirectTo);
        } else {
            $data->schedule_photo_date = $request->modal_set_photo_schedule_date;
            $data->schedule_photo_note = $request->modal_set_photo_schedule_photo_note;
            $data->save();

            $notify_status = $this->schedule_notification_email(1,$data);
                    if($notify_status['error'] == true) {
                        $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                    }
            
            $request->session()->flash('alert-success', 'Schedule photo already sent');
            return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
        }
    }

    public function post_custome_set_pick_up_schedule(Request $request) {

        $request->validate([
            'modal_set_pick_up_schedule_pick_up_access_card_date' => 'required|max:200',
            'uuid' => 'required|max:200',
        ]);
        
        $data = Akses_Data::where('status_akses',8)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Failed to set schedule pick up!,Data is not found or status has been changed!');
            return redirect($this->redirectTo);
        } else {
            $data->pick_up_access_card_date = $request->modal_set_pick_up_schedule_pick_up_access_card_date;
            $data->pick_up_access_card_note = $request->modal_set_pick_up_schedule_pick_up_access_card_note;
            $data->save();

            $notify_status = $this->schedule_notification_email(2,$data);
                    if($notify_status['error'] == true) {
                        $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                    }

            $request->session()->flash('alert-success', 'Schedule pick up already sent');
            return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
        }
    }

    public function post_new_set_access_card_number(Request $request) {
        $request->validate([
            'accesscard_number' => 'required|max:200',
            'uuid' => 'required|max:200',
        ]);
        $data = Akses_Data::where('status_akses',4)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Set Access Card Number Failed!, Data is not found or status has been changed!');
            return redirect($this->redirectTo);
        } else {

            if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4) {

                $exist_access_card = Akses_Data::where('no_access_card',$request->accesscard_number)->first();

                if(count($exist_access_card) > 0) {
                    $request->session()->flash('alert-danger', 'Access card number '. $request->accesscard_number .' already exist in another data');
                    return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
                }

                $data->status_akses         = 5;
                $data->no_access_card       = $request->accesscard_number;
                $data->updated_by        = Auth::user()->id;
                $data->save();
                $notify = new custom_notification;
                $notify_status = $notify->set_notify(1,$data);
                    if($notify_status['error'] == true) {
                        $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                    }
                $request->session()->flash('alert-success', 'Access card number already set');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            } else {
                $request->session()->flash('alert-danger', 'set Access Number Failed,Please contact your administrator');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            }
            
        }
    }

    public function post_new_set_admin_room (Request $request) {
        $request->validate([
            'selected_admin_room' => 'required|max:200',
            'uuid' => 'required|max:200',
        ]);
        $data = Akses_Data::where('status_akses',5)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Set Admin Room Failed!,Data is not found or status has been changed!');
            return redirect($this->redirectTo);
        } else {
            if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4 || $data->request_type == 5) {
                $data->status_akses         = 6;
                $data->updated_by        = Auth::user()->id;
                $data->admin_room_list_id   = $request->selected_admin_room;
                $data->save();
                $notify = new custom_notification;
                $notify_status = $notify->set_notify(1,$data);
                    if($notify_status['error'] == true) {
                        $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                    }
                $request->session()->flash('alert-success', 'Admin Room already set');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            } else if($data->request_type == 2) {
                $data->status_akses         = 6;
                $data->updated_by        = Auth::user()->id;
                $data->admin_room_list_id   = $request->selected_admin_room;
                $data->save();
                $notify = new custom_notification;
                $notify_status = $notify->set_notify(1,$data);
                    if($notify_status['error'] == true) {
                        $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                    }
                $request->session()->flash('alert-success', 'Admin Room already set');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            } else {
                $request->session()->flash('alert-danger', 'set Admin Room Failed,Please contact your administrator');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            }
        }
    }


    public function post_extend_check_access_card_number(Request $request) {
        $response= array();
        $response['error']  = true;
        $check_exists = Akses_Data::where('no_access_card',$request->access_card_no)->count();

        if($check_exists < 1) {
            $response['message'] = "access card number is not exists!";
            return json_encode($response); 
        }

        $akses_data = Akses_Data::where('no_access_card',$request->access_card_no)
                        ->whereIn('status_akses',array(9,19))
                        //->where('status_akses',9)
                        ->first();

        if(count($akses_data) < 1) {
            $response['message'] = "access card number is not active!";
            return json_encode($response); 
        }

        $response['data']   = $akses_data;
        $response['error']  = false;
        return json_encode($response);
    }

    public function post_new_access_card(Request $request) {

        $request->validate([
            'register_id'       => 'required|max:50',
            'new_full_name'     => 'required|max:50',
            'new_email'         => 'required|max:50',
            'new_nik'           => 'required|max:50',
            'new_date_start'    => 'required|max:50',
            'new_date_end'      => 'required|max:50',
            'new_sponsor'       => 'required|max:50',
            'new_ktp'           => 'required|image|mimes:jpeg,png,jpg|max:5000',
        ]);


        $exist = Akses_Data::where('nik',$request->new_nik)
                ->whereIn('status_data',array(1,3))->count();
        if($exist > 0) {
            $request->session()->flash('alert-danger', 'NIK number has registerred by system, please check the data');
            return redirect($this->redirectTo);
        } 
        //dd($exist);


        $access_data = new Akses_Data;
        $bool = false;
        // status in akses data
        $conditional_status_akses  = 1;
        $uuid = time().$this->faker->uuid;
        // status request type
        $request_type = 1;


        if($request->register_id == 1) {
            $request->validate([
            'new_division'       => 'required|max:50',
            'new_position'       => 'required|max:50',
            ]);

            if ($request->hasFile('new_ktp')) {
                $image = $request->file('new_ktp');
                $file_name = $uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->foto      = $path.$file_name;
            }


            $access_data->request_type  = $request_type;
            $access_data->register_type = $request->register_id;
            $access_data->name          = strtolower($request->new_full_name);
            $access_data->email         = strtolower($request->new_email);
            $access_data->nik           = $request->new_nik;
            $access_data->date_start    = $request->new_date_start;
            $access_data->date_end      = $request->new_date_end;
            $access_data->pic_list_id   = $request->new_sponsor;
            $access_data->additional_note   = $request->new_additional_note;

            $access_data->divisi        = $request->new_division;
            $access_data->jabatan       = $request->new_position;

            $access_data->ktp_detail        = $request->ktp_detail;
            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        } elseif(($request->register_id == 2)) {
            $request->validate([
            'new_location_activities'   => 'required|max:100',
            'new_po'  => 'required|image|mimes:jpeg,png,jpg|max:5000',
            ]);

            if ($request->hasFile('new_ktp')) {
                $image = $request->file('new_ktp');
                $file_name = $uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->foto      = $path.$file_name;
            }

            if ($request->hasFile('new_po')) {
                $image = $request->file('new_po');
                $file_name = time().$this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->po      = $path.$file_name;
            }


            $access_data->request_type  = $request_type;
            $access_data->register_type = $request->register_id;
            $access_data->name          = strtolower($request->new_full_name);
            $access_data->email         = strtolower($request->new_email);
            $access_data->nik           = $request->new_nik;
            $access_data->date_start    = $request->new_date_start;
            $access_data->date_end      = $request->new_date_end;
            $access_data->pic_list_id   = $request->new_sponsor;
            $access_data->additional_note   = $request->new_additional_note;

            $access_data->floor             = $request->new_location_activities;

            $access_data->ktp_detail        = $request->ktp_detail;
            $access_data->po_detail        = $request->po_detail;

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        }


        if($bool) {
            $notify = new custom_notification;
            $notify_status = $notify->set_notify(1,$access_data);
                if($notify_status['error'] == true) {
                    $request->session()->flash('alert-danger','Failed to create notification = ' . $notify_status['message']);
                }
            $request->session()->flash('alert-success', 'New access has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid); 
        
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


    public function upload_access_card(Request $request) {
        $register_type = $request->register_type;
        $allow = false;
        $request->validate([
            'excel_file' => 'required'
        ]);

        if(
            $request->excel_file->getClientOriginalExtension() == 'xls' 
            ||
            $request->excel_file->getClientOriginalExtension() == 'xlsx'
          ) 
        {
            $allow = true;
        } 


        if(!$allow) {
            $request->session()->flash('alert-danger', 'Extension file must in xls or xlsx');
            return redirect($this->redirectTo);
        }

        if($request->excel_file){
            $path = Input::file('excel_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
                $reader->noHeading = true;
            })->get();

            if(count($data) > 2000) {
                $request->session()->flash('alert-danger', 'Limit upload rows maximum (2000 rows) issue!');
                return redirect($this->redirectTo);
            }


            try {
                $file_name = $request->excel_file->getClientOriginalName();
                $full_access_card_data = array();

                foreach ($data as $key => $value) {

                    if ($key <= 1) {continue;}

                    if (!isset($value[1]) || $value[1] == null) {
                        $request->session()->flash('alert-danger', "full name is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $name = $value[1]; // name

                    if (!isset($value[2]) || $value[2] == null) {
                        $request->session()->flash('alert-danger', "email is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $email = $value[2]; 

                    $nik = $value[3];
                    $ktp_detail = $value[4];
                    $po_detail = $value[5];

                    if (!isset($value[6]) || $value[6] == null) {
                        $request->session()->flash('alert-danger', "start active work is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $date_start = $this->convertDate($value[6]);

                    if (!isset($value[7]) || $value[7] == null) {
                        $request->session()->flash('alert-danger', "end active work is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $date_end = $this->convertDate($value[7]);

                    if (!isset($value[8]) || $value[8] == null) {
                        $request->session()->flash('alert-danger', "pic-sponsor is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $pic_id = $this->get_pic_id_from_name($value[8]);
                    if($pic_id == null) {
                        $request->session()->flash('alert-danger', "pic-sponsor : ". $value[8] ." is not found in database");
                        return redirect($this->redirectTo);
                    }


                    if (!isset($value[9]) || $value[9] == null) {
                        $request->session()->flash('alert-danger', "access card number is required to upload data");
                        return redirect($this->redirectTo);
                    }
                    $no_access_card = $value[9];
                    $location = $value[10];


                    $each = array(
                        'request_type'      => 1,
                        'register_type'     => $register_type,
                        'name'              => $name,
                        'email'             => $email,
                        'nik'               => $nik,
                        'pic_list_id'       => $pic_id->id,
                        'status_akses'      => 9,
                        'created_by'        =>Auth::user()->id,
                        'updated_by'        =>Auth::user()->id,
                        'no_access_card'    => $no_access_card,
                        'floor'             => $location,
                        'date_start'        => $date_start,
                        'date_end'          => $date_end,
                        'uuid'              => time().$this->faker->uuid,     
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s'),
                        'ktp_detail'        => $ktp_detail,
                        'po_detail'         => $po_detail,
                        'upload_detail'     => $file_name
                    );

                    array_push($full_access_card_data,$each);
                    // echo $date_start. "<br>";
                    // echo $value[8]." id : ". $pic_id."<br>";
                    // echo $value;die;

                }


                if(Akses_Data::insert($full_access_card_data)) {
                    $request->session()->flash('alert-success', 'upload success!');
                    return redirect($this->redirectTo);
                } else {
                    $request->session()->flash('alert-danger', 'upload failed!');
                    return redirect($this->redirectTo);
                }


                // dd($full_access_card_data);
            } catch(Exception $e) {
                $request->session()->flash('alert-danger', $e);
                return redirect($this->redirectTo);
            }
            
        } else {
            $request->session()->flash('alert-danger', 'Out of scope!');
            return redirect($this->redirectTo);
        }
    }

    private function convertDate($dateStr) {
        $time = strtotime($dateStr);
        $newformat = date('Y-m-d',$time);
        return $newformat;
    }

    private function get_pic_id_from_name($pic_name) {
        return Pic_List::where('vendor_name',$pic_name)->first();
    }
}


