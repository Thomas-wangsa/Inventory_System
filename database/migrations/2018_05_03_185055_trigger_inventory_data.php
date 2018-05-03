<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerInventoryData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventory_data', function (Blueprint $table) {
            DB::unprepared('
            CREATE TRIGGER tr_Inventory_Data_INSERT AFTER INSERT ON `inventory_data` FOR EACH ROW
            BEGIN
                INSERT INTO inventory_data_history (`id_inventory_data`, `count`, `inventory_sub_list_id`, `comment`,`serial_number`,`location`,`status_inventory`,`updated_by`,`status_trigger`,`trigger_at`) 
                VALUES (NEW.id, NEW.count,NEW.inventory_sub_list_id,NEW.comment,NEW.serial_number,NEW.location,NEW.status_inventory, NEW.updated_by,1,now());
            END
            ');


            DB::unprepared('
            CREATE TRIGGER tr_Inventory_Data_UPDATE AFTER UPDATE ON `inventory_data` FOR EACH ROW
            BEGIN
                INSERT INTO inventory_data_history (`id_inventory_data`, `count`, `inventory_sub_list_id`, `comment`,`serial_number`,`location`,`status_inventory`,`updated_by`,`status_trigger`,`trigger_at`) 
                VALUES (NEW.id, NEW.count,NEW.inventory_sub_list_id,NEW.comment,NEW.serial_number,NEW.location,NEW.status_inventory, NEW.updated_by,2,now());
            END
            ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventory_data', function (Blueprint $table) {
            DB::unprepared('DROP TRIGGER `tr_Inventory_Data_INSERT`');
            DB::unprepared('DROP TRIGGER `tr_Inventory_Data_UPDATE`');
        });
    }
}
