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
        Schema::create('authority', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nguoi_uy_quyen')->nullable();
            $table->bigInteger('nguoi_duoc_uy_quyen')->nullable();
            $table->bigInteger('co_phan_uy_quyen')->nullable();
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
        Schema::dropIfExists('authority');
    }
};
