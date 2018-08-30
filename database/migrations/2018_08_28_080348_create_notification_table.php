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
            $table->unsignedInteger('data_id');
            $table->unsignedInteger('status_data_id');
            $table->unsignedInteger('sub_notify_id');
            $table->unsignedInteger('is_read')->default(0);
            $table->timestamps();

            $table->foreign('user_id', 'notification_user_id_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('category', 'notification_category_fkey')
                ->references('id')->on('divisi')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('sub_notify_id', 'notification_status_notify_fkey')
                ->references('id')->on('sub_notify')
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
