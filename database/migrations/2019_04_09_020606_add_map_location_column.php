<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMapLocationColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_inventory_sub_data', function (Blueprint $table) {
            $table->unsignedInteger('mapping_location_id')->nullable()->after('sub_data_uuid');

            $table->foreign('mapping_location_id', 'new_inventory_sub_data_mapping_location_id_fkey')
                ->references('id')->on('new_inventory_mapping_location')
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
