<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class New_Map_Images extends Model
{
    use SoftDeletes;
    protected $table = "new_map_images";
}
