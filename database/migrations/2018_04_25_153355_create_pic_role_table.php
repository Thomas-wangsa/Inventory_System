<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePicRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pic_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('pic_list_id');
            $table->unsignedInteger('pic_level_id');
            $table->boolean('status')->default(1);
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id', 'pic_role_users_id_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('pic_list_id', 'pic_role_list_id_fkey')
                ->references('id')->on('pic_list')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('pic_level_id', 'pic_role_level_id_fkey')
                ->references('id')->on('pic_level')
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
        Schema::dropIfExists('pic_role');
    }
}
