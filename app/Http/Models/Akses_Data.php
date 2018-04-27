<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Akses_Data extends Model
{
    protected $table = "akses_data";

    public function scopeGetDetailAkses($query) {
    	return $query->join('status_akses','status_akses.id','=','akses_data.status_akses')
    	->join('users','users.id','=','akses_data.updated_by')
    	->select('akses_data.*','status_akses.name AS status_name','status_akses.color','users.name AS username')
    	->orderBy('akses_data.id','DESC');
    }
}
