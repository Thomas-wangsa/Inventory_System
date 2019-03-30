<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInventorySubDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_inventory_sub_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('new_inventory_data_id');
            $table->string('sub_data_status');
            $table->string('x_point')->nullable();
            $table->string('y_point')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('sub_data_uuid')->unique();
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('new_inventory_data_id', 'new_inventory_sub_data_new_inventory_data_id_fkey')
                ->references('id')->on('new_inventory_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('created_by', 'new_inventory_sub_data_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'new_inventory_sub_data_updated_fkey')
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
        Schema::dropIfExists('new_inventory_sub_data');
    }
}
