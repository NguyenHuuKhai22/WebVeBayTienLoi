<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('hang_bay', function (Blueprint $table) {
            $table->softDeletes(); // Thêm cột deleted_at
        });
    }

    public function down()
    {
        Schema::table('hang_bay', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
