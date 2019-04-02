<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_Room_List extends Model
{
    protected $table = "admin_room_list";
    protected $primaryKey = 'id';

    public function scopeGetConfig($query,$param=null) {
    	$query->join('users AS u_c','u_c.id','=','admin_room_list.created_by')
    		->join('users AS u_u','u_u.id','=','admin_room_list.updated_by');
    	if($param != null) {$query->where('admin_room','LIKE',"%$param%");}
    	$query->select('admin_room_list.*','u_c.name AS created_by_user','u_u.name AS updated_by_user');
    	return $query;
    }
}
