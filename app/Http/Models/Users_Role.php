<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Users_Role extends Model
{	
	use Notifiable;
	
    protected $table = "users_role";
    protected $primaryKey = 'id';

    public function scopeGetAksesDecisionMaker($query,$param) {
    	return $query->join('users','users.id','=','users_role.user_id')
    			->where('divisi',2)
    			->where('jabatan',$param)
    			->select('users.name AS username','users.email','users_role.uuid')
    			->take(1);

    }

}
