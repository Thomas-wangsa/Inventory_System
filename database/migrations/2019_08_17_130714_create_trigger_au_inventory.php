<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerAuInventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         DB::unprepared('
        CREATE TRIGGER tr_New_Inventory_Data_Update AFTER UPDATE ON `new_inventory_data` FOR EACH ROW
            BEGIN
                INSERT INTO new_inventory_data_history (
                new_inventory_data_id,inventory_name,group1,group2,group3,group4,inventory_list_id,inventory_level_id,
                status,status_data,created_by,updated_by,file_name_upload,tanggal_update_data,
                kategori,kode_gambar,dvr,lokasi_site,kode_lokasi,
                jenis_barang,merk,tipe,model,serial_number,
                psu_adaptor,tahun_pembuatan,tahun_pengadaan,kondisi,deskripsi,
                asuransi,lampiran,tanggal_retired,po,qty,
                keterangan,uuid,comment,created_at,updated_at,deleted_at,sheet_detail
                ) 
                SELECT * FROM new_inventory_data;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_New_Inventory_Data_Update`');
    }
}
