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
        Schema::create('vote_item_customer_import', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->nullable();
            $table->bigInteger('vote_item_id')->nullable();
            $table->bigInteger('vote_id')->nullable();
            $table->string('loai')->nullable();
            $table->tinyInteger('bau_don_phieu')->nullable();
            $table->integer('so_phieu_bau')->nullable();
            $table->string('ket_qua_bieu_quyet')->nullable();
            $table->bigInteger('co_phan_bieu_quyet')->nullable();
            $table->tinyInteger('khong_hop_le')->nullable();


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
        Schema::dropIfExists('vote_item_customer_import');
    }
};
