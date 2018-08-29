<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Pic_Role;

use App\Http\Models\Users_Detail;
use App\Http\Models\Akses_Data;
use App\Http\Models\Akses_Expiry;
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
        $current_date = date('Y-m-d');
        $count_cron_today = Akses_Expiry::where('date_execution',$current_date)
                            ->count();
        if($count_cron_today < 1) {
            $date = strtotime("+40 day");
            $to_date =  date('Y-m-d', $date);


            Akses_Data::whereDate('date_end','<',$current_date)
                    ->where('status_akses',7)
                    ->where('status_data',3)
                    ->update(['status_akses'=>14]);


            $akses_data_expiry = Akses_Data::whereDate('date_end','>=',$current_date)
                    ->whereDate('date_end','<=',$to_date)
                    ->where('status_akses',7)
                    ->where('status_data',3)
                    ->get();

            if(count($akses_data_expiry) > 0) {
                $full_notification = array();
                foreach($akses_data_expiry as $key=>$val) {

                    if($val->pic_list_id == null) {
                        $list_user_id = Users_Role::where('divisi',3)
                        ->where('jabatan',1)
                        ->pluck('user_id');


                        if(count($list_user_id) > 0) {
                            foreach($list_user_id as $key_user=>$val_user) {
                                $data_notify = array(
                                'user_id'           => $val_user,
                                'akses_data_id'     => $val->id,
                                'status_akses_id'   => $val->status_akses,
                                'status_notify'     => 3,
                                'created_at'        => date('Y-m-d H:i:s')

                                );

                                array_push($full_notification,$data_notify);
                            }
                        }
                        
                    } elseif($val->pic_list_id != null) {
                        $list_user_id = Users_Role::join('pic_role',
                                'pic_role.id','=','users_role.jabatan')
                                ->where('users_role.divisi',2)
                                ->where('pic_role.pic_list_id',$val->pic_list_id)
                                ->whereIn('pic_role.pic_level_id',array(1,2))
                                ->select('pic_role.user_id')
                                ->get();


                        dd($list_user_id);
                    }
                }

                notify::insert($full_notification);
            }


            //dd($full_notification);
            //dd($akses_data_expiry);
            
            //Akses_Expiry::firstOrCreate(['date_execution'=>$current_date]);
        }


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
            ->join('notification_status',
                'notification_status.id','=','notification.status_notify')
            ->where('notification.user_id',Auth::user()->id)
            ->select('u.name AS username','akses_data.name AS access_card_name',
                    'status_akses.name AS status_akses_name',
                    'status_akses.color AS status_akses_color',
                    'notification.is_read',
                    'akses_data.uuid',
                    'c_user.name AS request_name',
                    'notification_status.name AS status_notify_name',
                    'notification.created_at'
                    )
            ->orderby('notification.created_at','desc')
            ->paginate(20);
        
        // Update 
        notify::where('user_id',Auth::user()->id)
        ->where('is_read',0)
        ->update(['is_read'=>1]);
       
        

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
