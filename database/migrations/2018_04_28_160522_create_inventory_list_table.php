<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('inventory_name')->unique();
            $table->unsignedInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('updated_by', 'inventory_list_user_fkey')
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
        Schema::dropIfExists('inventory_list');
    }
}
