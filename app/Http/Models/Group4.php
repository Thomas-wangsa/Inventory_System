<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Group4 extends Model
{
    protected $table = "group4";

    public function scopeGetConfig($query,$param=null) {
    	$query->join('users AS u_c','u_c.id','=','group4.created_by')
    		->join('users AS u_u','u_u.id','=','group4.updated_by');
    	if($param != null) {$query->where('group4_name','LIKE',"%$param%");}
    	$query->select('group4.*','u_c.name AS created_by_user','u_u.name AS updated_by_user');
    	return $query;
    }
}
