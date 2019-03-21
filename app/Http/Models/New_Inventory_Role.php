<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class New_Inventory_Role extends Model
{
    protected $table = "new_inventory_role";

  	protected $fillable = [
        'user_id','group1','group2','group3','group4','inventory_list_id', 'inventory_level_id',
    ];

    protected $primaryKey = 'id';
}
