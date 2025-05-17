<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20); // 'private', 'group'
            $table->string('title')->nullable(); // Chỉ cần cho group chats
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
            $table->unsignedBigInteger('last_message_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('type');
            $table->index(['created_by_id', 'created_by_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};