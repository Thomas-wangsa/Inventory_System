<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory_data', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('inventory_list_id')->nullable();
            $table->unsignedInteger('status_data')->default(1);
            $table->unsignedInteger('status_inventory');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');
            
            $table->string('file_name_upload');
            $table->string('title_upload');

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

            $table->string('qty')->nullable();
            $table->string('keterangan')->nullable();

            
            $table->uuid('uuid');
            $table->text('comment')->nullable(); 
            $table->timestamps();

            $table->foreign('inventory_list_id', 'inventory_list_id_fkey')
                ->references('id')->on('inventory_list')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('updated_by', 'inventory_data_updated_fkey')
                ->references('id')->on('users')
                ->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->foreign('status_inventory', 'inventory_data_status_fkey')
                ->references('id')->on('status_inventory')
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
        Schema::dropIfExists('inventory_data');
    }
}
