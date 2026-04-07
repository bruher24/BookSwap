<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\Role;
use App\Models\TradeOffer;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Override;
use Tests\TestCase;

final class TradeOfferApiTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $sender;
    private User $anotherSender;
    private User $receiver;
    private User $other;
    private TradeOffer $tradeOffer;
    private array $tradeOfferCreatePayload;
    private array $tradeOfferUpdatePayload;
    private Collection $books;

    #[Override]
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

        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne();

        /** @var Collection<int, Book> $books*/
        $books = Book::factory()->count(3)->create([
            'user_id' => $this->sender->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);
        $this->books = $books;

        $booksIds = $this->books->pluck('id')->all();

        $this->anotherSender = User::factory()->unverified()->createOne();

        $this->tradeOfferCreatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->anotherSender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $this->tradeOfferCreatePayload['trade_offer_items'] = $booksIds;

        $this->tradeOfferUpdatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->receiver->id,
            'receiver_id' => $this->anotherSender->id,
        ]);

        $this->tradeOfferUpdatePayload['trade_offer_items'] = $booksIds;
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

    public function test_user_can_get_sent_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertOk();
    }

    public function test_user_can_get_received_tradeoffer(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertOk();
    }

    public function test_user_cannot_get_others_tradeoffer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertForbidden();
    }

    public function test_user_can_create_tradeoffer_as_sender(): void
    {
        Sanctum::actingAs($this->anotherSender);

        $response = $this->postJson('/api/v1/trade_offers', $this->tradeOfferCreatePayload);
        $response->assertCreated();
    }

    public function test_user_cannot_create_tradeoffer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->postJson('/api/v1/trade_offers', $this->tradeOfferCreatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_update_sent_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertOk();
    }

    public function test_user_can_update_received_tradeoffer(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertOk();
    }

    public function test_user_cannot_update_others_tradeoffer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_delete_owned_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->deleteJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertAccepted();
    }

    public function test_user_can_delete_sent_tradeoffer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->deleteJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertAccepted();
    }

    public function test_user_can_delete_received_tradeoffer(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->deleteJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertAccepted();
    }

    public function test_user_cannot_delete_others_tradeoffer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->deleteJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertForbidden();
    }
}
