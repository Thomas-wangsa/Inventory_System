<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Setting_Data;
use Illuminate\Support\Facades\Auth;

class CheckCredentials
{   
    protected $user_detail;
    protected $user_divisi;
    protected $user_jabatan;
    protected $user_setting;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $id = Auth::user()->id;
        $this->user_detail    = Users::GetDetailById($id)->first();
        $this->user_divisi    = Users_Role::GetDivisiById($id)->pluck('divisi')->toArray();
        $this->user_setting   = Setting_Data::GetListById($id)->pluck('setting_list_id');
        
        // ternary 
        $this->user_setting   = count($this->user_setting) > 0 
                            ? $this->user_setting->toArray() : array(0);




        $this->user_jabatan = Users_Role::GetAllRoleById($id)->orderBy('divisi')->get();
        //dd($this->user_jabatan);

        // Share
        view()->share('user_detail', $this->user_detail);
        view()->share('user_divisi',$this->user_divisi);
        view()->share('user_jabatan',$this->user_jabatan);
        view()->share('user_setting',$this->user_setting);

        $request->attributes->add(['user_divisi' => $this->user_divisi]);
        $request->attributes->add(['user_detail' => $this->user_detail]);
        $request->attributes->add(['user_setting' => $this->user_setting]);

        return $next($request);
    }
}
