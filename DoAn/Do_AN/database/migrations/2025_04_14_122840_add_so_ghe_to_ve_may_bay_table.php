<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ve_may_bay', function (Blueprint $table) {
            $table->string('so_ghe')->nullable()->after('loai_ghe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ve_may_bay', function (Blueprint $table) {
            $table->dropColumn('so_ghe');
        });
    }
};
