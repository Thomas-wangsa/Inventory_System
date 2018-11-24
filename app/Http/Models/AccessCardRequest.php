<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AccessCardRequest extends Model
{
    protected $table = "access_card_request";
    protected $primaryKey = 'id';

    protected $fillable = [
        'request_name', 
    ];
}
