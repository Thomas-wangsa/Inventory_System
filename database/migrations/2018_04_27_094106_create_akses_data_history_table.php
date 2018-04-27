<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAksesDataHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses_data_history', function (Blueprint $table) {
            $table->unsignedInteger('id_akses_data');
            $table->unsignedInteger('status_akses');
            $table->unsignedInteger('updated_by');
            $table->text('comment')->nullable();
            $table->unsignedInteger('status_trigger')->default(0);
            $table->dateTime('trigger_at');

            $table->foreign('id_akses_data', 'akses_data_history_id_fkey')
                ->references('id')->on('akses_data')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('status_akses', 'akses_data_history_fkey')
                ->references('id')->on('status_akses')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('updated_by', 'akses_data_history_updated_fkey')
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
        Schema::dropIfExists('akses_data_history');
    }
}
