<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Group3 extends Model
{
    protected $table = "group3";

    public function scopeGetConfig($query,$param=null) {
    	$query->join('users AS u_c','u_c.id','=','group3.created_by')
    		->join('users AS u_u','u_u.id','=','group3.updated_by');
    	if($param != null) {$query->where('group3_name','LIKE',"%$param%");}
    	$query->select('group3.*','u_c.name AS created_by_user','u_u.name AS updated_by_user');
    	return $query;
    }
}
