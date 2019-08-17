<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNewInventoryDataHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_inventory_data_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('new_inventory_data_id');
            $table->string('inventory_name');
            $table->unsignedInteger('group1');
            $table->unsignedInteger('group2');
            $table->unsignedInteger('group3');
            $table->unsignedInteger('group4')->nullable();
            $table->unsignedInteger('inventory_list_id');
            $table->unsignedInteger('inventory_level_id');

            $table->unsignedInteger('status');
            $table->unsignedInteger('status_data')->default(0);
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            
            $table->string('file_name_upload')->nullable();

            $table->string('tanggal_update_data')->nullable();
            $table->string('kategori')->nullable();
            $table->string('kode_gambar')->nullable();
            $table->string('dvr')->nullable();
            $table->string('lokasi_site')->nullable();

            $table->string('kode_lokasi')->nullable();
            $table->string('jenis_barang')->nullable();
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->string('model')->nullable();

            $table->string('serial_number')->nullable();
            $table->string('psu_adaptor')->nullable();
            $table->string('tahun_pembuatan')->nullable();
            $table->string('tahun_pengadaan')->nullable();
            $table->string('kondisi')->nullable();

            $table->string('deskripsi')->nullable();
            $table->string('asuransi')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('tanggal_retired')->nullable();
            $table->string('po')->nullable();

            $table->unsignedInteger('qty')->nullable();
            $table->string('keterangan')->nullable();

            
            $table->string('uuid');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('sheet_detail')->nullable();
            $table->timestamp('trigger_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('new_inventory_data_history');
    }
}
