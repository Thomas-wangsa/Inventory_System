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
use App\Http\Models\Status_Akses;
use App\Http\Models\Akses_Role;
use App\Http\Models\Notification AS notify;


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

class AksesController extends Controller
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
        $conditional_union = false;
        $insert_access_data = false;
        $restrict_divisi_pic = 2;
        $restrict_divisi_access = 3;
        $user_divisi = \Request::get('user_divisi');
        $allow = false;
        if(
            in_array($this->admin,$user_divisi)
            ||
            in_array($restrict_divisi_pic,$user_divisi)
            || 
            in_array($restrict_divisi_access,$user_divisi) 
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Sorry you dont have authority to access page');
            return redirect('home');
        }


        $level_authority     = array();
        $level_authority['divisi'] = array();
        $level_authority['access'] = array();
        $level_authority['pic']    = array();
        // for add new acess 
        if(in_array($this->admin,$user_divisi)) {
            //echo "only admin";
            $level_authority['divisi'] = Divisi::whereIn('id',array(2,3))
                        ->get();
            $level_authority['access'] = Akses_Role::where('id',1)
                        ->get();
            $level_authority['pic']    = Pic_List::select('pic_list.id',
                        'pic_list.vendor_name','pic_list.vendor_detail_name',
                        DB::raw('
                                (SELECT pic_level_name FROM pic_level WHERE id = 1 LIMIT 1) as pic_level_name'
                            )
                        )
                        ->get(); 

            $insert_access_data = true;
        } else if( in_array($restrict_divisi_access,$user_divisi) && in_array($restrict_divisi_pic,$user_divisi) ) {

            //echo "pic & akses active";
            $level_authority['divisi'] = Divisi::whereIn('id',array(2,3))
                        ->get();
            $level_authority['access'] = Akses_Role::where('id',1)
                        ->get();
            $level_authority['pic']    = Users_Role::join('pic_role',
                                'pic_role.id','=','users_role.jabatan')
                                ->join('pic_list',
                                'pic_list.id','=','pic_role.pic_list_id')
                                ->join('pic_level',
                                'pic_level.id','=','pic_role.pic_level_id')
                                ->where('users_role.divisi','=','2')
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('pic_role.user_id','=',Auth::user()->id)
                                ->where('pic_role.pic_level_id','=','1')
                                ->select('pic_list.id',
                                'pic_list.vendor_name','pic_list.vendor_detail_name',
                                    DB::raw('
                                        (SELECT pic_level_name 
                                        FROM pic_level 
                                        WHERE id = 1 LIMIT 1
                                        ) as pic_level_name'
                                    )
                                )
                                ->get();
            $insert_access_data = true;
        } else if(in_array($restrict_divisi_access,$user_divisi) && !in_array($restrict_divisi_pic,$user_divisi)) {
            //echo "only akses";
            $level_authority['divisi'] = Divisi::whereIn('id',array(3))
                        ->get();
            $level_authority['access'] = Akses_Role::where('id',1)
                        ->get();

            $exist_count = Users_Role::where('user_id',Auth::user()->id)
                            ->where('divisi',$restrict_divisi_access)
                            ->where('jabatan',1)
                            ->count();
            if($exist_count > 0) {
                $insert_access_data = true;
            }
        } else if(!in_array($restrict_divisi_access,$user_divisi) && in_array($restrict_divisi_pic,$user_divisi)) {
            //echo "only pic";
            $level_authority['divisi'] = Divisi::whereIn('id',array(2))
                        ->get();
            $level_authority['pic']    = Users_Role::join('pic_role',
                                'pic_role.id','=','users_role.jabatan')
                                ->join('pic_list',
                                'pic_list.id','=','pic_role.pic_list_id')
                                ->join('pic_level',
                                'pic_level.id','=','pic_role.pic_level_id')
                                ->where('users_role.divisi','=','2')
                                ->where('users_role.user_id','=',Auth::user()->id)
                                ->where('pic_role.user_id','=',Auth::user()->id)
                                ->where('pic_role.pic_level_id','=','1')
                                ->select('pic_list.id',
                                'pic_list.vendor_name','pic_list.vendor_detail_name',
                                    DB::raw('
                                        (SELECT pic_level_name 
                                        FROM pic_level 
                                        WHERE id = 1 LIMIT 1
                                        ) as pic_level_name'
                                    )
                                )
                                ->get();

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
        


        $staff_pendaftaran_data     = false;
        $staff_pencetakan_data      = false;
        $manager_pencetakan_data    = false;
        $staff_pengaktifan_data     = false;
        $manager_pengaktifan_data   = false;

        if(in_array($restrict_divisi_access,$user_divisi)) {

            $access_role_array = Users_Role::where('user_id',Auth::user()->id)
                            ->where('divisi',$restrict_divisi_access)
                            ->pluck('jabatan')->toArray();

            if(in_array(1,$access_role_array)) {
                $staff_pendaftaran_data = true;
            }

            if(in_array(2,$access_role_array)) {
                $staff_pencetakan_data = true;
            } 

            if(in_array(3,$access_role_array)) {
                $manager_pencetakan_data = true;
            } 

            if(in_array(4,$access_role_array)) {
                $staff_pengaktifan_data = true;
            }

            if(in_array(5,$access_role_array)) {
                $manager_pengaktifan_data = true;
            } 


        }
        // Staf Pendaftaran
        
    


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


        // if($conditional_union) {
        //     $union = Akses_Data::join('status_akses'
        //         ,'status_akses.id','=','akses_data.status_akses')
        //     ->join('users','users.id','=','akses_data.created_by')
        //     ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id')
        //     ->where('created_by',Auth::user()->id);
        //     dd($akses_data->get());
        //     dd($union->get());
        //     $akses_data = $akses_data->union($union);
        //     dd($akses_data->get());
        // }
        
        if($request->search == "on") {
            if($request->search_nama != null) {
                $akses_data = $akses_data->where('akses_data.name','like','%'.$request->search_nama."%");
            } 
            
            if($request->search_filter != null) {
                $akses_data = $akses_data->where('akses_data.status_akses',$request->search_filter);            
            } 

            if($request->search_uuid != null) {
                $akses_data = $akses_data->where('akses_data.uuid',$request->search_uuid);
            }
        } else {

            if(in_array($this->admin,$user_divisi)) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[1,2,3,4,5,6]);    
            } else if(in_array($restrict_divisi_access, $user_divisi)) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[2,3,4,5,6]);
            } else if(in_array($restrict_divisi_pic,$user_divisi)) {
                $akses_data = $akses_data->whereIn('akses_data.status_akses',[1]);
            } 
            
        }   

        $akses_data->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','pic_list.vendor_name','pic_list.vendor_detail_name','users.name AS created_by_name');

        if($request->search_order != null) {
            $akses_data =    $akses_data->orderBy($request->search_order,'asc');
        } else {
            $akses_data =    $akses_data->orderBy('akses_data.id','DESC');
        }
        
        $final_akses_data = $akses_data->paginate(5);

        $conditional_sponsor = array();
        foreach($final_akses_data as $key=>$val) {
            $is_data_sponsor = false;
            if(in_array($val->pic_list_id,$sponsor_access_data)) {
                $is_data_sponsor = true;
            }
            array_push($conditional_sponsor,$is_data_sponsor);
            //echo $val->pic_list_id;
        }

        // dd($pic_list_dropdown);
        //dd($akses_data->paginate(5));


        $data = array(
            'data'         => $final_akses_data,
            'status_akses'  => Status_Akses::all(),
            'pic_list'      => $pic_list_dropdown,
            'faker'         => $this->faker,
            'level_authority'          => $level_authority,
            'insert_access_data'       => $insert_access_data,
            'sponsor_access_data'      => $conditional_sponsor,
            'staff_pendaftaran_data'   => $staff_pendaftaran_data,
            'staff_pencetakan_data'   => $staff_pencetakan_data,
            'manager_pencetakan_data'   => $manager_pencetakan_data,
            'staff_pengaktifan_data'   => $staff_pengaktifan_data,
            'manager_pengaktifan_data'   => $manager_pengaktifan_data
        );

        //dd($data);
        return view('akses/index',compact('data'));
        
    }

    public function pendaftaran_akses(Request $request) {
        $access_data = new Akses_Data;
        $bool = false;
        $conditional_status_akses  = 1;
        $uuid = time().$this->faker->uuid;
        if($request->type_daftar == "vendor") {
            $request->validate([
                'po' => 'required|image|mimes:jpeg,png,jpg|max:550',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:550',
                'name' => 'required|max:50',
                'email' => 'required|max:50',
                'nik' => 'required|max:50',

            ]);

            $count_pic_list_id = Pic_List::where('id',$request->pic_list_id)
                                ->count();
            if($count_pic_list_id < 1) {
                $request->session()->flash('alert-danger', 'Pic Category is not found');
                 return redirect($this->redirectTo);
            }

            if($request->date_end <= $request->date_start) {
                $request->session()->flash('alert-danger', 'End Active Access Card must after Start Active Access Card');
                return redirect($this->redirectTo);
            }

            
            if ($request->hasFile('po')) {
                $image = $request->file('po');
                $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->po = $path.$file_name;
            }

            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $file_name = $uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->foto = $path.$file_name;
            }

            $access_data->type_daftar = $request->type_daftar;
            $access_data->name = $request->name;
            $access_data->email = strtolower($request->email);
            $access_data->nik = $request->nik;
            $access_data->pic_list_id = $request->pic_list_id;
            $access_data->no_access_card = $request->no_access_card;
            $access_data->date_start = $request->date_start;
            $access_data->date_end = $request->date_end;
            $access_data->floor = $request->floor;
            $access_data->additional_note = $request->additional_note;
            $access_data->created_by = Auth::user()->id;
            $access_data->updated_by = Auth::user()->id;
            $access_data->status_akses = $conditional_status_akses;
            $access_data->uuid = $uuid;
            $bool = $access_data->save();

        } else if($request->type_daftar == "staff") {

            $request->validate([
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:550',
                'name' => 'required|max:50',
                'email' => 'required|max:50',
                'nik' => 'required|max:50',

            ]);

            if($request->date_end <= $request->date_start) {
                $request->session()->flash('alert-danger', 'End Active Access Card must after Start Active Access Card');
                return redirect($this->redirectTo);
            }


            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $file_name = $uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $access_data->foto = $path.$file_name;
            }

            $conditional_pic_list_id = null;
            if($request->level_authority == 3 
                && 
                $request->access_level_authority == 1
            ) {
                $conditional_status_akses = 3;
            } else if ($request->level_authority == 2) {
                $conditional_pic_list_id = $request->pic_level_authority;
            }

            
            $access_data->type_daftar = $request->type_daftar;
            $access_data->name = $request->name;
            $access_data->email = strtolower($request->email);
            $access_data->nik = $request->nik;
            $access_data->no_access_card = $request->no_access_card;
            $access_data->date_start = $request->date_start;
            $access_data->date_end = $request->date_end;
            $access_data->divisi   = $request->divisi;
            $access_data->jabatan  = $request->jabatan;
            $access_data->additional_note = $request->additional_note;
            $access_data->created_by = Auth::user()->id;
            $access_data->updated_by = Auth::user()->id;
            $access_data->status_akses = $conditional_status_akses;
            $access_data->uuid = $uuid;
            $access_data->pic_list_id = $conditional_pic_list_id;
            $bool = $access_data->save();

        } else {
            $request->session()->flash('alert-danger', 'Out of scope access card, Please contact your administrator');
            return redirect($this->redirectTo);
        }

        if($bool) {
            $request->session()->flash('alert-success', 'New access has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }

        $this->notify($conditional_status_akses,$uuid);
        return redirect($this->redirectTo."?search=on&search_uuid=".$uuid);   
    }


    public function notify($status_akses,$uuid) {
        // Could be improve
        $akses_data = Akses_Data::where('status_akses',$status_akses)
                    ->where('uuid',$uuid)->first();
        if(count($akses_data) < 1) {
            $request->session()->flash('alert-danger', 'Failed create Notification card, Please contact your administrator');
            return redirect($this->redirectTo);
        } else {
            $notify = array();

            $user_updated     = Auth::user()->id;
            array_push($notify,$user_updated);

            $user_created = $akses_data->created_by;
            if(!in_array($user_created,$notify)) {
                array_push($notify,$user_created);
            }

            $next_user   = array();
            switch($status_akses) {
                case "1" :
                    $next_user = Users_Role::join('pic_role',
                        'pic_role.id','=','users_role.jabatan')
                        ->where('users_role.divisi','=',2)
                        ->where('pic_list_id',$akses_data->pic_list_id)
                        ->where('pic_level_id',2)
                        ->pluck('pic_role.user_id');
                    break;
                case "2" :
                    $next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',1)
                    ->pluck('user_id');
                    break;
                case "3" :
                    $next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',2)
                    ->pluck('user_id');
                    break;
                case "4" :
                    $next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',3)
                    ->pluck('user_id');
                    break;
                case "5":
                    $next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',4)
                    ->pluck('user_id');
                    break;
                case "6" :
                    $next_user = Users_Role::where('divisi',3)
                    ->where('jabatan',5)
                    ->pluck('user_id');
                    break;
                default :
                    break;
            } 

            foreach($next_user as $key=>$val) {
                if(!in_array($val,$notify)) {
                    array_push($notify,$val);
                }
            }

            
            
            $notify_status = 2;
            $notify_category = 3;
            if($status_akses == 1) {
                $notify_status = 1;
                $notify_category = 2;
            }

            foreach($notify as $key=>$val) {
                $data_notify = array(
                'user_id'           => $val,
                'category'          => $notify_category,
                'data_id'     => $akses_data->id,
                'status_data_id'   => $status_akses,
                'sub_notify_id'     => $notify_status,
                );

                notify::firstOrCreate($data_notify);
            }

            if($this->env != "development") {
                $data['notify_user']        = $notify;
                $data['access_card_data']   = $akses_data;
                $this->send($data);
            }
        }
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




    public function akses_approval(Request $request) {
        $data = Akses_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'there is no access card found');
            return redirect($this->redirectTo);
        } else {
            if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4) {
                if($data->status_akses == 1) {
                    if($request->next_status == 2) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'Error: approved by sponsor is error');
                        return redirect($this->redirectTo);
                    }

                } else if ($data->status_akses == 2) {

                    if($request->next_status == 3) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'verification failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 3) {

                    if($request->next_status == 4) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'approval verification failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 4) {
                    if($request->next_status == 5) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'card_printing failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 5) {
                    if($request->next_status == 6) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else if($request->next_status == 7) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'approval activation failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 6) {
                    if($request->next_status == 7) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'admin room failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 7) {
                    if($request->next_status == 8) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'activation failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 8) {
                    if($request->next_status == 9) {
                        $data->status_akses = $request->next_status;
                        $data->status_data  = 3;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'pick up card failed');
                        return redirect($this->redirectTo);
                    }
                } else {
                    $request->session()->flash('alert-danger', 'System Approval Error');
                    return redirect($this->redirectTo);
                }
            } else if($data->request_type == 2)  {
                if($data->status_akses == 1) {
                    if($request->next_status == 2) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'Error: approved by sponsor is error');
                        return redirect($this->redirectTo);
                    }

                } else if ($data->status_akses == 2) {

                    if($request->next_status == 3) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'verification failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 3) {

                    if($request->next_status == 4) {
                        $data->status_akses = 5;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'approval verification failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 5) {
                    if($request->next_status == 6) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else if($request->next_status == 7) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'approval activation failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 6) {
                    if($request->next_status == 7) {
                        $data->status_akses = $request->next_status;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'admin room failed');
                        return redirect($this->redirectTo);
                    }
                } else if ($data->status_akses == 7) {
                    if($request->next_status == 8) {
                        $data->status_akses = 9;
                        $data->updated_by   = Auth::user()->id;
                        $data->save();
                    } else {
                        $request->session()->flash('alert-danger', 'admin room failed');
                        return redirect($this->redirectTo);
                    }
                } else {
                    $request->session()->flash('alert-danger', 'Out of scope in extending');
                    return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
                }
            } else if($data->request_type == 5)  {              
                if($data->register_type == 1) {
                    if ($data->status_akses == 5) {
                        if($request->next_status == 6) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else if($request->next_status == 7) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'approval activation failed');
                            return redirect($this->redirectTo);
                        }
                    } else if ($data->status_akses == 6) {
                        if($request->next_status == 7) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'admin room failed');
                            return redirect($this->redirectTo);
                        }
                    } else if ($data->status_akses == 7) {
                        if($request->next_status == 9) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'activation failed');
                            return redirect($this->redirectTo);
                        }
                    } else {
                        $request->session()->flash('alert-danger', 'Out of scope in leveling type permanent');
                        return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
                    }
                } elseif($data->register_type == 2) {
                    if($data->status_akses == 1) {
                        if($request->next_status == 5) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'Error: approved by sponsor is error');
                            return redirect($this->redirectTo);
                        }
                    } else if ($data->status_akses == 5) {
                        if($request->next_status == 6) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else if($request->next_status == 7) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'approval activation failed');
                            return redirect($this->redirectTo);
                        }
                    } else if ($data->status_akses == 6) {
                        if($request->next_status == 7) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'admin room failed');
                            return redirect($this->redirectTo);
                        }
                    } else if ($data->status_akses == 7) {
                        if($request->next_status == 9) {
                            $data->status_akses = $request->next_status;
                            $data->updated_by   = Auth::user()->id;
                            $data->save();
                        } else {
                            $request->session()->flash('alert-danger', 'activation failed');
                            return redirect($this->redirectTo);
                        }
                    } else {
                        $request->session()->flash('alert-danger', 'Out of scope in leveling type non permanent');
                        return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
                    }
                } else {
                    $request->session()->flash('alert-danger', 'Out of scope in leveling');
                    return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
                }

            } else  {
                $request->session()->flash('alert-danger', 'Out of scope');
                return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
            }


            // if($data->status_akses <= 3) {

            //     switch ($data->status_akses) {
            //         case 1:
            //             $data->status_akses = 2;
            //             break;
            //         case 2:
            //             $data->status_akses = 3;
            //             break;
            //         case 3;
            //             $data->status_akses = 4;
            //             $data->status_data  = 3;
            //         default:
            //             # code...
            //             break;
            //     }
                
            //     $data->updated_by   = $this->credentials->id;
            //     $data->save();
            // } else {
            //     return redirect($this->redirectTo);
            // }
            
        }


        //$this->notify($data->status_akses,$request->uuid);
        $request->session()->flash('alert-success', 'Access card already approved');
        return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
        //return view('akses/approval');
    }


    public function akses_reject(Request $request) {
        $data = Akses_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();

        if(count($data) < 1) {
            $request->session()->flash('alert-danger', 'Data Kosong');
            return redirect($this->redirectTo);
        } 

        return view('akses/reject',compact('data'));
    }


    public function proses_reject(Request $request) {
        $data = Akses_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            if($data->status_data == 1 || $data->status_data == 2) {
                if($data->request_type == 1 || $data->request_type == 3 || $data->request_type == 4) {
                    switch ($data->status_akses) {
                        case 1:
                            $data->status_akses = 10;
                            break;
                        case 2:
                            $data->status_akses = 11;
                            break;
                        case 3;
                            $data->status_akses = 12;
                            break;
                        case 4;
                            $data->status_akses = 13;
                            break;
                        case 5;

                            $data->status_akses = 14;
                            break;
                        case 6;
                            $data->status_akses = 15;
                            break;
                        case 7;
                            $data->status_akses = 16;
                            break;
                        case 8;
                            $data->status_akses = 17;
                            break;
                        default:
                            # code...
                            break;
                    }
                
                    $data->status_data  = 2;

                    if($data->status_akses == 14 || $data->status_akses == 16) {
                        $data->status_akses = 4;
                        $data->status_data  = 1;
                    }

                    $data->updated_by   = Auth::user()->id;
                    $data->additional_note = $data->additional_note."<br/> <br/>".$request->desc;
                    $data->comment      = $request->desc;
                    $data->save();
                } else if ($data->request_type == 2) {
                    switch ($data->status_akses) {
                        case 1:
                            $data->status_akses = 10;
                            break;
                        case 2:
                            $data->status_akses = 11;
                            break;
                        case 3;
                            $data->status_akses = 12;
                            break;
                        case 4;
                            $data->status_akses = 13;
                            break;
                        case 5;

                            $data->status_akses = 14;
                            break;
                        case 6;
                            $data->status_akses = 15;
                            break;
                        case 7;
                            $data->status_akses = 16;
                            break;
                        case 8;
                            $data->status_akses = 17;
                            break;
                        default:
                            # code...
                            break;
                    }
                
                    $data->status_data  = 2;

                    if($data->status_akses == 14 || $data->status_akses == 16) {
                        $data->status_akses = 2;
                        $data->status_data  = 1;
                    }

                    $data->updated_by   = Auth::user()->id;
                    $data->additional_note = $data->additional_note."<br/> <br/>".$request->desc;
                    $data->comment      = $request->desc;
                    $data->save();
                } else if ($data->request_type == 5) { 
                    switch ($data->status_akses) {
                        case 1:
                            $data->status_akses = 10;
                            break;
                        case 2:
                            $data->status_akses = 11;
                            break;
                        case 3;
                            $data->status_akses = 12;
                            break;
                        case 4;
                            $data->status_akses = 13;
                            break;
                        case 5;
                            $data->status_akses = 14;
                            break;
                        case 6;
                            $data->status_akses = 15;
                            break;
                        case 7;
                            $data->status_akses = 16;
                            break;
                        case 8;
                            $data->status_akses = 17;
                            break;
                        default:
                            # code...
                            break;
                    }
                
                    $data->status_data  = 2;

                    $data->updated_by   = Auth::user()->id;
                    $data->additional_note = $data->additional_note."<br/> <br/>".$request->desc;
                    $data->comment      = $request->desc;
                    $data->save();
                }else {
                   $request->session()->flash('alert-danger', 'Out of scope');
                    return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid); 
                }
            } else {
                $request->session()->flash('alert-danger', 'Failed to reject access card!');
                return redirect($this->redirectTo);
            }
            
        }
        //$this->notify($data->status_akses,$request->uuid);
        $request->session()->flash('alert-success', 'access card has been rejected');
        return redirect($this->redirectTo."?search=on&search_uuid=".$request->uuid);
    }

    public function pendaftaran_akses_bck(Request $request) {
        

            	
        $akses_data = new Akses_Data;
    	if($request->type_daftar == "staff") {

            $request->validate([
            'staff_foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:550',
            ]);
            if ($request->hasFile('staff_foto')) {
                $image = $request->file('staff_foto');
                $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $akses_data->foto  = $path.$file_name;
            }

            $akses_data->type   = $request->type_daftar;
            $akses_data->name   = strtolower($request->staff_nama);
            $akses_data->email  = strtolower($request->staff_email);            
            $akses_data->no_card  = strtolower($request->staff_no_card);
            $akses_data->comment  = $request->staff_note;
            
    	} elseif($request->type_daftar == "vendor") {
    		//dd($request);
    		
    		$request->validate([
                'po' => 'required|image|mimes:jpeg,png,jpg|max:550',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:550',
            ]);

            $akses_data->type = $request->type_daftar;
            $akses_data->name = strtolower($request->vendor_nama);
            $akses_data->email = strtolower($request->vendor_email);
            $akses_data->date_start = $request->start_card;
            $akses_data->date_end = $request->end_card;
            $akses_data->floor = strtolower($request->floor);
            $akses_data->comment = $request->pekerjaan;

            if ($request->hasFile('po')) {
                $image = $request->file('po');
                $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $akses_data->po = $path.$file_name;
            }

            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $akses_data->foto = $path.$file_name;
            }
    		
    	} else {
            $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur ini');
            return redirect($this->redirectTo);
    	}
    		// dd($request);
        $akses_data->created_by         = Auth::user()->id;
        $akses_data->updated_by         = Auth::user()->id;
    	$akses_data->status_akses       = 2;
        $akses_data->uuid               = $this->faker->uuid;
        $bool = $akses_data->save();

        if($bool) {
            $request->session()->flash('alert-success', 'Akses telah di daftarkan');
            //$this->send($akses_data);
        } else {
            $request->session()->flash('alert-danger', 'Data tidak masuk, Please contact your administrator');
        }
        
        if($this->send_email_control) {

            $new_akses = Akses_Data::where('uuid',$akses_data->uuid)->first();
            $this->send($new_akses);
        }

    	return redirect($this->redirectTo);
    }

    public function pendaftaran_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$this->credentials->id,
            "status_akses"  => 2
        ]);
        return redirect($this->redirectTo);
    }

    public function pencetakan_akses(Request $request) {
        $akses_data = Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 3
        ]);

        if($akses_data) {
            $request->session()->flash('alert-success', 'Akses sukses di daftarkan untuk cetak');
            $this->send(Akses_Data::find($request->data_id));
            return redirect($this->redirectTo);
        } else {
            $request->session()->flash('alert-danger', 'Akses gagal di daftarkan untuk cetak');
            return redirect($this->redirectTo);
        }
        
    }

    public function pencetakan_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 4
        ]);
        return redirect($this->redirectTo);
    }

    public function aktifkan_akses(Request $request) {
        $akses_data = Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 5
        ]);

        if($akses_data) {
            $request->session()->flash('alert-success', 'Akses sukses di daftarkan untuk cetak');
            $this->send(Akses_Data::find($request->data_id));
            return redirect($this->redirectTo);
        } else {
            $request->session()->flash('alert-danger', 'Akses gagal di daftarkan untuk cetak');
            return redirect($this->redirectTo);
        }
    }

    public function aktifkan_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 6
        ]);
        return redirect($this->redirectTo);
    }

    public function send($data){
        $cc_email = array();
        $new_akses  = $data['access_card_data'];

        $status_akses = Status_Akses::find($new_akses->status_akses);

        $subject    = "(".$status_akses->name.")"." Access Card For ".$data['access_card_data']->name;
        $user  = Users::find($data['notify_user'][0]);
        foreach($data['notify_user'] as $key=>$val) {
            if($key != 0) {
                $user_email = Users::find($val)->email;
                array_push($cc_email,$user_email);
            }
        }


        $desc_1    = "Access card has been updated by ";
        $desc_name = $user->name;
        $desc_2    = " with the following information : ";
        // $target_divisi = 2;

        $error = false;



        if(count($user) < 1) {
            $error = true;            
        }

        if(!$error) {
            $data = array(
                "subject"           => $subject,
                "cc_email"          => $cc_email,
                "desc_1"            => $desc_1,
                "desc_name"         => $desc_name,
                "desc_2"            => $desc_2,
                "access_card_name"  => $new_akses->name,
                "access_card_no"    => $new_akses->no_access_card,
                "status_akses"      => $status_akses->name,
                "status_color"      => $status_akses->color,
                "uuid"              =>  $new_akses->uuid,
                     
            );

            // dd($data);
            $user->notify(new Akses_Notifications($data));
        } 
        
    }

    public function akses_get_info(Request $request) {
        $response['status'] = false;

        $akses_data = Akses_Data::leftjoin('pic_list',
                        'pic_list.id','=','akses_data.pic_list_id')
                        ->join('status_akses',
                        'status_akses.id','=','akses_data.status_akses')
                        ->join('users as c_user',
                        'c_user.id','=','akses_data.created_by')
                        ->join('users as u_user',
                        'u_user.id','=','akses_data.updated_by')
                        ->join('access_card_register_status',
                        'access_card_register_status.id','=','akses_data.register_type')
                        ->join('access_card_request',
                        'access_card_request.id','=','akses_data.request_type')
                        ->leftjoin('admin_room_list',
                        'admin_room_list.id','=','akses_data.admin_room_list_id')
                        ->where('akses_data.status_akses',$request->status)
                        ->where('akses_data.uuid',$request->uuid)
                        ->select('akses_data.*',
                            'pic_list.vendor_name AS pic_list_vendor_name',
                            'pic_list.vendor_detail_name AS pic_list_vendor_detail_name',
                            'status_akses.name AS status_name',
                            'status_akses.color AS status_color',
                            'c_user.name AS created_by_username',
                            'u_user.name AS updated_by_username',
                            'access_card_register_status.register_name AS register_name',
                            'access_card_request.request_name AS request_name',
                            'admin_room_list.admin_room AS admin_room_name')
                        ->first();

        if(count($akses_data) < 1) {
            $response['message'] = "credentials not found";
        } else {
            $response['status'] = true;
            $response['data'] = $akses_data;
        }

        return json_encode($response);
    }

    public function update_access_card(Request $request) {
        $request->validate([
                'uuid' => 'required',
                'type_daftar' => 'required',
                'name'  => 'required',
                'email' => 'required',
                'nik'   => 'required',
                'date_start'=> 'required',
                'date_end'  => 'required',
        ]);

        $akses_data = Akses_Data::where('uuid',$request->uuid)->first();

        if(count($akses_data) < 1) {
            $request->session()->flash('alert-danger', 'credentials not found');
            return redirect($this->redirectTo);
        }

        if($request->type_daftar == "vendor") {
            $akses_data->floor    = $request->floor;
        } else if($request->type_daftar == "staff") {
            $akses_data->divisi  = $request->divisi;
            $akses_data->jabatan = $request->jabatan; 
        }
        $akses_data->name           = $request->name;
        $akses_data->email          = $request->email;
        $akses_data->nik            = $request->nik;
        $akses_data->no_access_card = $request->no_access_card;
        $akses_data->date_start     = $request->date_start;
        $akses_data->date_end       = $request->date_end;
        $akses_data->additional_note       = $request->additional_note;
        $akses_data->updated_by     = Auth::user()->id;


        $bool = $akses_data->save();

        if(!$bool) {
            $request->session()->flash('alert-danger', 'Update Failed');
        }

        $request->session()->flash('alert-success', 'Update Success');
        return redirect($this->redirectTo."?search=on&search_uuid=".$akses_data->uuid);

    }

    public function deactivated_access_card(Request $request) {
        $request->validate([
            'uuid' => 'required',
            'no_access_card' => 'required',
            'note' => 'required',
        ]);

        $akses_data = Akses_Data::where('uuid',$request->uuid)
                ->where('status_akses',9)
                ->first();

        if(count($akses_data) < 1) {
            $request->session()->flash('alert-danger', 'credentials not found');
            return redirect($this->redirectTo);
        }

        $akses_data->status_akses = 18;
        $akses_data->status_data = 4;
        $akses_data->comment = $request->note;
        $bool = $akses_data->save();

        if(!$bool) {
            $request->session()->flash('alert-danger', 'Update Failed');
        }
        $request->session()->flash('alert-success', 'Update Success');
        return redirect($this->redirectTo."?search=on&search_uuid=".$akses_data->uuid);
        //dd($request);
    }
}
