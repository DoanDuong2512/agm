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
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('participant_id');
            $table->string('participant_type'); // 'App\\Models\\User' hoặc 'App\\Models\\Customer'
            $table->timestamp('last_read_at')->nullable();
            $table->boolean('is_admin')->default(false); // Quyền admin trong group
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['conversation_id', 'participant_id', 'participant_type'], 'unique_participant');
            $table->index(['participant_id', 'participant_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_participants');
    }
};
