<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;

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
        



    }




}
