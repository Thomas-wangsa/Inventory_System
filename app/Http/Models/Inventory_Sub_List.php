<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_Sub_List extends Model
{
    protected $table = "inventory_sub_list";

    protected $fillable = [
        'inventory_list_id', 'inventory_sub_list_name',
    ];
}
