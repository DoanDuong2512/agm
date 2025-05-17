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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('ma_co_dong')->nullable();
            $table->bigInteger('co_phan_so_huu')->nullable();
            $table->bigInteger('tong_co_phan_duoc_uy_quyen')->nullable();
            $table->bigInteger('tong_so_co_dong_uy_quyen')->nullable();
            $table->tinyInteger('co_dong_noi_bo')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            //
        });
    }
};
