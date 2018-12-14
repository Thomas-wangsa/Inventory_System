<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = "notification";

    protected $fillable = [
        'user_id','category','notify_type', 'notify_status','data_id','data_uuid'
    ];
}
