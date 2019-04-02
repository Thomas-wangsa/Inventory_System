<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreatedByPicListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pic_list', function (Blueprint $table) {
            $table->unsignedInteger('created_by')->default(1)->after('vendor_detail_name');

            $table->foreign('created_by', 'pic_list_createdby_user_fkey')
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
        Schema::table('pic_list', function (Blueprint $table) {
            //
        });
    }
}
