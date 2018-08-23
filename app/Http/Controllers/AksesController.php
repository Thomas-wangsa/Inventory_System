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

use App\Http\Models\Divisi;
use App\Http\Models\Inventory_Data;
use App\Http\Models\Setting_Data;


use App\Mail\AksesMail;
use Illuminate\Support\Facades\Mail;

use App\Notifications\Akses_Notifications;
use Illuminate\Support\Facades\Notification;

use Faker\Factory as Faker;

class AksesController extends Controller
{	
	protected $redirectTo      = '/access';
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
    }

    public function index(Request $request) {
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


        // for add new acess 
        if(in_array($this->admin,$user_divisi)) {
            $insert_access_data = true;
        }

        if(in_array(1,$user_divisi) || in_array(3, $user_divisi)) {
            $akses_data = Akses_Data::join('status_akses','status_akses.id','=','akses_data.status_akses')
            ->leftjoin('pic_list','pic_list.id','=','akses_data.pic_list_id');
        }
        
        if($request->search == "on") {
            if($request->search_nama != null) {
                $akses_data = $akses_data->where('akses_data.name','like',$request->search_nama."%");
            } 
            else if($request->search_filter != null) {
                $akses_data = $akses_data->where('akses_data.status_akses',$request->search_filter);            
            } else if($request->search_uuid != null) {
                $akses_data = $akses_data->where('akses_data.uuid',$request->search_uuid);
            }
        }    

        $akses_data->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','pic_list.vendor_name','pic_list.vendor_detail_name');

        if($request->search_order != null) {
            $akses_data =    $akses_data->orderBy($request->search_order,'asc');
        } else {
            $akses_data =    $akses_data->orderBy('akses_data.id','DESC');
        }
        
        //dd($akses_data->paginate(5));
        
        $data = array(
            'data'         => $akses_data->paginate(5),
            'status_akses'  => Status_Akses::all(),
            'pic_list'      => Pic_List::all(),
            'faker'         => $this->faker,
            'insert_access_data'       => $insert_access_data
        );


        return view('akses/index',compact('data'));
        // dd($user_divisi);
        // $jabatan = Users_Role::where('user_id',Auth::user()->id)
        //         ->where('divisi',$this->restrict)->select('jabatan')->get()->pluck('jabatan');

        // $status_data = array();
        // foreach($jabatan as $key=>$val) {

        //     switch($val) {
        //         case 1 : 
        //             array_push($status_data,1);
        //         break;
        //         case 2 : 
        //             array_push($status_data,1,2);
        //         break;
        //         case 3 : 
        //             array_push($status_data,2,3);
        //         break;
        //         case 4 : 
        //             array_push($status_data,3,4);
        //         break;
        //         default :
        //             unset($status_data);
        //         break;
        //     }
        // } 

        // //array_push($status_data,4,5,6,7);
        // //$akses_data = Akses_Data::GetSpecific($status_data);
        // $akses_data   = Akses_Data::join('status_akses','akses_data.status_akses','=','status_akses.id')
        //     ->join('users','users.id','=','akses_data.updated_by')
        //     //->whereIn('akses_data.status_akses',$status_data)
        //     ->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','users.name AS username');
        
    }

    public function pendaftaran_akses(Request $request) {
        $access_data = new Akses_Data;
        $bool = false;
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
                $file_name = $this->faker->uuid.".".$image->getClientOriginalExtension();
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
            $access_data->status_akses = 1;
            $access_data->uuid = $this->faker->uuid;
            $bool = $access_data->save();

        } else if($request->type_daftar == "staff") {
            $request->session()->flash('alert-danger', 'On Process, Please contact your administrator');
            return redirect($this->redirectTo);
        } else {
            $request->session()->flash('alert-danger', 'Out of scope access card, Please contact your administrator');
            return redirect($this->redirectTo);
        }

        if($bool) {
            $request->session()->flash('alert-success', 'New access has been created');
        } else {
            $request->session()->flash('alert-danger', 'Failed create new access card, Please contact your administrator');
        }


        if(!$this->env == "development") {
            $new_akses = Akses_Data::where('uuid',$access_data->uuid)->first();
            $this->send($new_akses);
        }

        return redirect($this->redirectTo);
        
        
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
                    $request->session()->flash('alert-danger', 'Kartu Akses gagal daftarkan');
                    return redirect($this->redirectTo);
                }
            } else if ($data->status_akses == 3) {

                if($request->next_status == 4) {
                    $data->status_akses = $request->next_status;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Kartu Akses gagal cetak');
                    return redirect($this->redirectTo);
                }
            } else if ($data->status_akses == 4) {
                if($request->next_status == 5) {
                    $data->status_akses = $request->next_status;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Kartu Akses gagal di approve manager cetak');
                    return redirect($this->redirectTo);
                }
            } else if ($data->status_akses == 5) {
                if($request->next_status == 6) {
                    $data->status_akses = $request->next_status;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Kartu Akses gagal aktifkan');
                    return redirect($this->redirectTo);
                }
            } else if ($data->status_akses == 6) {
                if($request->next_status == 7) {
                    $data->status_akses = $request->next_status;
                    $data->status_data  = 3;
                    $data->updated_by   = Auth::user()->id;
                    $data->save();
                } else {
                    $request->session()->flash('alert-danger', 'Kartu Akses gagal approve manager Pengaktifan');
                    return redirect($this->redirectTo);
                }
            } else {
                $request->session()->flash('alert-danger', 'System Approval Error');
                return redirect($this->redirectTo);
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

        if(!$this->env == "development") {
            $new_akses = Akses_Data::where('uuid',$access_data->uuid)->first();
            $this->send($new_akses);
            $request->session()->flash('alert-success', 'Access card already approved');
        } else {
           $request->session()->flash('alert-warning', 'Development mode'); 
        }
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

                switch ($data->status_akses) {
                    case 1:
                        $data->status_akses = 8;
                        break;
                    case 2:
                        $data->status_akses = 9;
                        break;
                    case 3;
                        $data->status_akses = 10;
                        break;
                    case 4;
                        $data->status_akses = 11;
                        break;
                    case 5;
                        $data->status_akses = 12;
                        break;
                    case 6;
                        $data->status_akses = 13;
                        break;
                    default:
                        # code...
                        break;
                }
                
                $data->status_data  = 2;
                $data->updated_by   = Auth::user()->id;
                $data->comment      = $request->desc;
                $data->save();
            } else {
                $request->session()->flash('alert-danger', 'Kartu Akses gagal di Tolak');
                return redirect($this->redirectTo);
            }
            
        }

        $request->session()->flash('alert-success', 'Kartu Akses telah di tolak');
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

    public function send($new_akses){

        $indosat_path = \Request::get('indosat_path');

        $cc_email = array();
        $target_divisi = 2;

        $error = false;
        switch($new_akses->status_akses) {
            case 1 :
                $target_jabatan = 2;
                $next = 2;
                $user = Users_Role::GetAksesDecisionMaker($target_jabatan)->first();
                
                $list_email = Users_Role::join('users',
                                'users.id','=','users_role.user_id')
                                ->where('divisi',$target_divisi)
                                ->where('jabatan',$target_jabatan)
                                ->select('users.email')
                                ->distinct()
                                ->get()->pluck('email');
                $cc_email = $list_email->toArray();
                $subject = "Pendaftaran Kartu atas nama ".$new_akses->name ;
                $desc    = "baru saja mendaftarkan pengguna kartu";
                break;
            case 2 :
                $target_jabatan = 3;
                $next = 3;
                $user = Users_Role::GetAksesDecisionMaker($target_jabatan)->first();
            

                $list_email = Users_Role::join('users',
                                'users.id','=','users_role.user_id')
                                ->where('divisi',$target_divisi)
                                ->where('jabatan',$target_jabatan)
                                ->select('users.email')
                                ->distinct()
                                ->get()->pluck('email');
                $cc_email = $list_email->toArray();

                $subject = "Pencetakan Kartu atas nama ".$new_akses->name ;
                $desc    = "telah mendaftarkan kartu untuk di cetak";
                break;
            case 3 : 
                $target_jabatan = 4;
                $next = 4;
                $user = Users_Role::GetAksesDecisionMaker($target_jabatan)->first();
                
                $list_email = Users_Role::join('users',
                                'users.id','=','users_role.user_id')
                                ->where('divisi',$target_divisi)
                                ->where('jabatan',$target_jabatan)
                                ->select('users.email')
                                ->distinct()
                                ->get()->pluck('email');
                $cc_email = $list_email->toArray();

                $subject = "Pengaktifan Kartu atas nama ".$new_akses->name ;
                $desc    = "baru saja mencetakan kartu untuk di aktifkan";
                break;
            default : 
                $error = true;
                break;
        }

        $created_by = Users::where('id','=',$new_akses->created_by)
                    ->select('email')
                    ->first();

        if(!in_array($created_by->email,$cc_email)) {
            array_push($cc_email,$created_by->email);
        }
        
        $attachment =  "";
        if($new_akses->type == 'self' || $new_akses->type == 'staff') {
            $attachment = $new_akses->foto;
        } else if ($new_akses->type == 'vendor') {
            $attachment = $new_akses->po;
        } 


        if(count($user) < 1) {
            $error = true;            
        }

        if(!$error) {
            $data = array(
                "subject"   => $subject,
                "head"      => $user->name,
                "staff"     => Users::find($new_akses->updated_by)->name,
                "desc"      => $desc,
                "nama_user" => $new_akses->name,
                "email"     => $new_akses->email,
                "comment"    => $new_akses->comment,
                "uuid"      =>  $new_akses->uuid.'&next_status='.$next,
                "cc_email" => $cc_email,
                "attachment" => $attachment      
            );

            //dd($data);
            $user->notify(new Akses_Notifications($data));
        } 
        
    }
}
