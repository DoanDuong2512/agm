_table.php
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
            $table->dropUnique(['vn_id']);
            $table->dropUnique(['email']); 
            $table->unique('ma_co_dong');
            $table->date('vn_id_issue_date')->nullable();
            $table->string('phone', 60)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unique('vn_id');
            $table->unique('email'); 
            $table->dropUnique(['ma_co_dong']); 
            $table->dropColumn('vn_id_issue_date');
            $table->string('phone', 20)->nullable()->change(); 
        });
    }
};