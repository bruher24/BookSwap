<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('second_user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('blocked_by', ['first', 'second'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['first_user_id', 'second_user_id', 'deleted_at']);
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
