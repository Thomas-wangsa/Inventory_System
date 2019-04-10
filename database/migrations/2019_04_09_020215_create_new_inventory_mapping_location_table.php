<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInventoryMappingLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_inventory_mapping_location', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('new_inventory_data_id');
            $table->unsignedInteger('new_map_id');
            $table->unsignedInteger('new_map_images_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('new_inventory_data_id', 'new_inventory_mapping_location_new_inventory_data_id_fkey')
                ->references('id')->on('new_inventory_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('new_map_id', 'new_inventory_mapping_location_new_map_id_fkey')
                ->references('id')->on('new_map')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
                
            $table->foreign('new_map_images_id', 'new_inventory_mapping_location_new_map_images_id_fkey')
                ->references('id')->on('new_map_images')
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
        Schema::dropIfExists('new_inventory_mapping_location');
    }
}
