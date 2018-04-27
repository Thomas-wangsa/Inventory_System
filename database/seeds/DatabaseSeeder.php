<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Status_Akses;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {	
    	$divisi_array = array(
    		array("name"=>"Administrator"),
    		array("name"=>"Akses"),
    		array("name"=>"Inventory")
    	);

    	foreach ($divisi_array as $key => $value) {
    		Divisi::firstOrCreate($value);
    	}


        $design = new Design;
        $design->logo = "images/template/plain.jpg";
        $design->save();

        $users_array = array(
            array(
                "name"=>"admin",
                "email"=>"admin@gmail.com",
                "password"=>bcrypt(123456)
            ),
            array(
                "name"=>"thomas",
                "email"=>"thomas.wangsa@gmail.com",
                "password"=>bcrypt(123456)
            )
        );

        foreach ($users_array as $key => $value) {
            Users::firstOrCreate($value);
        }
        

        $akses_role_array = array(
            array("name"=>"staff pendaftaran"),
            array("name"=>"head pendaftaran"),
            array("name"=>"staff pencetakan"),
            array("name"=>"head pencetakan"),
            array("name"=>"staff pengaktifan "),
            array("name"=>"head pengaktifan"),
        );

        foreach ($akses_role_array as $key => $value) {
            Akses_Role::firstOrCreate($value);
        }


        $users = Users::all();

        foreach ($users as $key => $value) {
            $data_array = array(
                "user_id"  => $value->id,
                "divisi"    => $key+1,
            );
            Users_Role::firstOrCreate($data_array);
        }

        $status_akses_array = array(
            array("name"=>"Menunggu Daftar","color"=>"#FFFF00"),
            array("name"=>"Diterima Daftar","color"=>"#00FF00"),
            array("name"=>"Menunggu Cetak","color"=>"#FFFF00"),
            array("name"=>"Diterima Cetak","color"=>"#00FF00"),
            array("name"=>"Aktifkan Kartu","color"=>"#FFFF00"),
            array("name"=>"Kartu Aktif","color"=>"#00FF00"),
            array("name"=>"Ditolak Daftar","color"=>"#FF0000"),
            array("name"=>"Ditolak Cetak","color"=>"#FF0000"),
            array("name"=>"Ditolak Aktif","color"=>"#FF0000")
        );

        foreach ($status_akses_array as $key => $value) {
            Status_Akses::firstOrCreate($value);
        }
    }




}
