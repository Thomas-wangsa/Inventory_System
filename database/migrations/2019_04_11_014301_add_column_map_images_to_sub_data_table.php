<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnMapImagesToSubDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_inventory_sub_data', function (Blueprint $table) {
            $table->unsignedInteger('map_id')->nullable()->after('sub_data_uuid');
            $table->unsignedInteger('map_images_id')->nullable()->after('map_id');

            $table->foreign('map_id', 'new_inventory_sub_data_map_id_fkey')
                ->references('id')->on('new_map')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('map_images_id', 'new_inventory_sub_data_map_images_id_fkey')
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
        Schema::table('new_inventory_sub_data', function (Blueprint $table) {
            //
        });
    }
}
