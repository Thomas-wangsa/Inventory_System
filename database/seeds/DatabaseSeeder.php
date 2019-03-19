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
use App\Http\Models\Admin_Room_List;
use App\Http\Models\Admin_Room_Role;
use App\Http\Models\Status_Akses;
use App\Http\Models\Status_Inventory;
use App\Http\Models\Inventory_Level;
use App\Http\Models\Inventory_List;
use App\Http\Models\Inventory_Role;
use App\Http\Models\Setting_List;
use App\Http\Models\Setting_Data;
use App\Http\Models\Sub_Notify;
use App\Http\Models\AccessCardRegisterStatus;
use App\Http\Models\AccessCardRequest;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{   
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {	


        $accesscardregisterstatus_array = array(
            array("register_name"=>"permanent"),
            array("register_name"=>"non permanent"),
        );
        foreach ($accesscardregisterstatus_array as $key => $value) {
            AccessCardRegisterStatus::firstOrCreate($value);
        }
        
        

        $accesscardrequest_array = array(
            array("request_name"=>"new access card"),
            array("request_name"=>"extending access card"),
            array("request_name"=>"access card broken"),
            array("request_name"=>"access card lost"),
            array("request_name"=>"change leveling access card"),
        );
        
        foreach ($accesscardrequest_array as $key => $value) {
            AccessCardRequest::firstOrCreate($value);
        }

        $faker = Faker::create();
    	$divisi_array = array(
    		array("name"=>"administrator"),
            array("name"=>"pic sponsor"),
            array("name"=>"access card"),
    		array("name"=>"old inventory"),
            array("name"=>"admin room"),
            array("name"=>"inventory"),
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
                // "name"=>"sir kat",
                // "email"=>"katimin@indosatooredoo.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber,
            ),
            array(
                "name"=>$faker->name,
                "email"=>"requester.alphabet@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"requester.bravos@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"requester.choco@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"requester.delta@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"pic.alphabet@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"pic.bravos@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"pic.choco@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"pic.delta@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),

            array(
                "name"=>$faker->name,
                "email"=>"verification@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"approval.verification@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"card.printing@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"approval.activation@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.activation@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.cctv.inventory@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"head.cctv.inventory@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"staff.travo.inventory@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"head.travo.inventory@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"admin.room.lobby@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"admin.room.ccms@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"admin.room.nathan@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            array(
                "name"=>$faker->name,
                "email"=>"admin.room.mitra@gmail.com",
                "password"=>bcrypt(123456),
                "mobile"=>$faker->phoneNumber
            ),
            // array(
            //     "name"=>$faker->name,
            //     "email"=>"dummy.pic@gmail.com",
            //     "password"=>bcrypt(123456),
            //     "mobile"=>$faker->phoneNumber
            // ),
            // array(
            //     "name"=>$faker->name,
            //     "email"=>"dummy.inv@gmail.com",
            //     "password"=>bcrypt(123456),
            //     "mobile"=>$faker->phoneNumber
            // ),
            // array(
            //     "name"=>$faker->name,
            //     "email"=>"dummy.data@gmail.com",
            //     "password"=>bcrypt(123456),
            //     "mobile"=>$faker->phoneNumber
            // ),
        );



        $pic_list_array = array(
            "vendor_name"=>"alphabet",
            "vendor_detail_name"=>"PT ALPHABET INDONESIA",
            "updated_by"=>1
        );
        $pic_list_array_2 = array(
            "vendor_name"=>"bravos",
            "vendor_detail_name"=>"PT BRAVOS SINGAPORE",
            "updated_by"=>1
        );

        $pic_list_array_3 = array(
            "vendor_name"=>"choco",
            "vendor_detail_name"=>"PT CHOCO CHOCO INDONESIA",
            "updated_by"=>1
        );

        $pic_list_array_4 = array(
            "vendor_name"=>"delta",
            "vendor_detail_name"=>"PT DON XIAMIE",
            "updated_by"=>1
        );



        $admin_room_list_array = array(
            "admin_room"=>"lobby",
            "admin_room_detail"=>"LOBBY UTAMA",
            "updated_by"=>1
        );
        $admin_room_list_array_3 = array(
            "admin_room"=>"ccms",
            "admin_room_detail"=>"RUANGAN PAK KATIMIN",
            "updated_by"=>1
        );
        $admin_room_list_array_2 = array(
            "admin_room"=>"nathan",
            "admin_room_detail"=>"RUANGAN PAK ROBERT",
            "updated_by"=>1
        );

        $admin_room_list_array_4 = array(
            "admin_room"=>"mitra",
            "admin_room_detail"=>"RUANGAN MITRA",
            "updated_by"=>1
        );




        $inventory_list_array = array(
            "inventory_name"=>"cctv",
            "inventory_detail_name"=>"CCTV Management",
            "updated_by"=>1
        );

        $inventory_list_array_2 = array(
            "inventory_name"=>"travo",
            "inventory_detail_name"=>"TRAVO EQUIPMENT",
            "updated_by"=>1
        );

        

        foreach ($users_array as $key => $value) {
            Users::firstOrCreate($value); 
            if($key == 0) {
                PIC_List::firstOrCreate($pic_list_array);
                PIC_List::firstOrCreate($pic_list_array_2);
                PIC_List::firstOrCreate($pic_list_array_3);
                PIC_List::firstOrCreate($pic_list_array_4);
                Inventory_List::firstOrCreate($inventory_list_array);
                Inventory_List::firstOrCreate($inventory_list_array_2);
                Admin_Room_List::firstOrCreate($admin_room_list_array);
                Admin_Room_List::firstOrCreate($admin_room_list_array_2);
                Admin_Room_List::firstOrCreate($admin_room_list_array_3);
                Admin_Room_List::firstOrCreate($admin_room_list_array_4);
                
            }        
        }


        // $notification_status = array(
        //     array("name"=>"new access card requested"),
        //     array("name"=>"access card processed"),
        //     array("name"=>"access card expiry notify"),
        //     array("name"=>"new inventory requested"),
        //     array("name"=>"inventory processed")
        // );

        // foreach($notification_status as $key=>$val) {
        //     Sub_Notify::firstOrCreate($val);
        // }

        $akses_role_array = array(
            array("name"=>"verification"),
            array("name"=>"approval verification"),
            array("name"=>"card printing"),
            array("name"=>"approval activation"),
            array("name"=>"staff activation"),
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
            array("pic_level_name"=>"requester"),
            array("pic_level_name"=>"sponsor pic"),
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
                    $list   = 1;
                    $jabatan = 1;
                    break;
                case 3: 
                    $divisi     = 2;
                    $list       = 2;
                    $jabatan    = 1;
                    break;
                case 4:
                    $divisi     = 2;
                    $list       = 3;
                    $jabatan    = 1;
                    break;
                case 5:
                    $divisi     = 2;
                    $list       = 4;
                    $jabatan    = 1;
                    break;
                case 6:
                    $divisi     = 2;
                    $list       = 1;
                    $jabatan    = 2;
                    break;
                case 7:
                    $divisi     = 2;
                    $list       = 2;
                    $jabatan    = 2;
                    break;
                case 8:
                    $divisi     = 2;
                    $list       = 3;
                    $jabatan    = 2;
                    break;
                case 9:
                    $divisi     = 2;
                    $list       = 4;
                    $jabatan    = 2;
                    break;
                case 10:
                    $divisi     = 3;
                    $jabatan    = 1;
                    break;
                case 11:
                    $divisi     = 3;
                    $jabatan    = 2;
                    break;
                case 12:
                    $divisi     = 3;
                    $jabatan    = 3;
                    break;
                case 13:
                    $divisi     = 3;
                    $jabatan    = 4;
                    break;
                case 14:
                    $divisi     = 3;
                    $jabatan    = 5;
                    break;
                case 15:
                    $divisi     = 4;
                    $list       = 1;
                    $jabatan    = 1;
                    break;
                case 16:
                    $divisi     = 4;
                    $list       = 1;
                    $jabatan    = 2;
                    break;
                case 17:
                    $divisi     = 4;
                    $list       = 2;
                    $jabatan    = 1;
                    break;
                case 18:
                    $divisi     = 4;
                    $list       = 2;
                    $jabatan    = 2;
                    break;
                case 19:
                    $divisi     = 5;
                    $list       = 1;
                    break;
                case 20:
                    $divisi     = 5;
                    $list       = 2;
                    break;
                case 21:
                    $divisi     = 5;
                    $list       = 3;
                    break;
                case 22:
                    $divisi     = 5;
                    $list       = 4;
                    break;
                // case 11:
                //     $divisi     = 4;
                //     $jabatan    = 2;
                //     break;
                // case 12:
                //     $divisi     = 4;
                //     $jabatan    = 3;
                //     break;
                // case 13:
                //     $divisi     = 2;
                //     $jabatan    = 1;
                //     break;
                // case 14:
                //     $divisi     = 4;
                //     $jabatan    = 3;
                //     break; 
                // case 15:
                //     $divisi     = 2;
                //     $jabatan    = 1;
                //     break;    
                default:
                    $divisi     = 1;
                    $jabatan    = 0;
                    break;
            }

            
            if($divisi == 2) {

                $pic_role_array = array(
                    "user_id"         => $value->id,
                    "pic_list_id"     => $list,
                    "pic_level_id"    => $jabatan
                );

                $new_pic_role = Pic_Role::firstOrCreate($pic_role_array);

                $jabatan = $new_pic_role->id;
            }

            if($divisi == 4) {

                $inventory_role_array = array(
                    "user_id"               => $value->id,
                    "inventory_list_id"     => $list,
                    "inventory_level_id"    => $jabatan
                );

                $new_inventory_role = Inventory_Role::firstOrCreate($inventory_role_array);

                $jabatan = $new_inventory_role->id;
            }


            if($divisi == 5) {

                $admin_room_role_array = array(
                    "user_id"               => $value->id,
                    "admin_room_list_id"     => $list
                );

                $new_admin_room_role = Admin_Room_Role::firstOrCreate($admin_room_role_array);

                $jabatan = $new_admin_room_role->id;
            }


            $data_user_detail = array(
                "user_id"   => $value->id,
                "uuid"      => time().$faker->uuid,
                "foto"      => "/images/template/default.png",
                "nik"       => $faker->uuid,
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

        // $status_akses_array = array(
        //     array("name"=>"pending sponsor","color"=>"#FC7206"),
        //     array("name"=>"pending staff pendaftaran","color"=>"#334703"),
        //     array("name"=>"pending staff pencetakan","color"=>"#057242"),
        //     array("name"=>"pending manager pencetakan","color"=>"#034657"),
        //     array("name"=>"pending staff pengaktifan","color"=>"#385EE1"),
        //     array("name"=>"pending manager pengaktifan","color"=>"#9618D1"),
        //     array("name"=>"access card is active","color"=>"#0000FF"),
        //     array("name"=>"rejected by sponsor","color"=>"#FF0000"),
        //     array("name"=>"rejected by staff pendaftaran","color"=>"#FF0000"),
        //     array("name"=>"rejected by staff pencetakan","color"=>"#FF0000"),
        //     array("name"=>"rejected by manager pencetakan","color"=>"#FF0000"),
        //     array("name"=>"rejected by staff pengaktifan","color"=>"#FF0000"),
        //     array("name"=>"rejected by manager pengaktifan","color"=>"#FF0000"),
        //     array("name"=>"access card is expired","color"=>"#FF0000"),
        //     array("name"=>"access card is deactivated","color"=>"#FF0000"),
        //     array("name"=>"pending owner","color"=>"#FC7206"),
        //     array("name"=>"rejected by owner","color"=>"#FF0000"),
        // );

        $status_akses_array = array(
            array("name"=>"pending pic sponsor","color"=>"#FC7206"),
            array("name"=>"pending verification","color"=>"#334703"),
            array("name"=>"pending approval verification","color"=>"#057242"),
            array("name"=>"pending set access card number","color"=>"#034657"),
            array("name"=>"pending approval activation","color"=>"#385EE1"),
            array("name"=>"pending admin room","color"=>"#385EE1"),
            array("name"=>"pending activation","color"=>"#385EE1"),
            array("name"=>"pending pick up access card","color"=>"#9618D1"),
            array("name"=>"access card is active","color"=>"#0000FF"),

            array("name"=>"reject pic sponsor","color"=>"#FF0000"),
            array("name"=>"reject verification","color"=>"#FF0000"),
            array("name"=>"reject approval verification","color"=>"#FF0000"),
            array("name"=>"reject set access card number","color"=>"#FF0000"),
            array("name"=>"reject approval activation","color"=>"#FF0000"),
            array("name"=>"reject admin room","color"=>"#FF0000"),
            array("name"=>"reject activation","color"=>"#FF0000"),
            array("name"=>"reject pick up access card","color"=>"#FF0000"),
            array("name"=>"access card is deactived","color"=>"#FF0000"),
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
            array('setting_name'  => 'add new pic category'),
            array('setting_name'  => 'add new inventory'),
            array('setting_name'  => 'access report '),
            array('setting_name'  => 'inventory report '),
            array('setting_name'  => 'add new floor plan'),
            array('setting_name'  => 'add new admin room category'),
             array('setting_name'  => 'config'),
        );
        foreach ($setting_list_array as $key => $value) {
            Setting_List::firstOrCreate($value);
        }

        $full_data = array();
        
        //$request_type   = 1;
        for($i=0;$i<=500;$i++) {
            $request_type   = $faker->numberBetween(1,5);
            $register_type  = $faker->numberBetween(1,2);
            
            $akses_data = array(
            "request_type"  => $request_type,
            "register_type" => $register_type,

            "name"          => $faker->name,
            "email"         => $faker->email,
            "nik"           => $faker->uuid,
            "date_start"    => $faker->date($format = 'Y-m-d', $max = 'now'),
            "date_end"      => $faker->date($format = 'Y-m-d', $max = 'now'),
            "pic_list_id"   => $faker->numberBetween(1,Pic_List::count()),
            "foto"          => "/images/akses/1543412672c336ecdd-ab95-3ae0-9bd6-a671d87c03d6.jpg",
            "po"          => "/images/akses/1543412672c336ecdd-ab95-3ae0-9bd6-a671d87c03d6.jpg",
            
            "additional_note"=> $faker->text,
            "comment"        => $faker->text,

            "status_akses"  => 1,
            "created_by"    => $faker->numberBetween(1,Users::count()),
            "updated_by"    => $faker->numberBetween(1,Users::count()),
            
            
            "divisi"        => $faker->jobTitle,
            "jabatan"       => $faker->jobTitle,
            "floor"         => $faker->address,
            
            "no_access_card"=> null,
            "admin_room_list_id"=>null,
            "uuid"          => $faker->uuid,
            "created_at"    => $faker->dateTime($max = 'now'),
            "updated_at"    => $faker->dateTime($max = 'now'),

            "comment"       => null
            );

            if($request_type == 1 || $request_type == 3 || $request_type == 4) {
                $akses_data['status_akses'] = $faker->numberBetween(1,18);
            } else if($request_type == 2) {
                $akses_data['status_akses'] = $faker->numberBetween(1,3);
            } else if($request_type == 5) {
                if($register_type == 1) {
                    $akses_data['status_akses'] = 5;  
                } else if($register_type == 2) {
                    $akses_data['status_akses'] = 1;
                }
            }

            if($akses_data['status_akses'] > 4 || $request_type == 2) {
                $akses_data['no_access_card'] = $faker->uuid;
            }

            if($akses_data['status_akses'] == 6) {
                $akses_data['admin_room_list_id'] = $faker->numberBetween(1,Admin_Room_List::count());
            }

            if($akses_data['status_akses'] > 9) {
                $akses_data['comment'] = $faker->text;
            }



            array_push($full_data,$akses_data);
        }

        Akses_Data::insert($full_data);
        
    }




}
