<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Users_Role extends Model
{	
	use Notifiable;
	use SoftDeletes;
    protected $table = "users_role";
    protected $primaryKey = 'role_id';


    public function scopeGetDivisiById($query,$user_id) {
        return 
        $query->where('users_role.user_id','=',$user_id)
        ->select('divisi')->distinct();
    }

    public function scopeGetAllRoleById($query,$user_id) {
        return
        $query->where('users_role.user_id','=',$user_id)
        ->select('divisi','jabatan',
            DB::raw('case 
                WHEN(users_role.divisi = 1)
                    THEN (
                        select name
                        from divisi where id = users_role.divisi
                    )
                WHEN(users_role.divisi = 2)
                    THEN (select CONCAT(pl.pic_level_name," ",p_list.vendor_name) 
                        FROM pic_role pr
                        INNER JOIN pic_level pl
                        ON pl.id = pr.pic_level_id
                        INNER JOIN pic_list p_list
                        ON p_list.id = pr.pic_list_id
                        where pr.id = jabatan
                        and user_id = '.$user_id.' 
                    )
                WHEN(users_role.divisi = 3)
                    THEN (
                        select CONCAT(name," ","(access)") 
                        from akses_role where id = jabatan
                    )
                WHEN(users_role.divisi = 4)
                    THEN (select CONCAT(il.inventory_level_name," ",i_list.inventory_name) 
                        FROM inventory_role ir
                        INNER JOIN inventory_level il
                        ON il.id = ir.inventory_level_id
                        INNER JOIN inventory_list i_list
                        ON i_list.id = ir.inventory_list_id
                        where ir.id = jabatan 
                        and user_id = '.$user_id.'
                    )
                WHEN(users_role.divisi = 5)
                    THEN (select CONCAT("admin room"," ",al.admin_room) 
                        FROM admin_room_role ar
                        INNER JOIN admin_room_list al
                        ON al.id = ar.admin_room_list_id
                        where ar.id = jabatan 
                        and user_id = '.$user_id.'
                    )
                ELSE "NULL"
                END AS nama_jabatan')
        );
    }
    public function scopeGetAksesDecisionMaker($query,$param) {
    	return $query->join('users','users.id','=','users_role.user_id')
    			->where('divisi',2)
    			->where('jabatan',$param)
    			->select('users.name','users.email');

    }

    public function scopeGetInventoryDecisionMaker($query,$param) {
        if($param == 2) {
            return $query->join('users','users.id','=','users_role.user_id')
                ->where('divisi',3)
                ->where('jabatan',$param)
                ->select('users.name AS username','users.email','users_role.uuid')
                ->take(1);
        } else if($param == 3) {
            return $query->join('users','users.id','=','users_role.user_id')
                    ->where('divisi',1)
                    ->select('users.name AS username','users.email','users_role.uuid')
                    ->take(1);
        }
        

    }



}
