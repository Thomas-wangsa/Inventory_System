<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('akses_data_id');
            $table->unsignedInteger('status_akses_id');
            $table->unsignedInteger('read')->default(0);
            $table->timestamps();

            $table->foreign('user_id', 'notification_user_id_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('akses_data_id', 'notification_akses_data_fkey')
                ->references('id')->on('akses_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('status_akses_id', 'notification_status_akses_fkey')
                ->references('id')->on('status_akses')
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
        Schema::dropIfExists('notification');
    }
}
