<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMapLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_location', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('map_id');
            $table->unsignedInteger('inventory_data_id');
            $table->string('image_location');
            $table->string('map_location_uuid');
            // $table->string('form_x')->nullable();
            // $table->string('form_y')->nullable();
            $table->unsignedInteger('status_data')->default(1);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->timestamps();


            $table->foreign('map_id', 'map_location_map_id_fkey')
                ->references('id')->on('map')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('inventory_data_id', 'map_location_inventory_data_id_fkey')
                ->references('id')->on('inventory_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('created_by', 'map_location_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'map_location_updated_fkey')
                ->references('id')->on('users')
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
        Schema::dropIfExists('map_location');
    }
}
