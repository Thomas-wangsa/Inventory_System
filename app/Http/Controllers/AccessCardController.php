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


        //dd($akses_data->count());
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

            if($request->search_uuid != null) {
                $akses_data = $akses_data->where('akses_data.uuid',$request->search_uuid);
            }
        } else {

            if(in_array($this->admin,$user_divisi)) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[1,2,3,4,5,6,7,8]);    
            } else if(in_array($restrict_divisi_access, $user_divisi) 
                && count($user_divisi) == 1) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[2,3,4,5,6,7,8]);
            } else if(in_array($restrict_divisi_pic,$user_divisi) 
                    && count($user_divisi) == 1) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[1]);
            } 
            
        }
        //dd($akses_data->count());

        $akses_data->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','pic_list.vendor_name','pic_list.vendor_detail_name','users.name AS created_by_name','access_card_register_status.register_name AS register_name','access_card_request.request_name AS request_name');

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
                        ->where('status_akses',9)
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
                        ->where('status_akses',9)
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
                        ->where('status_akses',9)
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
        $uuid = time().$this->faker->uuid;
        // status request type
        $request_type = 2;

        if($request->extend_create_register_status == 1) {

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->extend_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->extend_create_accesscard;
            $access_data->date_start        = $request->new_extend_date_start;
            $access_data->date_end          = $request->new_extend_date_end;
            $access_data->additional_note   = $request->new_extend_additional_note;

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
                $access_data->po      = $path.$file_name;
            }

            $access_data->foto              = $old_data->foto;

            $access_data->register_type     = $request->extend_create_register_status;
            $access_data->name              = $old_data->name;
            $access_data->no_access_card    = $request->extend_create_accesscard;
            $access_data->date_start        = $request->new_extend_date_start;
            $access_data->date_end          = $request->new_extend_date_end;
            $access_data->additional_note   = $request->new_extend_additional_note;

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
            $request->session()->flash('alert-success', 'extending access card has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);

    } 

    public function post_new_set_access_card_number(Request $request) {
        $data = Akses_Data::where('status_akses',4)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Set Access Card Number Failed!, Data Not Found!');
            return redirect($this->redirectTo);
        } else {

            if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4) {
                $data->status_akses         = 5;
                $data->no_access_card       = $request->accesscard_number;
                $data->save();
                $request->session()->flash('alert-success', 'Access card number already set');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            } else {
                $request->session()->flash('alert-danger', 'set Access Number Failed,Please contact your administrator');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            }
            
        }
    }

    public function post_new_set_admin_room (Request $request) {
        $data = Akses_Data::where('status_akses',5)
                    ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Set Admin Room Failed!, Data Not Found!');
            return redirect($this->redirectTo);
        } else {
            if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4 || $data->request_type == 5) {
                $data->status_akses         = 6;
                $data->admin_room_list_id   = $request->selected_admin_room;
                $data->save();
                $request->session()->flash('alert-success', 'Admin Room already set');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            } else if($data->request_type == 2) {
                $data->status_akses         = 6;
                $data->admin_room_list_id   = $request->selected_admin_room;
                $data->save();
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
                        ->where('status_akses',9)
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
            'new_ktp'           => 'required|image|mimes:jpeg,png,jpg|max:550',
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

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        } elseif(($request->register_id == 2)) {
            $request->validate([
            'new_location_activities'   => 'required|max:100',
            'new_po'  => 'required|image|mimes:jpeg,png,jpg|max:550',
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

            $access_data->status_akses      = $conditional_status_akses;
            $access_data->uuid              = $uuid;
            $access_data->created_by        = Auth::user()->id;
            $access_data->updated_by        = Auth::user()->id;
            
            $bool = $access_data->save();

        }


        if($bool) {
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
}


