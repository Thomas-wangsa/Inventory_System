<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewInventoryRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_inventory_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group1');
            $table->unsignedInteger('group2');
            $table->unsignedInteger('group3');
            $table->unsignedInteger('group4')->nullable();
            $table->unsignedInteger('inventory_list_id');
            $table->unsignedInteger('inventory_level_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id', 'new_inventory_role_users_id_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            
            $table->foreign('group1', 'new_group1_fkey')
                ->references('id')->on('group1')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('group2', 'new_group2_fkey')
                ->references('id')->on('group2')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('group3', 'new_group3_fkey')
                ->references('id')->on('group3')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('group4', 'new_group4_fkey')
                ->references('id')->on('group4')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('inventory_list_id', 'new_inventory_role_list_id_fkey')
                ->references('id')->on('inventory_list')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('inventory_level_id', 'new_inventory_role_level_id_fkey')
                ->references('id')->on('inventory_level')
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
        Schema::dropIfExists('new_inventory_role');
    }
}
