<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Akses_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
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
	protected $redirectTo      = '/akses';
    protected $url;
    protected $credentials;
    protected $faker;
    protected $restrict = 2;
    protected $admin    = 1;

    public function __construct(UrlGenerator $url){
        // $this->url      = $url;
        $this->faker    = Faker::create();

        // $this->middleware(function ($request, $next) {
        //     $this->credentials = Users::GetRoleById(Auth::id())->first();
        //     $this->setting     = Setting_Data::where('user_id',Auth::id())
        //                             ->where('status',1)
        //                             ->select('setting_list_id')
        //                             ->pluck('setting_list_id')->all();
        //     return $next($request);
        // });
    }

    public function index(Request $request) {
        $user_divisi = \Request::get('user_divisi');
        $allow = false;
        if(
            in_array($this->restrict,$user_divisi)
            ||
            in_array($this->admin,$user_divisi)
            ) 
        {
            $allow = true;
        }

        if(!$allow) {
            $request->session()->flash('alert-danger', 'Maaf anda tidak ada akses untuk fitur akses');
            return redirect('home');
        }


        $jabatan = Users_Role::where('user_id',Auth::user()->id)
                ->where('divisi',$this->restrict)->select('jabatan')->get()->pluck('jabatan');

        $status_data = array();
        foreach($jabatan as $key=>$val) {

            switch($val) {
                case 1 : 
                    array_push($status_data,1);
                break;
                case 2 : 
                    array_push($status_data,1,2);
                break;
                case 3 : 
                    array_push($status_data,2,3);
                break;
                case 4 : 
                    array_push($status_data,3,4);
                break;
                default :
                    unset($status_data);
                break;
            }
        } 

        
        $data = array(
            'data'         => Akses_Data::GetSpecific($status_data)->get(),
            'status_akses'  => Status_Akses::all(),
            'user'          => Auth::user(),
            'jabatan'       => $jabatan->toArray()
            //'execute'       => $execute
        );


        return view('akses/index',compact('data'));
    }

    public function pendaftaran_pic(Request $request) {
        dd($request);
        $akses_data = new Akses_Data;
        $akses_data->type = $request->type_daftar;
        $akses_data->created_by = Auth::user()->id;
        $akses_data->updated_by = Auth::user()->id;
        $akses_data->uuid       = $this->faker->uuid;
        $akses_data->status_akses = 1;

        $user   = Users::find(Auth::user()->id);
        $detail = Users_Detail::where('user_id',Auth::user()->id)->first();
        if($request['type_daftar'] == "self") {
            $akses_data->name = $user->name;
            $akses_data->email = $user->email;
            $akses_data->no_card = $request->no_kartu;
            $akses_data->foto   = $detail->foto;
            $akses_data->comment   = "Di Daftarkan oleh staff PIC";
        } else if ($request['type_daftar'] == "vendor") {
            $request->validate([
                'po' => 'required|image|mimes:jpeg,png,jpg|max:550',
                'foto' => 'required|image|mimes:jpeg,png,jpg|max:550',
            ]);

            $akses_data->name = $request->vendor_nama;
            $akses_data->email = $request->vendor_email;
            $akses_data->date_start = $request->start_card;
            $akses_data->date_end = $request->end_card;
            $akses_data->floor = $request->floor;
            $akses_data->comment = $request->pekerjaan;

            if ($request->hasFile('po')) {
                $image = $request->file('po');
                $file_name = date('Y-m-d H:i:s')." ".$this->faker->uuid.".jpg";
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $akses_data->po = $path.$file_name;
            }

            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $file_name = date('Y-m-d H:i:s')." ".$this->faker->uuid.".jpg";
                $path = "/images/akses/";
                $destinationPath = public_path($path);
                $image->move($destinationPath, $file_name);
                $akses_data->foto = $path.$file_name;
            }

            dd($request);

        }

        
        $bool = $akses_data->save();
        if($bool) {
            $request->session()->flash('alert-success', 'Akses telah di daftarkan');
        } else {
            $request->session()->flash('alert-danger', 'Data tidak masuk, Please contact your administrator');
        }
        return redirect($this->redirectTo);       
    }




    public function akses_approval(Request $request) {
        $data = Akses_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();
        if(count($data) < 1) {
            return redirect($this->redirectTo);
        } else {
            if($data->status_akses <= 3) {

                switch ($data->status_akses) {
                    case 1:
                        $data->status_akses = 2;
                        break;
                    case 2:
                        $data->status_akses = 3;
                        break;
                    case 3;
                        $data->status_akses = 4;
                        $data->status_data  = 3;
                    default:
                        # code...
                        break;
                }
                
                $data->updated_by   = $this->credentials->id;
                $data->save();
            } else {
                return redirect($this->redirectTo);
            }
            
        }
        return view('akses/approval');
    }


    public function akses_reject(Request $request) {
        $data = Akses_Data::where('status_data',1)
        ->where('uuid',$request->uuid)->first();

        if(count($data) < 1) {
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
            if($data->status_akses <= 3) {

                switch ($data->status_akses) {
                    case 1:
                        $data->status_akses = 5;
                        break;
                    case 2:
                        $data->status_akses = 6;
                        break;
                    case 3;
                        $data->status_akses = 7;
                    default:
                        # code...
                        break;
                }
                
                $data->status_data  = 2;
                $data->updated_by   = $this->credentials->id;
                $data->comment      = $request->desc;
                $data->save();
            } else {
                return redirect($this->redirectTo);
            }
            
        }
        return redirect($this->redirectTo);
    }

    public function pendaftaran_akses(Request $request) {
        $request->validate([
        'staff_foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $akses_data = new Akses_Data;

        if ($request->hasFile('staff_foto')) {
            $image = $request->file('staff_foto');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/akses/');
            $image->move($destinationPath, $name);
            $akses_data->foto  = $name;
        }

    	

    	if($request->type_daftar == "staff") {
            $akses_data->type   = $request->type_daftar;
            $akses_data->name   = strtolower($request->staff_nama);
            $akses_data->divisi = strtolower($request->staff_divisi);
            $akses_data->jabatan = strtolower($request->staff_jabatan);
            $akses_data->email  = strtolower($request->staff_email);
            $akses_data->nik  = strtolower($request->staff_nik);
            
    	} elseif($request->type_daftar == "vendor") {
    		// dd($request);
    		
    		$akses_data->type 	= $request->type_daftar;
    		$akses_data->name 	= strtolower($request->vendor_nama);
    		$akses_data->email 	= strtolower($request->vendor_email);
    		
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

    public function send($akses_data){
        $error = false;

        switch($akses_data->status_akses) {
            case 1 :
                $user = Users_Role::GetAksesDecisionMaker(2)->first();
                $subject = "Pendaftaran Kartu";
                $desc    = "baru saja mendaftarkan pengguna kartu";
                break;
            case 3 :
                $user = Users_Role::GetAksesDecisionMaker(4)->first();
                $subject = "Pencetakan Kartu";
                $desc    = "telah mendaftarkan kartu";
                break;
            case 5 : 
                $user = Users_Role::GetAksesDecisionMaker(6)->first();
                $subject = "Pengaktifan Kartu";
                $desc    = "telah mengaktifkan kartu";
                break;
            default : 
                $error = true;
                break;
        }

        if(!$error) {
            $data = array(
                "subject"   => $subject,
                "head"      => $user->username,
                "staff"     => Users::find($akses_data->updated_by)->name,
                "desc"      => $desc,
                "nama_user" => $akses_data->name,
                "divisi"    => $akses_data->divisi,
                "ktp"       => $akses_data->foto != null ? $akses_data->foto : null,
                "uuid"      => $akses_data->uuid    
            );

            //dd($data);
            $user->notify(new Akses_Notifications($data));
        } 
        
    }
}
