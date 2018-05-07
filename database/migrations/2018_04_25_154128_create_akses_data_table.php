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
            $table->string('type');
            $table->string('name');
            $table->string('email');
            $table->unsignedInteger('status_data')->default(1);
            $table->unsignedInteger('status_akses');
            $table->unsignedInteger('updated_by');
            $table->string('divisi')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('nik')->nullable();
            $table->string('foto')->nullable();
            $table->uuid('uuid'); 
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('status_akses', 'akses_data_fkey')
                ->references('id')->on('status_akses')
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
