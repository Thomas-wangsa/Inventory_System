<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Notification_Status extends Model
{
    protected $table = "notification_status";

    protected $fillable = [
        'name',
    ];
}
