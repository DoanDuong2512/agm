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
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->unsignedBigInteger('sender_id');
            $table->string('sender_type'); // 'App\\Models\\User' hoặc 'App\\Models\\Customer'
            $table->text('body')->nullable();
            $table->string('type')->default('text'); // 'text', 'image', 'file', 'system'
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('conversation_id');
            $table->index(['sender_id', 'sender_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};
