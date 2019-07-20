<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Pic_Role;

use App\Http\Models\Users_Detail;
use App\Http\Models\Akses_Data;
use App\Http\Models\AccessCardRequest;

use App\Http\Models\Akses_Expiry;
use App\Http\Models\Status_Akses;
use App\Http\Models\Status_Inventory;

use App\Http\Models\Setting_Data;
use App\Http\Models\Inventory_Data;
use App\Http\Models\New_Inventory_Data;

use Illuminate\Support\Facades\Hash;
use App\Http\Models\Notification AS notify;
use Illuminate\Support\Facades\DB;


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
            $date = strtotime("+30 day");
            $to_date =  date('Y-m-d', $date);


            Akses_Data::whereDate('date_end','<',$current_date)
                    ->where('status_akses',9)
                    ->update(
                        [
                            'status_akses'=>19,
                            'status_data'=>5,
                            'comment'=>'auto set expired by system'
                        ]);


            $akses_data_expiry = Akses_Data::whereDate('date_end','>=',$current_date)
                    ->whereDate('date_end','<=',$to_date)
                    ->where('status_akses',9)
                    ->get();
                                                                
            //dd($akses_data_expiry);
            if(count($akses_data_expiry) > 0) {
                $full_notification = array();
                foreach($akses_data_expiry as $key=>$val) {
                        $requester = $val['created_by'];
                        $data_notify = array(
                        'user_id'           => $requester,
                        'category'          => 1,
                        'notify_type'       => $val->request_type,
                        'notify_status'     => 20,
                        'data_id'           => $val["id"],
                        'data_uuid'         => $val["uuid"],
                        'name'              => $val["name"],
                        'note'              => "Expired on ". $val['date_end'],
                        'created_at'        => date('Y-m-d H:i:s')
                        );
                        array_push($full_notification,$data_notify);
                        
                        //dd($full_notification);
                } // Foreach
                notify::insert($full_notification);
            }


            //dd($full_notification);
            //dd($akses_data_expiry);
            
            Akses_Expiry::firstOrCreate(['date_execution'=>$current_date]);
        }


        return view('dashboard/dashboard');        
    }

    public function old_index() {
        return view('dashboard/dashboard_bck');        
    }

    public function notify(Request $request) {
        $data = array();

        $data['notify'] = notify::join('users as u',
                        'u.id','=','notification.user_id')
                        ->where('notification.user_id',Auth::user()->id)
                        ->orderby('created_at','desc')
                        ->select(
                            'notification.category',
                            'notification.notify_type',
                            'notification.notify_status',
                            'notification.created_at',
                            'notification.data_uuid',
                            'notification.name',
                            'notification.note'
                        )
                        ->paginate(20);
        //dd($data);
        foreach($data['notify'] as $key=>$val) {

            $created_by     = "-";
            $category_name  = "-";
            $notify_type    = "-";
            $notify_status  = "-";

           

            if($val['category'] == 1) {
                $category_name  = "access card";
                $created_by     = Akses_Data::join('users',
                                'users.id','=','akses_data.created_by')
                                ->where('akses_data.uuid',$val['data_uuid'])
                                ->select('users.name AS username')
                                ->first()->username;
                $notify_type    = AccessCardRequest::find($val['notify_type'])->request_name;
                $notify_status  = Status_Akses::find($val['notify_status']);
            } else if($val['category'] == 2) {

                $notify_name = "undefined";
                if($val['notify_type'] == 1) {
                    $notify_name = "new inventory";
                } else if ($val['notify_type'] == 2) {
                    $notify_name = "update inventory";
                }

                $category_name  = "inventory";
                $created_by     = New_Inventory_Data::join('users',
                                'users.id','=','new_inventory_data.created_by')
                                ->where('new_inventory_data.uuid',$val['data_uuid'])
                                ->select('users.name AS username')
                                ->first()->username;
                $notify_type    = $notify_name;
                $notify_status  = Status_Inventory::find($val['notify_status']);
            }
            //dd($notify_status);
            $data['notify'][$key]['created_by']     = isset($created_by) ? $created_by : "-";
            $data['notify'][$key]['category_name']  = isset($category_name) ? $category_name : "-";
            $data['notify'][$key]['notify_type']    = isset($notify_type) ? $notify_type : "-";
            $data['notify'][$key]['notify_status_name']  = isset($notify_status['name']) ? $notify_status['name'] : "-";
            $data['notify'][$key]['notify_status_color']  = isset($notify_status['color']) ? $notify_status['color'] : "#FF0000";
            $data['notify'][$key]['created_at'] = isset($val['created_at']) ? $val['created_at'] : "-";
            $data['notify'][$key]['data_uuid'] = isset($val['data_uuid']) ? $val['data_uuid'] : "-";
            
        }

        // Update 
        notify::where('user_id',Auth::user()->id)
        ->where('is_read',0)
        ->update(['is_read'=>1]);


        return view('dashboard/notify',compact('data'));
    }

    public function old_notify(Request $request) {


        $data['notify'] = notify::join('users as u',
                'u.id','=','notification.user_id')
            ->join('divisi','divisi.id','=','notification.category')
            ->where('notification.user_id',Auth::user()->id)
            ->select(
                    'notification.*',
                    'u.name AS username',
                    'divisi.name AS divisi_name',
                    'sub_notify.name AS sub_notify_name'
                    )
            ->orderby('notification.created_at','desc')
            ->paginate(20);

        $data['info'] = array();


        foreach($data['notify'] as $key=>$val) {
            DB::connection()->enableQueryLog();
            if($val->category == 2 || $val->category == 3) {
                $info = notify::join('akses_data',
                    'akses_data.id','=','notification.data_id')
                    ->join('status_akses',
                    'status_akses.id','=','notification.status_data_id')
                    ->join('users',
                    'users.id','=','akses_data.created_by')
                    ->where('notification.data_id',$val->data_id)
                    ->where('notification.user_id',Auth::user()->id)
                    ->where('notification.status_data_id',$val->status_data_id)
                    ->select(
                        'notification.category AS notification_category',
                        'users.name AS request_name',
                        'akses_data.name AS notification_data_name',
                        'status_akses.name AS notification_status_data_name',
                        'status_akses.color AS notification_status_data_color',
                        'akses_data.uuid AS notification_data_uuid'
                    )
                    ->first();

                if(count($info) > 0) {
                    $data['info'][$key] = $info;
                } else {
                    echo "ERROR in division 2 or 3, Please contact your administrator";
                    echo "<br/>";
                    echo DB::getQueryLog()[$key]['query'];
                    //dd(DB::getQueryLog()[$key]);
                    dd(DB::getQueryLog()[$key]['bindings']);
                    die;
                }

            } else if ($val->category == 4) {
                $info = notify::join('inventory_data',
                    'inventory_data.id','=','notification.data_id')
                    ->join('inventory_list',
                    'inventory_list.id','=','inventory_data.inventory_list_id')
                    ->join('status_inventory',
                    'status_inventory.id','=','notification.status_data_id')
                    ->join('users',
                    'users.id','=','inventory_data.created_by')
                    ->where('notification.data_id',$val->data_id)
                    ->where('notification.user_id',Auth::user()->id)
                    ->where('notification.status_data_id',$val->status_data_id)
                    ->select(
                        'notification.category AS notification_category',
                        'users.name AS request_name',
                        'inventory_list.inventory_name AS notification_data_name',
                        'status_inventory.name AS notification_status_data_name',
                        'status_inventory.color AS notification_status_data_color',
                        'inventory_data.uuid AS notification_data_uuid'
                    )
                    ->first();
                if(count($info) > 0) {
                    $data['info'][$key] = $info;
                } else {
                    echo "ERROR in division 4, Please contact your administrator";
                    echo "<br/>";
                    echo DB::getQueryLog()[$key]['query'];
                    //dd(DB::getQueryLog()[$key]);
                    dd(DB::getQueryLog()[$key]['bindings']);
                    die;
                }

            } else {
                $request->session()->flash('alert-danger', 'Category not found');
                return redirect('home');
            }
        }
        
        //dd($data);
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
