<?php

use App\Enums\TradeOfferStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trade_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('receiver_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('status', TradeOfferStatusEnum::cases())->default(TradeOfferStatusEnum::Pending->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_offers');
    }
};
