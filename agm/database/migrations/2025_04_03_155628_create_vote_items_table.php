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
        Schema::create('vote_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vote_id')->nullable();
            $table->string('noi_dung')->nullable();
            $table->string('vi_tri_ung_cu')->nullable();
            $table->double('ti_le_chap_thuan')->nullable();
            $table->bigInteger('tong_co_phan_bieu_quyet')->nullable();
            $table->bigInteger('tong_so_nguoi_bieu_quyet')->nullable();
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
        Schema::dropIfExists('vote_items');
    }
};
