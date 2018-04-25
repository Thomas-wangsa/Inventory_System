<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_role', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('divisi');
            $table->unsignedInteger('jabatan')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('user_id', 'users_role_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('divisi', 'users_role_divisi_fkey')
                ->references('id')->on('divisi')
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
        Schema::dropIfExists('users_role');
    }
}
