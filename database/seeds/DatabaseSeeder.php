<?php

use Illuminate\Database\Seeder;


use App\Http\Models\Divisi;
use App\Http\Models\Design;
use App\Http\Models\Users;
use App\Http\Models\Users_Detail;
use App\Http\Models\Users_Role;
use App\Http\Models\Akses_Role;
use App\Http\Models\Akses_Data;
use App\Http\Models\Pic_Level;
use App\Http\Models\Pic_List;
use App\Http\Models\Pic_Role;
use App\Http\Models\Status_Akses;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Inventory_Level;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Setting_List;
use App\Http\Models\Setting_Data;
use App\Http\Models\Sub_Notify;
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
            array("name"=>"access"),
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
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber,
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.pic@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"sponsor.pic@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"viewer.pic@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.pendaftaran@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.pencetakan@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"manager.pendaftaran.pencetakan@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.pengaktifan@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"manager.pengaktifan@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.inventoryA@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"head.inventoryA@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"viewer.inventoryA@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"dummy.pic@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"dummy.inv@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"dummy.data@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
        );

        $inventory_list_array = array(
            "inventory_name"=>"cctv",
            "inventory_detail_name"=>"CCTV Management",
            "updated_by"=>1
        );

        $inventory_list_array_2 = array(
            "inventory_name"=>"ccms",
            "inventory_detail_name"=>"CCMS BUILDING",
            "updated_by"=>1
        );

        $pic_list_array = array(
            "vendor_name"=>"abc",
            "vendor_detail_name"=>"PT ABC THAILAND",
            "updated_by"=>1
        );
        $pic_list_array_2 = array(
            "vendor_name"=>"bulkan",
            "vendor_detail_name"=>"PT BULKAN SINGAPORE",
            "updated_by"=>1
        );

        $pic_list_array_3 = array(
            "vendor_name"=>"tnk",
            "vendor_detail_name"=>"PT TNK INDONESIA",
            "updated_by"=>1
        );

        $pic_list_array_4 = array(
            "vendor_name"=>"amr",
            "vendor_detail_name"=>"PT AM AMSTERDAM",
            "updated_by"=>1
        );

        foreach ($users_array as $key => $value) {
            Users::firstOrCreate($value); 
            if($key == 0) {
                // Inventory_List::firstOrCreate($inventory_list_array);
                // Inventory_List::firstOrCreate($inventory_list_array_2);
                PIC_List::firstOrCreate($pic_list_array);
                PIC_List::firstOrCreate($pic_list_array_2);
                PIC_List::firstOrCreate($pic_list_array_3);
                PIC_List::firstOrCreate($pic_list_array_4);
            }        
        }


        $notification_status = array(
            array("name"=>"new access card requested"),
            array("name"=>"access card processed"),
            array("name"=>"access card expiry notify"),
            array("name"=>"new inventory requested"),
            array("name"=>"inventory processed")
        );

        foreach($notification_status as $key=>$val) {
            Sub_Notify::firstOrCreate($val);
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
            array("inventory_level_name"=>"staff inventory"),
            array("inventory_level_name"=>"head inventory"),
            array("inventory_level_name"=>"viewer inventory")
        );

        foreach ($inventory_level_array as $key => $value) {
            Inventory_Level::firstOrCreate($value);
        }


        $pic_level_array = array(
            array("pic_level_name"=>"staff pic"),
            array("pic_level_name"=>"sponsor pic")
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
                    $divisi = 2;
                    $jabatan = 1;
                    break;
                case 3: 
                    $divisi     = 2;
                    $jabatan    = 2;
                    break;
                case 4:
                    $divisi     = 2;
                    $jabatan    = 2;
                    break;
                case 5:
                    $divisi     = 3;
                    $jabatan    = 1;
                    break;
                case 6:
                    $divisi     = 3;
                    $jabatan    = 2;
                    break;
                case 7:
                    $divisi     = 3;
                    $jabatan    = 3;
                    break;
                case 8:
                    $divisi     = 3;
                    $jabatan    = 4;
                    break;
                case 9:
                    $divisi     = 3;
                    $jabatan    = 5;
                    break;
                // case 10:
                //     $divisi     = 4;
                //     $jabatan    = 1;
                //     break;
                // case 11:
                //     $divisi     = 4;
                //     $jabatan    = 2;
                //     break;
                // case 12:
                //     $divisi     = 4;
                //     $jabatan    = 3;
                //     break;
                case 13:
                    $divisi     = 2;
                    $jabatan    = 1;
                    break;
                // case 14:
                //     $divisi     = 4;
                //     $jabatan    = 3;
                //     break; 
                case 15:
                    $divisi     = 2;
                    $jabatan    = 1;
                    break;    
                default:
                    $divisi     = 1;
                    $jabatan    = 0;
                    break;
            }

            
            if($divisi == 2) {

                $pic_role_array = array(
                    "user_id"              =>$value->id,
                    "pic_list_id"     => Pic_List::first()->id,
                    "pic_level_id"    => $jabatan
                );

                $new_pic_role = Pic_Role::firstOrCreate($pic_role_array);

                $jabatan = $new_pic_role->id;
            }

            if($divisi == 4) {

                $inventory_role_array = array(
                    "user_id"              =>$value->id,
                    "inventory_list_id"     => Inventory_List::first()->id,
                    "inventory_level_id"    => $jabatan
                );

                $new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);

                $jabatan = $new_inventory_role->id;
            }


            $data_user_detail = array(
                "user_id"   => $value->id,
                "uuid"      => time().$faker->uuid,
                "foto"      => "images/template/default.png",
                "nik"       => $faker->phoneNumber,
                "company"   => $faker->company
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
            array("name"=>"pending sponsor","color"=>"#FC7206"),
            array("name"=>"pending staff pendaftaran","color"=>"#334703"),
            array("name"=>"pending staff pencetakan","color"=>"#057242"),
            array("name"=>"pending manager pencetakan","color"=>"#034657"),
            array("name"=>"pending staff pengaktifan","color"=>"#385EE1"),
            array("name"=>"pending manager pengaktifan","color"=>"#9618D1"),
            array("name"=>"access card is active","color"=>"#0000FF"),
            array("name"=>"rejected by sponsor","color"=>"#FF0000"),
            array("name"=>"rejected by staff pendaftaran","color"=>"#FF0000"),
            array("name"=>"rejected by staff pencetakan","color"=>"#FF0000"),
            array("name"=>"rejected by manager pencetakan","color"=>"#FF0000"),
            array("name"=>"rejected by staff pengaktifan","color"=>"#FF0000"),
            array("name"=>"rejected by manager pengaktifan","color"=>"#FF0000"),
            array("name"=>"access card is expired","color"=>"#FF0000"),
        );

        foreach ($status_akses_array as $key => $value) {
            Status_Akses::firstOrCreate($value);
        }

        
        $status_inventory_array = array(
            array("name"=>"pending head inventory","color"=>"#FFA500"),
            array("name"=>"pending administrator","color"=>"#9618D1"),
            array("name"=>"inventory is active","color"=>"#0000FF"),
            array("name"=>"rejected by head inventory","color"=>"#FF0000"),
            array("name"=>"rejected by administrator","color"=>"#FF0000"),
        );

        foreach ($status_inventory_array as $key => $value) {
            Status_Inventory::firstOrCreate($value);
        }


        $setting_list_array = array(
            array('setting_name'  => 'edit background'),
            array('setting_name'  => 'upload excel'),
            array('setting_name'  => 'add new vendor'),
            array('setting_name'  => 'add new inventory'),
            array('setting_name'  => 'access report '),
            array('setting_name'  => 'inventory report '),
            array('setting_name'  => 'add new floor plan'),
        );
        foreach ($setting_list_array as $key => $value) {
            Setting_List::firstOrCreate($value);
        }

        // $full_data = array();
        // for($i=0;$i<=100;$i++) {

        //     $type_daftar = "vendor";

        //     $akses_data = array(
        //         "type_daftar"   => $type_daftar,
        //         "name"          => $faker->name,
        //         "email"         => $faker->email,
        //         "nik"           => $faker->phoneNumber,
        //         "status_akses"  => $faker->numberBetween(1,13),
        //         "created_by"    => 15,
        //         "updated_by"    => 15,
        //         "no_access_card"=> $faker->phoneNumber,
        //         "date_start"    => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         "date_end"      => $faker->date($format = 'Y-m-d', $max = 'now'),
        //         "foto"          => "/images/akses/1536255441bdca178a-b56c-31d0-94fd-cf6052f0da64.jpg",
        //         "additional_note"=> $faker->text,
        //         "comment"=> $faker->text,
        //         "uuid"          => $faker->uuid,
        //         "created_at"    => $faker->dateTime($max = 'now'),
        //         "updated_at"    => $faker->dateTime($max = 'now')
        //     );

        //     if($type_daftar == "vendor") {
        //         $akses_data['po']          = "/images/akses/1536255441bdca178a-b56c-31d0-94fd-cf6052f0da64.jpg";
        //         $akses_data['pic_list_id'] = $faker->numberBetween(1,4);
        //         $akses_data['floor']       = $faker->numberBetween(1,26);
        //     }

        //     array_push($full_data,$akses_data);
        // }

        // Akses_Data::insert($full_data);
        
    }




}
