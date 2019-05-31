<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Akses_Data extends Model
{
    protected $table = "akses_data";
    use SoftDeletes;
    protected $fileable = array('type_daftar','nik');
    public function scopeGetDetailAkses($query) {
    	return $query->join('status_akses','status_akses.id','=','akses_data.status_akses')
    	->join('users','users.id','=','akses_data.updated_by')
    	->select('akses_data.*','status_akses.name AS status_name','status_akses.color','users.name AS username')
    	->orderBy('akses_data.id','DESC');
    }

    public function scopeGetNotify($query,$param) {
    	return $query->join('users','users.id','=','akses_data.updated_by')
    	->where('status_akses',$param)
    	->select('akses_data.*','users.name AS username')
    	->orderBy('akses_data.id','DESC');
    }


    public function scopeGetSpecific($query,$param) {
        return $query->join('status_akses','akses_data.status_akses','=','status_akses.id')
            ->join('users','users.id','=','akses_data.updated_by')
            ->whereIn('akses_data.status_akses',$param)
            ->select('akses_data.*','status_akses.name AS status_name','status_akses.color AS status_color','users.name AS username')
            ->orderBy('akses_data.id','DESC');
    }
}
