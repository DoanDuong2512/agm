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
            $table->bigInteger('tong_co_phan_duoc_uy_quyen_tru_noi_bo')->after('tong_co_phan_duoc_uy_quyen')->default(null)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer', function (Blueprint $table) {
            $table->dropColumn('tong_co_phan_duoc_uy_quyen_tru_noi_bo');
        });
    }
};
