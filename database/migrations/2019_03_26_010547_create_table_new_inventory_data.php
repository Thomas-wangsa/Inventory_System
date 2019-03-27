<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNewInventoryData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_inventory_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_name');
            $table->unsignedInteger('group1');
            $table->unsignedInteger('group2');
            $table->unsignedInteger('group3');
            $table->unsignedInteger('group4')->nullable();
            $table->unsignedInteger('inventory_list_id');
            $table->unsignedInteger('status');
            $table->unsignedInteger('main_quantity');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('uuid');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('group1', 'new_inventory_data_group1_fkey')
                ->references('id')->on('group1')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('group2', 'new_inventory_data_group2_fkey')
                ->references('id')->on('group2')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('group3', 'new_inventory_data_group3_fkey')
                ->references('id')->on('group3')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('group4', 'new_inventory_data_group4_fkey')
                ->references('id')->on('group4')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('inventory_list_id', 'new_inventory_data_list_id_fkey')
                ->references('id')->on('inventory_list')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('status', 'new_inventory_data_status_fkey')
                ->references('id')->on('status_inventory')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('created_by', 'new_inventory_data_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'new_inventory_data_updated_fkey')
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
        Schema::dropIfExists('new_inventory_data');
    }
}
