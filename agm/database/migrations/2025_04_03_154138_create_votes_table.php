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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('ten_phieu')->nullable();
            $table->integer('so_luong_nguoi_duoc_trung_cu')->nullable();
            $table->tinyInteger('cho_phep_co_dong_noi_bo_bieu_quyet')->default(1)->nullable();
            $table->tinyInteger('cho_phep_gui_lai_phieu_bau')->default(1)->nullable();
            $table->string('trang_thai')->nullable();
            $table->dateTime('thoi_gian_mo')->nullable();
            $table->dateTime('thoi_gian_dong')->nullable();
            $table->string('loai')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
