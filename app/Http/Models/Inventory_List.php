<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory_List extends Model
{
    protected $table = "inventory_list";
    protected $primaryKey = 'id';

    protected $fillable = [
        'inventory_name', 'updated_by',
    ];
}
