<?php

use App\Enums\BookTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name', 100)->unique();
            $table->string('publishing_house', 100);
            $table->year('publication_year');
            $table->string('isbn', 20)->unique()->nullable();
            $table->integer('page_count', false, true);
            $table->enum('book_type', BookTypeEnum::toArray());
            $table->foreignId('cover_id')->constrained('covers')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
