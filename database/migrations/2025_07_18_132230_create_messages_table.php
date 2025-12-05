<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained('chats')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('from_id')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('to_id')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->boolean('seen')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
