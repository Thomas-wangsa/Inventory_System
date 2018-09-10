<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pic_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vendor_name')->unique();
            $table->string('vendor_detail_name');
            $table->unsignedInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('updated_by', 'pic_list_user_fkey')
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
        Schema::dropIfExists('pic_list');
    }
}
