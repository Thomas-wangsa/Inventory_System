<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Status_Akses;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Inventory_Level;
use App\Http\Models\Inventory_List;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {	
    	$divisi_array = array(
    		array("name"=>"administrator"),
    		array("name"=>"akses"),
    		array("name"=>"inventory")
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

        $inventory_list_array = array(
            "inventory_name"=>"cctv",
            "updated_by"=>1
        );
        foreach ($users_array as $key => $value) {
            Users::firstOrCreate($value);
            if($key == 1) {
                Inventory_List::firstOrCreate($inventory_list_array);
            }
            
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

        $inventory_level_array = array(
            array("inventory_level_name"=>"head"),
            array("inventory_level_name"=>"staff")
        );

        foreach ($inventory_level_array as $key => $value) {
            Inventory_Level::firstOrCreate($value);
        }


        $status_akses_array = array(
            array("name"=>"Pending Head","color"=>"#FFFF00"),
            array("name"=>"Diterima Head","color"=>"#00FF00"),
            array("name"=>"Diterima Admin","color"=>"#FFFF00"),
            array("name"=>"Ditolak Head","color"=>"#FF0000"),
            array("name"=>"Ditolak Admin","color"=>"#FF0000")
        );

        foreach ($status_akses_array as $key => $value) {
            Status_Inventory::firstOrCreate($value);
        }

    }




}
