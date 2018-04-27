<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Users extends Model
{   
    protected $table = "users";
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeGetJabatan($query) {
        return $query->join('users_role','users_role.user_id','=','users.id')
        ->select('users.*','users_role.divisi','users_role.jabatan AS id_jabatan',
            DB::raw('case 
                WHEN(users_role.divisi = 1) 
                    THEN (select name from divisi where id = users_role.divisi)
                WHEN(users_role.divisi = 2) 
                    THEN (select name from akses_role where id = id_jabatan)
                ELSE "NULL"
                END AS jabatan ')
        );
    }
}
