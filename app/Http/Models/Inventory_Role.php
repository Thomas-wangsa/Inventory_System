<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_Role extends Model
{
  	protected $table = "inventory_role";

  	protected $fillable = [
        'user_id','inventory_list_id', 'inventory_level_id',
    ];

    protected $primaryKey = 'id';
}
