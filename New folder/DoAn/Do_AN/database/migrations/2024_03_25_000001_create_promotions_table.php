<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id('id_khuyen_mai');
            $table->string('ten_khuyen_mai');
            $table->text('mo_ta')->nullable();
            $table->decimal('phan_tram_giam', 5, 2);
            $table->dateTime('thoi_gian_bat_dau')->nullable();
            $table->dateTime('thoi_gian_ket_thuc')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chuyen_bay_khuyen_mai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_chuyen_bay');
            $table->unsignedBigInteger('id_khuyen_mai');
            $table->timestamps();

            if (Schema::hasTable('Chuyen_Bay')) {
                $table->foreign('id_chuyen_bay')
                      ->references('id_chuyen_bay')
                      ->on('Chuyen_Bay')
                      ->onDelete('cascade');
            }

            $table->foreign('id_khuyen_mai')
                  ->references('id_khuyen_mai')
                  ->on('promotions')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chuyen_bay_khuyen_mai');
        Schema::dropIfExists('promotions');
    }
}; 