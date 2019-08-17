<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAiInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER tr_New_Inventory_Data_Insert AFTER INSERT ON `new_inventory_data` FOR EACH ROW
            BEGIN
                INSERT INTO new_inventory_data_history SELECT * FROM new_inventory_data;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_New_Inventory_Data_Insert`');
    }
}
