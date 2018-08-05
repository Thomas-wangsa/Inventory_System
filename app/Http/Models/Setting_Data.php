<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Setting_Data extends Model
{
    protected $table = "setting_data";

    public function scopeGetListById($query,$user_id) {
        return 
        $query->where('user_id','=',$user_id)
        ->select('setting_list_id')->distinct();
    }
}
