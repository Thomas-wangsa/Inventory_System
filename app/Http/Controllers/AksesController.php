<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Akses_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Divisi;

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

    public function __construct(UrlGenerator $url){
        $this->url      = $url;
        $this->faker    = Faker::create();

        $this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            return $next($request);
        });
    }

    public function index(Request $request) {
        if($this->credentials->divisi == 1 || $this->credentials->divisi == 2 ) {
            $access = true;
        } else {
            $request->session()->flash('alert-danger', 'Maaf anda tidak memiliki akses untuk fitur ini');
            return redirect('home'); 
        }

        $data = array(
            'credentials'   => $this->credentials,
            'divisi'        => Divisi::all(),
            'akses'         => Akses_Data::GetDetailAkses()->paginate(5),
            'user'          => Auth::user()
        );

    	return view('akses/index',compact("data"));
    }

    public function pendaftaran_akses(Request $request) {
    	$akses_data = new Akses_Data;

    	if($request->type_daftar == "staff") {
            $request->validate([
            'staff_foto' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('staff_foto')) {
                $image = $request->file('staff_foto');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images/akses/');
                $image->move($destinationPath, $name);
                $akses_data->foto  = $name;
            }

            $akses_data->type   = $request->type_daftar;
            $akses_data->name   = strtolower($request->staff_nama);
            $akses_data->divisi = strtolower($request->staff_divisi);
            $akses_data->jabatan = strtolower($request->staff_jabatan);
            $akses_data->email  = strtolower($request->vendor_email);
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
        $akses_data->updated_by         = Auth::user()->id;
    	$akses_data->status_akses       = 1;
        $akses_data->uuid               = $this->faker->uuid;
        $bool = $akses_data->save();

        if($bool) {
            $request->session()->flash('alert-success', 'Akses telah di daftarkan');
            $this->send($akses_data);
        } else {
            $request->session()->flash('alert-danger', 'Data tidak masuk, Please contact your administrator');
        }
        //$this->send();
        
    	return redirect($this->redirectTo);
    }

    public function pendaftaran_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 2
        ]);
        return redirect($this->redirectTo);
    }

    public function pencetakan_akses(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 3
        ]);
        return redirect($this->redirectTo);
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
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 5
        ]);
        return redirect($this->redirectTo);
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
                break;
            case 3 :
                $user = Users_Role::GetAksesDecisionMaker(4)->first();
                break;
            case 5 : 
                $user = Users_Role::GetAksesDecisionMaker(6)->first();
                break;
            default : 
                $error = true;
                break;
        }

        if(!$error) {
            $data = array(
                "test"  => 1,
                "test2" => "afagaf" 
            );

            $user->notify(new Akses_Notifications($data));
        } 
        
    }
}
