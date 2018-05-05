<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

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
        'name', 'email', 'password','mobile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function scopeGetRoleById($query,$id) {
        return 
        $query->join('users_role','users_role.user_id','=','users.id')
        ->where('users.id','=',$id)
        ->select('users.*','users_role.divisi',
            'users_role.jabatan AS id_jabatan',
            'users_role.uuid',
            DB::raw('case 
                WHEN(users_role.divisi = 1) 
                    THEN (select name from divisi where id = users_role.divisi)
                WHEN(users_role.divisi = 2) 
                    THEN (select name from akses_role where id = id_jabatan)
                WHEN(users_role.divisi = 3)
                    THEN (select CONCAT(il.inventory_level_name," ",i_list.inventory_name) 
                        FROM inventory_role ir
                        INNER JOIN inventory_level il
                        ON il.id = ir.inventory_level_id
                        INNER JOIN inventory_list i_list
                        ON i_list.id = ir.inventory_list_id
                        where ir.id = id_jabatan 
                    )
                ELSE "NULL"
                END AS jabatan')
            );

    }

    public function scopeGetJabatan($query) {
        return $query->join('users_role','users_role.user_id','=','users.id')
        ->select('users.*','users_role.divisi','users_role.jabatan AS id_jabatan',
            'users_role.uuid',
            DB::raw('case 
                WHEN(users_role.divisi = 1) 
                    THEN (select name from divisi where id = users_role.divisi)
                WHEN(users_role.divisi = 2) 
                    THEN (select name from akses_role where id = id_jabatan)
                WHEN(users_role.divisi = 3)
                    THEN (select CONCAT(il.inventory_level_name," ",i_list.inventory_name) 
                        FROM inventory_role ir
                        INNER JOIN inventory_level il
                        ON il.id = ir.inventory_level_id
                        INNER JOIN inventory_list i_list
                        ON i_list.id = ir.inventory_list_id
                        where ir.id = id_jabatan 
                    )
                ELSE "NULL"
                END AS jabatan ')
            )
        ->where('users.id', '!=', Auth::id())
        ->orderBy('users.id', 'desc');
    }
}
