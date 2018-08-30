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
            $table->unsignedInteger('category');
            $table->unsignedInteger('notification_data_id');
            $table->unsignedInteger('status_data_id');
            $table->unsignedInteger('status_notify');
            $table->unsignedInteger('is_read')->default(0);
            $table->timestamps();

            $table->foreign('user_id', 'notification_user_id_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('category', 'notification_category_fkey')
                ->references('id')->on('divisi')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('status_notify', 'notification_status_notify_fkey')
                ->references('id')->on('notification_status')
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
