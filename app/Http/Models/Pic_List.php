<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Pic_List extends Model
{
    protected $table = "pic_list";
    protected $primaryKey = 'id';

    public function scopeGetConfig($query,$param=null) {
    	$query->join('users AS u_c','u_c.id','=','pic_list.created_by')
    		->join('users AS u_u','u_u.id','=','pic_list.updated_by');
    	if($param != null) {$query->where('vendor_name','LIKE',"%$param%");}
    	$query->select('pic_list.*','u_c.name AS created_by_user','u_u.name AS updated_by_user');
    	return $query;
    }
}
