<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('count');
            $table->unsignedInteger('inventory_sub_list_id');
            $table->text('comment')->nullable();
            $table->string('serial_number');
            $table->string('location');
            $table->unsignedInteger('status_inventory');
            $table->unsignedInteger('updated_by');
            $table->timestamps();

            $table->foreign('inventory_sub_list_id', 'inventory_data_sub_id_fkey')
                ->references('id')->on('inventory_sub_list')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('updated_by', 'inventory_data_updated_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('status_inventory', 'inventory_data_status_fkey')
                ->references('id')->on('status_inventory')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory_data');
    }
}
