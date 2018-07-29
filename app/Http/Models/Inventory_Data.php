<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_Data extends Model
{
    protected $table = "inventory_data";

    protected $fillable = [
        'count', 'inventory_sub_list_id','comment','serial_number',
        'location','status_inventory','updated_by','uuid','created_by'
    ];

    public function scopeGetDetailInventory($query,$param) {
    	return $query->join('status_inventory','status_inventory.id','=','inventory_data.status_inventory')
    	->join('inventory_sub_list','inventory_sub_list.id','=','inventory_data.inventory_sub_list_id')
    	->join('inventory_list','inventory_list.id','=','inventory_sub_list.inventory_list_id')
    	->join('users','users.id','=','inventory_data.updated_by')
        ->whereIn('inventory_list.id',$param)
    	->select('inventory_data.*','status_inventory.name AS status_name','status_inventory.color AS inventory_color'
    		,'inventory_sub_list.inventory_sub_list_name','inventory_list.inventory_name','users.name AS username')
    	->orderBy('inventory_data.id','DESC');
    }

    public function scopeGetNotify($query,$param) {
        return $query->join('users','users.id','=','inventory_data.updated_by')
        ->join('inventory_sub_list','inventory_sub_list.id','=','inventory_data.inventory_sub_list_id')
        ->where('status_inventory',$param)
        ->select('inventory_data.*','users.name AS username','inventory_sub_list.inventory_sub_list_name')
        ->orderBy('inventory_data.id','DESC');
    }
}
