<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_Data extends Model
{
    protected $table = "inventory_data";

    protected $fillable = [
        'count', 'inventory_sub_list_id','comment','serial_number',
        'location','status_inventory','updated_by'
    ];

    public function scopeGetDetailInventory($query) {
    	return $query->join('status_inventory','status_inventory.id','=','inventory_data.status_inventory')
    	->join('inventory_sub_list','inventory_sub_list.id','=','inventory_data.inventory_sub_list_id')
    	->join('inventory_list','inventory_list.id','=','inventory_sub_list.inventory_list_id')
    	->join('users','users.id','=','inventory_data.updated_by')
    	->select('inventory_data.*','status_inventory.name AS status_name','status_inventory.color'
    		,'inventory_sub_list.inventory_sub_list_name','inventory_list.inventory_name','users.name AS username')
    	->orderBy('inventory_data.id','DESC');
    }
}
