<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Data;
use App\Http\Models\Status_Akses;
use App\Http\Models\Setting_Data;
use App\Http\Models\Inventory_Data;
use Illuminate\Support\Facades\Hash;


class HomeController extends Controller
{   
    protected $credentials;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->credentials = Users::GetRoleById(Auth::id())->first();
            $this->setting     = Setting_Data::where('user_id',Auth::id())
                                    ->where('status',1)
                                    ->select('setting_list_id')
                                    ->pluck('setting_list_id')->all();
            if($this->credentials == null) {
                Auth::guard()->logout();
                $request->session()->invalidate();
                $request->session()->flash('alert-warning', 'Maaf, User sudah tidak aktif');
                return redirect('/login');
            }

            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $role  = array(1);
        $data['credentials']    = $this->credentials;
        $data['status_akses']   = Status_Akses::all();
        $data['setting']        = $this->setting;
        // dd($setting);
        if($this->credentials['divisi'] == 1) {
            $data['data']           = Akses_Data::GetSpecific($role)
            ->where('created_by',$this->credentials['id'])->get();
             
            return view('dashboard/pic',compact('data'));
        } else {
            return view('dashboard/dashboard',compact('data'));
        }
        
    }

    public function notify() {
        $data['setting']        = $this->setting;
        $data['notify'] = 0;

        if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
            $data['notify_data']    = Inventory_Data::GetNotify(2)->get();
            $data['desc']           = " telah menambahkan barang baru dengan informasi ";
        } else if ($this->credentials->divisi == 2) {
            switch ($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Akses_Data::where('status_akses',1)->count();
                    $data['notify_data']    = Akses_Data::GetNotify(1)->get();
                    $data['desc']           = " telah mendaftarkan kartu akses atas nama ";
                    break;
                case 3:
                    $data['notify']         = Akses_Data::where('status_akses',2)->count();
                    $data['notify_data']    = Akses_Data::GetNotify(2)->get();
                    $data['desc']           = " telah menyetujui daftar kartu akses atas nama ";
                    break;
                case 4:
                    $data['notify']         = Akses_Data::where('status_akses',3)->count();
                    $data['notify_data']    = Akses_Data::GetNotify(3)->get();
                    $data['desc']           = " telah mendaftarkan kartu akses untuk di cetak atas nama  ";
                    break;
                case 5:
                    $data['notify']         = Akses_Data::where('status_akses',4)->count();
                    $data['notify_data']    = Akses_Data::GetNotify(4)->get();
                    $data['desc']           = " telah menyetujui cetak kartu akses atas nama  ";
                    break;
                case 6:
                    $data['notify']         = Akses_Data::where('status_akses',5)->count();
                    $data['notify_data']    = Akses_Data::GetNotify(5)->get();
                    $data['desc']           = " telah mendaftarkan kartu akses untuk di cetak atas nama  ";
                    break;
                
                default:
                    # code...
                    break;
            }

        } else if($this->credentials->divisi == 3) {
            switch($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                    $data['notify_data']    = Inventory_Data::GetNotify(1)->get();
                    $data['desc']           = " telah menambahkan barang baru dengan informasi ";
                    break;
            }
            
        }
        $data['credentials']        = $this->credentials;
        return view('dashboard/notify',compact('data'));
    }

    public function profile() {
        $data['setting']        = $this->setting;
        $data['credentials'] = $this->credentials;
        return view('dashboard/profile',compact('data'));
    }


    public function password() {
        $data['setting']        = $this->setting;
        $data['credentials'] = $this->credentials;
        return view('dashboard/password',compact('data'));
    }


    public function post_password(Request $request) {
        $validatedData = $request->validate([
            'now_password'          => 'required|min:6',
            'password'              => 'required|min:6|confirmed|different:now_password',
            'password_confirmation' => 'required|min:6',
        ]);

        $user = Auth::user();
        if(Hash::check($request->now_password, $user->password)){
            $user->password = bcrypt($request->password);
            $cek = $user->save();
            if($cek) {
                $request->session()->flash('alert-success', 'Password telah di update !');
                
            } else {
                $request->session()->flash('alert-danger', 'Password gagal di update');
            }
        } else {
            $request->session()->flash('alert-danger', 'Failed, Data password tidak cocok');
        }

        return redirect("password");
        
    }


    public function ganti_foto(Request $request) {
        $request->validate([
        'background' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('background')) {
            $image = $request->file('background');
            $name = $this->credentials->id;
            $destinationPath = public_path('/images/user/');
            $image->move($destinationPath, $name);

            $user = Users_Role::where('user_id',$this->credentials->id)->first();
            $user->foto = "/images/user/".$name;
            $user->save();

            $request->session()->flash('alert-success', 'Foto telah di update');
            
        } else {
            $request->session()->flash('alert-danger', 'Please contact your administrator');
        }


        return redirect('profile'); 
    }

    public function ganti_profile(Request $request) {
        $request->validate([
        'nama_lengkap' => 'required|min:6',
        'phone'         => 'required|numeric|min:9'
        ]);

        $user = Users::find($this->credentials->id);
        $user->name = $request->nama_lengkap;
        $user->mobile = $request->phone;
        $cek = $user->save();

        if($cek) {
                $request->session()->flash('alert-success', 'Data telah di update !');
                
            } else {
                $request->session()->flash('alert-danger', 'Data gagal di update');
            }

        return redirect('profile'); 
    }

}
