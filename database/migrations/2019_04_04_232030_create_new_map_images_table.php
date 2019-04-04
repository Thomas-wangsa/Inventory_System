<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewMapImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_map_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('new_inventory_data_id');
            $table->string('images_name');
            $table->string('images');
            $table->string('images_notes')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('uuid'); 
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('new_inventory_data_id', 'new_map_images_new_inventory_data_id_fkey')
                ->references('id')->on('new_inventory_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('created_by', 'new_map_images_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'new_map_images_updated_fkey')
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
        Schema::dropIfExists('new_map_images');
    }
}
