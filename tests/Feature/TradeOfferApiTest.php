<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\Role;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TradeOfferApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sender;
    private User $receiver;
    private User $other;
    private TradeOffer $tradeOffer;
    private array $tradeOfferCreatePayload;
    private array $tradeOfferUpdatePayload;
    private Collection $books;

    public function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->unverified()->createOne();
        $adminRole = Role::factory()->create(['name' => 'admin']);
        $this->admin->roles()->attach($adminRole);
        $this->sender = User::factory()->unverified()->createOne();
        $this->receiver = User::factory()->unverified()->createOne();
        $this->other = User::factory()->unverified()->createOne();

        $this->tradeOffer = TradeOffer::factory()->createOne([
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $this->tradeOfferCreatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->receiver->id,
            'receiver_id' => $this->sender->id,
        ]);
        $this->tradeOfferUpdatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->receiver->id,
            'receiver_id' => $this->sender->id,
        ]);

        $bookType = BookType::factory()->createOne();
        $this->cover = Cover::factory()->createOne(['user_id' => $this->sender->id]);

        $this->books = Book::factory()->count(3)->create([
            'user_id' => $this->sender->id,
            'book_type_id' => $bookType->id,
        ]);
    }

    public function test_user_cannot_index_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson('/api/v1/trade_offers');
        $response->assertForbidden();
    }

    public function test_admin_can_index_tradeoffer(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/trade_offers');
        $response->assertOk();
    }

    public function test_user_can_create_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $booksIds = $this->books->pluck('id')->all();
        $payload = $this->tradeOfferCreatePayload;
        $payload['trade_offer_items'] = $booksIds;
        $response = $this->postJson('/api/v1/trade_offers', $payload);
        $response->assertCreated();
    }
}
