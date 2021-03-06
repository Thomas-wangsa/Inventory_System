<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting_Role extends Model
{	
	use SoftDeletes;
    protected $table = "setting_role";
     protected $primaryKey = 'id';

    public function scopeGetListById($query,$user_id) {
        return 
        $query->where('user_id','=',$user_id)
        ->select('setting_list_id')->distinct();
    }
}
