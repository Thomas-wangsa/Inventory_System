<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class New_Inventory_Sub_Data extends Model
{	
	use SoftDeletes;
    protected $table = "new_inventory_sub_data";
}
