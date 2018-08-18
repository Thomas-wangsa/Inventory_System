<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Pic_Role extends Model
{
    protected $table = "pic_role";
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id','pic_list_id', 'pic_level_id',
    ];
}
