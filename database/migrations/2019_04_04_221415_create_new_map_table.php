<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_map', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('new_inventory_data_id');
            $table->string('map_name');
            $table->string('map_images');
            $table->string('map_notes')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('uuid'); 
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('new_inventory_data_id', 'new_map_new_inventory_data_id_fkey')
                ->references('id')->on('new_inventory_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('created_by', 'new_map_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'new_map_updated_fkey')
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
        Schema::dropIfExists('new_map');
    }
}
