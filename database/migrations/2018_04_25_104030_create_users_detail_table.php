<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_detail', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->uuid('uuid'); 
            $table->string('nik')->nullable();
            $table->string('foto')->nullable();
            $table->string('company')->nullable();
            $table->string('email_2')->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'users_detail_fkey')
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
        Schema::dropIfExists('users_detail');
    }
}
