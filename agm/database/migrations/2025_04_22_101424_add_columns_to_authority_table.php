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
        Schema::table('authority', function (Blueprint $table) {
            $table->string('ten_nguoi_duoc_uy_quyen')->nullable();
            $table->tinyInteger('is_shareholder')->unsigned()->default(0);
            $table->string('vn_id', 60)->nullable();
            $table->date('vn_id_issue_date')->nullable();
            $table->string('address', 255)->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('authority', function (Blueprint $table) {
            $table->dropColumn('ten_nguoi_duoc_uy_quyen');
            $table->dropColumn('is_shareholder');
            $table->dropColumn('vn_id');
            $table->dropColumn('vn_id_issue_date');
            $table->dropColumn('address');
        });
    }
};
