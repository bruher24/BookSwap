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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_user_id')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreignId('second_user_id')->constrained('users')->onDelete('set null')->onUpdate('cascade');
            $table->enum('blocked_by', ['first', 'second'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['first_user_id', 'second_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
