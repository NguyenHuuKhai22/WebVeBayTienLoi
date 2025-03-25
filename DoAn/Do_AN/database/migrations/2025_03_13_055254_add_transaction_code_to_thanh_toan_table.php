<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->text('meta_data')->nullable();
        });
    }


    public function down()
    {
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->dropColumn('ma_giao_dich');
        });
    }
};
