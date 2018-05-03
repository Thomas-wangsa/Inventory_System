<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDataHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_data_history', function (Blueprint $table) {
            $table->unsignedInteger('id_inventory_data');
            $table->unsignedInteger('count');
            $table->unsignedInteger('inventory_sub_list_id');
            $table->text('comment')->nullable();
            $table->string('serial_number');
            $table->string('location');
            $table->unsignedInteger('status_inventory');
            $table->unsignedInteger('updated_by');
            $table->unsignedInteger('status_trigger')->default(0);
            $table->dateTime('trigger_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_data_history');
    }
}
