<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;
use App\Http\Models\Users_Detail;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Pic_Level;
use App\Http\Models\Pic_List;
use App\Http\Models\Status_Akses;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Inventory_Level;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Setting_List;
use App\Http\Models\Setting_Data;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{   
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {	

        $faker = Faker::create();
    	$divisi_array = array(
    		array("name"=>"administrator"),
            array("name"=>"pic"),
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
                "name"=>"superman",
                "email"=>"admin@gmail.com",
                "password"=>bcrypt(123456)
            )
        );

        $inventory_list_array = array(
            "inventory_name"=>"cctv",
            "updated_by"=>1
        );

        $pic_list_array = array(
            "vendor_name"=>"ccms",
            "vendor_detail_name"=>"PT ABC INDONESIA",
            "updated_by"=>1
        );

        foreach ($users_array as $key => $value) {
            Users::firstOrCreate($value); 
            if($key == 0) {
                Inventory_List::firstOrCreate($inventory_list_array);
                PIC_List::firstOrCreate($pic_list_array);
            }        
        }


        $akses_role_array = array(
            array("name"=>"staff pendaftaran"),
            array("name"=>"staff pencetakan"),
            array("name"=>"manager pendaftaran dan pencetakan"),
            array("name"=>"staff pengaktifan "),
            array("name"=>"manager pengaktifan "),
        );

        foreach ($akses_role_array as $key => $value) {
            Akses_Role::firstOrCreate($value);
        }


        $inventory_level_array = array(
            array("inventory_level_name"=>"staff"),
            array("inventory_level_name"=>"head")
        );

        foreach ($inventory_level_array as $key => $value) {
            Inventory_Level::firstOrCreate($value);
        }


        $pic_level_array = array(
            array("pic_level_name"=>"staff pic"),
            array("pic_level_name"=>"sponsor")
        );

        foreach ($pic_level_array as $key => $value) {
            Pic_Level::firstOrCreate($value);
        }

        $users = Users::all();

        foreach ($users as $key => $value) {
            $jabatan = 0;
            switch ($value->id) {
                case 1:
                    $divisi = 1;
                    $jabatan = 0;
                    break;
                case 2:
                case 3: 
                    $divisi     = 2;
                    $jabatan    = 2;
                    break;
                case 4:
                    $divisi     = 2;
                    $jabatan    = 3;
                    break;
                case 5:
                    $divisi     = 2;
                    $jabatan    = 4;
                    break;
                case 6:
                    $divisi     = 3;
                    $jabatan    = 1;
                    break;
                case 7:
                    $divisi     = 3;
                    $jabatan    = 2;
                    break;
                case 8:
                    $divisi     = 1;
                    $jabatan    = 0;
                    break;
                case 9:
                    $divisi     = 1;
                    $jabatan    = 0;
                    break;
                case 10:
                    $divisi     = 3;
                    $jabatan    = 2;
                    break;
                default:
                    $divisi     = 1;
                    $jabatan    = 0;
                    break;
            }

            
            $data_user_detail = array(
                "user_id"   => $value->id,
                "uuid"      => $faker->uuid,
                "foto"      => "images/template/default.png"
            );
            Users_Detail::firstOrCreate($data_user_detail);

            $data_user_role = array(
                "user_id"   => $value->id,
                "divisi"    => $divisi,
                "jabatan"   => $jabatan
            );
            Users_Role::firstOrCreate($data_user_role);

        }

        $status_akses_array = array(
            array("name"=>"Pending Sponsor","color"=>"#000000"),
            array("name"=>"Pending Daftar","color"=>"#000000"),
            array("name"=>"Pending Cetak","color"=>"#FFA500"),
            array("name"=>"Pending Manager Cetak","color"=>"#FFA500"),
            array("name"=>"Pending Aktif","color"=>"#00FF00"),
            array("name"=>"Pending Manager Pengaktifan","color"=>"#00FF00"),
            array("name"=>"Kartu Aktif","color"=>"#0000FF"),
            array("name"=>"Ditolak Sponsor","color"=>"#FF0000"),
            array("name"=>"Ditolak Daftar","color"=>"#FF0000"),
            array("name"=>"Ditolak Cetak","color"=>"#FF0000"),
            array("name"=>"Ditolak Manager Cetak","color"=>"#FF0000"),
            array("name"=>"Ditolak Aktif","color"=>"#FF0000"),
            array("name"=>"Ditolak Manager Pengaktifan","color"=>"#FF0000")
        );

        foreach ($status_akses_array as $key => $value) {
            Status_Akses::firstOrCreate($value);
        }

        


        $status_inventory_array = array(
            array("name"=>"Pending Head","color"=>"#FFA500"),
            array("name"=>"Inventory Aktif","color"=>"#0000FF"),
            array("name"=>"Ditolak Head","color"=>"#FF0000"),
        );

        foreach ($status_inventory_array as $key => $value) {
            Status_Inventory::firstOrCreate($value);
        }


        $setting_list_array = array(
            array('setting_name'  => 'edit background'),
            array('setting_name'  => 'upload excel'),
            array('setting_name'  => 'add new vendor'),
            array('setting_name'  => 'add new inventory'),
            array('setting_name'  => 'report level'),
        );
        foreach ($setting_list_array as $key => $value) {
            Setting_List::firstOrCreate($value);
        }
        
    }




}
