<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventorySubListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_sub_list', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventory_list_id');
            $table->string('inventory_sub_list_name')->unique();
            $table->timestamps();

            $table->foreign('inventory_list_id', 'inventory_sub_list_id_fkey')
                ->references('id')->on('inventory_list')
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
        Schema::dropIfExists('inventory_sub_list');
    }
}
