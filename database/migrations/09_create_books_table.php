<?php

use App\Enums\BookConditionEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 100);
            $table->boolean('is_available')->default(true);
            $table->string('publishing_house', 100)->nullable();
            $table->year('publication_year')->nullable();
            $table->string('isbn', 20)->nullable()->unique();
            $table->unsignedInteger('page_count');
            $table->enum('condition', BookConditionEnum::cases())->default(BookConditionEnum::Perfect->value);
            $table->foreignId('book_type_id')->nullable()->constrained('book_types')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('cover_id')->nullable()->constrained('covers')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('trade_offer_id')->nullable()->constrained('trade_offers')->nullOnDelete()->cascadeOnUpdate();
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
