<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Admin_Room_Role extends Model
{
    protected $table = "admin_room_role";
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id','admin_room_list_id', 
    ];
}
