<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Users;
use App\Http\Models\Akses_Data;
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
            return $next($request);
        });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {   
        $data['credentials'] = $this->credentials;

        if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
        } else if ($this->credentials->divisi == 2) {
            switch ($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Akses_Data::where('status_akses',1)->count();
                    break;
                case 3:
                    $data['notify']         = Akses_Data::where('status_akses',2)->count();
                    break;
                case 4:
                    $data['notify']         = Akses_Data::where('status_akses',3)->count();
                    break;
                case 5:
                    $data['notify']         = Akses_Data::where('status_akses',4)->count();
                    break;
                case 6:
                    $data['notify']         = Akses_Data::where('status_akses',5)->count();
                    break;
                
                default:
                    # code...
                    break;
            }

        } else if($this->credentials->divisi == 3) {
            switch($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                    break;
            }
            
        }
        return view('dashboard/dashboard',compact('data'));
    }


    public function notify() {

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
        if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
        } else if ($this->credentials->divisi == 2) {
            switch ($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Akses_Data::where('status_akses',1)->count();
                    break;
                case 3:
                    $data['notify']         = Akses_Data::where('status_akses',2)->count();
                    break;
                case 4:
                    $data['notify']         = Akses_Data::where('status_akses',3)->count();
                    break;
                case 5:
                    $data['notify']         = Akses_Data::where('status_akses',4)->count();
                    break;
                case 6:
                    $data['notify']         = Akses_Data::where('status_akses',5)->count();
                    break;
                
                default:
                    # code...
                    break;
            }

        } else if($this->credentials->divisi == 3) {
            switch($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                    break;
            }
            
        }
        $data['credentials'] = $this->credentials;
        return view('dashboard/profile',compact('data'));
    }


    public function password() {
        if($this->credentials->divisi == 1 ) {
            $data['notify']         = Inventory_Data::where('status_inventory',2)->count();
        } else if ($this->credentials->divisi == 2) {
            switch ($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Akses_Data::where('status_akses',1)->count();
                    break;
                case 3:
                    $data['notify']         = Akses_Data::where('status_akses',2)->count();
                    break;
                case 4:
                    $data['notify']         = Akses_Data::where('status_akses',3)->count();
                    break;
                case 5:
                    $data['notify']         = Akses_Data::where('status_akses',4)->count();
                    break;
                case 6:
                    $data['notify']         = Akses_Data::where('status_akses',5)->count();
                    break;
                
                default:
                    # code...
                    break;
            }

        } else if($this->credentials->divisi == 3) {
            switch($this->credentials->id_jabatan) {
                case 2:
                    $data['notify']         = Inventory_Data::where('status_inventory',1)->count();
                    break;
            }
            
        }
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
}
