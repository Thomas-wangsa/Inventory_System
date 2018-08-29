<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Akses_Expiry extends Model
{
    protected $table = "akses_expiry";


    protected $fillable = [
        'date_execution',
    ];
}
