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
        Schema::create('agm_info', function (Blueprint $table) {
            $table->id();
            $table->integer('so_co_dong_tham_du')->nullable();
            $table->integer('so_luong_co_dong_uy_quyen')->nullable();
            $table->integer('tong_so_co_phan_tham_gia')->nullable();
            $table->integer('tong_so_co_phan_co_quyen_bieu_quyet')->nullable();
            $table->double('ti_le')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agm_info');
    }
};
