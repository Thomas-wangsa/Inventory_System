<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCardRegisterStatus extends Model
{
    protected $table = "access_card_register_status";
    protected $primaryKey = 'id';

    protected $fillable = [
        'register_name', 
    ];
}
