<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;


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
                "name"=>"thomas",
                "email"=>"thomas.wangsa@gmail.com",
                "password"=>bcrypt(123456)
            ),
            array(
                "name"=>"thomas3",
                "email"=>"thomas3.wangsa@gmail.com",
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
                "divisi"    => 2,
            );
            Users_Role::firstOrCreate($data_array);
        }
    }




}
