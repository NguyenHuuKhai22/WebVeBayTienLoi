<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('Ve_May_Bay', function (Blueprint $table) {
            $table->decimal('khoi_luong_dang_ky', 5, 2)->default(0)->after('gia_ve');
            $table->decimal('phi_hanh_ly', 10, 2)->default(0)->after('khoi_luong_dang_ky');
        });
    }

    public function down()
    {
        Schema::table('Ve_May_Bay', function (Blueprint $table) {
            $table->dropColumn(['khoi_luong_dang_ky', 'phi_hanh_ly']);
        });
    }
}; 