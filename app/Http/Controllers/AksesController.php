<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\Akses_Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\UrlGenerator;

class AksesController extends Controller
{	
	protected $redirectTo      = '/akses';
    protected $url;

    public function __construct(UrlGenerator $url){
        $this->url  = $url;
    }

    public function index() {
    	$data = Akses_Data::GetDetailAkses()->get();
        $url  = $this->url;
        $user = Auth::user();
    	return view('akses/index',compact("data","url","user"));
    }

    public function pendaftaran_akses(Request $request) {
    	$akses_data = new Akses_Data;

    	if($request->type_daftar == "staff") {
            $akses_data->type   = $request->type_daftar;
            $akses_data->name   = strtolower($request->staff_nama);
            $akses_data->divisi = strtolower($request->staff_divisi);
            $akses_data->jabatan = strtolower($request->staff_jabatan);
            $akses_data->email  = strtolower($request->vendor_email);
            $akses_data->nik  = strtolower($request->staff_nik);
            $akses_data->foto  = strtolower($request->staff_foto);
    	} elseif($request->type_daftar == "vendor") {
    		// dd($request);
    		
    		$akses_data->type 	= $request->type_daftar;
    		$akses_data->name 	= strtolower($request->vendor_nama);
    		$akses_data->email 	= strtolower($request->vendor_email);
    		
    	} else {
    		echo "ERROR, Please contact your administrator";die;
    	}
    		// dd($request);
        $akses_data->updated_by = Auth::user()->id;
    	$akses_data->status_akses = 1;
    	$akses_data->save();
    	return redirect($this->redirectTo);
    }

    public function pendaftaran_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 2
        ]);
        return redirect($this->redirectTo);
    }

    public function pencetakan_akses(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 3
        ]);
        return redirect($this->redirectTo);
    }

    public function pencetakan_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 4
        ]);
        return redirect($this->redirectTo);
    }

    public function aktifkan_akses(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 5
        ]);
        return redirect($this->redirectTo);
    }

    public function aktifkan_diterima(Request $request) {
        Akses_Data::where('id',$request->data_id)
        ->update([
            "updated_by"    =>$request->updated_by,
            "status_akses"  => 6
        ]);
        return redirect($this->redirectTo);
    }
}