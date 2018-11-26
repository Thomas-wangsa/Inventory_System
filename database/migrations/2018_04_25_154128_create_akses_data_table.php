<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAksesDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('request_type');
            $table->unsignedInteger('register_type');
            $table->string('name');
            $table->string('email');
            $table->string('nik')->nullable();
            $table->unsignedInteger('pic_list_id')->nullable();
            $table->unsignedInteger('admin_room_list_id')->nullable();
            // 
            // 1 = on progress
            // 2 = rejected
            // 3 = done
            // 4 = deactivated
            // 5 = expired
            $table->unsignedInteger('status_data')->default(1); 

            $table->unsignedInteger('status_akses');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            $table->string('divisi')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('no_access_card')->nullable();
            $table->string('floor')->nullable();
            $table->string('date_start')->nullable();
            $table->string('date_end')->nullable();
            $table->string('po')->nullable();
            $table->string('foto')->nullable();
            $table->string('uuid'); 
            $table->text('additional_note')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('request_type', 'akses_data_request_type_fkey')
                ->references('id')->on('access_card_request')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('register_type', 'akses_data_register_type_fkey')
                ->references('id')->on('access_card_register_status')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('status_akses', 'akses_data_fkey')
                ->references('id')->on('status_akses')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('created_by', 'akses_data_created_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'akses_data_updated_fkey')
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
        Schema::dropIfExists('akses_data');
    }
}
