<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notification";

    protected $fillable = [
        'user_id','category','data_id', 'status_data_id','sub_notify_id'
    ];
}
