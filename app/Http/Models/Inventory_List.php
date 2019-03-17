<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_List extends Model
{
    protected $table = "inventory_list";
    protected $primaryKey = 'id';

    protected $fillable = [
        'inventory_name', 'updated_by','inventory_detail_name',
    ];

    public function scopeGetConfig($query,$param=null) {
    	$query->join('users AS u_c','u_c.id','=','inventory_list.created_by')
    		->join('users AS u_u','u_u.id','=','inventory_list.updated_by');
    	if($param != null) {$query->where('inventory_name','LIKE',"%$param%");}
    	$query->select('inventory_list.*','u_c.name AS created_by_user','u_u.name AS updated_by_user');
    	return $query;
    }
}
