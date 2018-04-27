<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerAksesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('akses_data', function (Blueprint $table) {
            DB::unprepared('
            CREATE TRIGGER tr_Akses_Data_INSERT AFTER INSERT ON `akses_data` FOR EACH ROW
            BEGIN
                INSERT INTO akses_data_history (`id_akses_data`, `status_akses`, `updated_by`, `comment`,`status_trigger`,`trigger_at`) 
                VALUES (NEW.id, NEW.status_akses, NEW.updated_by,NEW.comment,1,now());
            END
            ');


            DB::unprepared('
            CREATE TRIGGER tr_Akses_Data_UPDATE AFTER UPDATE ON `akses_data` FOR EACH ROW
            BEGIN
                INSERT INTO akses_data_history (`id_akses_data`, `status_akses`, `updated_by`, `comment`,`status_trigger`,`trigger_at`) 
                VALUES (NEW.id, NEW.status_akses, NEW.updated_by,NEW.comment,2,now());
            END
            ');

       
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('akses_data', function (Blueprint $table) {
            DB::unprepared('DROP TRIGGER `tr_Akses_Data_INSERT`');
            DB::unprepared('DROP TRIGGER `tr_Akses_Data_UPDATE`');
        });
    }
}
