<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Users_Detail;
use App\Http\Models\Akses_Data;
use App\Http\Models\Status_Akses;
use App\Http\Models\Setting_Data;
use App\Http\Models\Inventory_Data;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Notification AS notify;


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
        // $this->middleware(function ($request, $next) {
        //     $this->credentials = Users::GetRoleById(Auth::id())->first();
        //     $this->setting     = Setting_Data::where('user_id',Auth::id())
        //                             ->where('status',1)
        //                             ->select('setting_list_id')
        //                             ->pluck('setting_list_id')->all();
        //     if($this->credentials == null) {
        //         Auth::guard()->logout();
        //         $request->session()->invalidate();
        //         $request->session()->flash('alert-warning', 'Maaf, User sudah tidak aktif');
        //         return redirect('/login');
        //     }

        //     return $next($request);
        // });
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('dashboard/dashboard');        
    }

    public function old_index() {
        return view('dashboard/dashboard_bck');        
    }

    public function notify() {
        $data['notify'] = notify::join('users as u',
                'u.id','=','notification.user_id')
            ->join('akses_data',
                'akses_data.id','=','notification.akses_data_id')
            ->join('status_akses',
                'status_akses.id','=','notification.status_akses_id')
            ->join('users as c_user',
                'c_user.id','=','akses_data.created_by')
            ->where('notification.user_id',Auth::user()->id)
            ->select('u.name AS username','akses_data.name AS access_card_name',
                    'status_akses.name AS status_akses_name',
                    'status_akses.color AS status_akses_color',
                    'notification.read',
                    'akses_data.uuid',
                    'c_user.name AS request_name'
                    )
            ->orderby('notification.created_at','desc')
            ->paginate(20);
        
        // Update 
        notify::where('user_id',Auth::user()->id)
        ->where('read',0)
        ->update(['read'=>1]);
       
        

        return view('dashboard/notify',compact('data'));
    }

    public function profile() {
        return view('dashboard/profile');
    }


    public function ganti_foto(Request $request) {
        $request->validate([
        'background' => 'required|image|mimes:jpeg,png,jpg|max:550',
        ]);

        if ($request->hasFile('background')) {
            $image = $request->file('background');
            $name = $request->user_uuid.".".$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/user/');
            $image->move($destinationPath, $name);

            $user = Users_Detail::find(Auth::user()->id);
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
        'nama_lengkap' => 'required|min:3',
        'phone'         => 'required|numeric|min:9'
        ]);

        $user = Users::find(Auth::user()->id);
        $user->name = strtolower($request->nama_lengkap);
        $user->mobile = $request->phone;
        $cek = $user->save();

        if($cek) {
                $request->session()->flash('alert-success', 'Data telah di update !');
                
            } else {
                $request->session()->flash('alert-danger', 'Data gagal di update');
            }

        return redirect('profile'); 
    }

    public function password() {
        return view('dashboard/password');
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
