<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\BookType;
use App\Models\Cover;
use App\Models\Role;
use App\Models\TradeOffer;
use App\Models\User;
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
    private array $tradeOfferAsSenderCreatePayload;
    private array $tradeOfferAsReceiverCreatePayload;
    private array $tradeOfferUpdatePayload;

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
        $this->anotherSender = User::factory()->unverified()->createOne();
        $anotherReceiver = User::factory()->unverified()->createOne();

        $this->tradeOffer = TradeOffer::factory()->createOne([
            'sender_id' => $this->sender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        // As sender creation
        $this->tradeOfferAsSenderCreatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->anotherSender->id,
            'receiver_id' => $this->receiver->id,
        ]);

        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne();

        $senderBooks = Book::factory()->count(3)->create([
            'user_id' => $this->anotherSender->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $senderBooksIds = $senderBooks->pluck('id')->all();

        $receiverBooks = Book::factory()->count(3)->create([
            'user_id' => $this->receiver->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $receiverBooksIds = $receiverBooks->pluck('id')->all();

        $this->tradeOfferAsSenderCreatePayload['sender_items'] = $senderBooksIds;
        $this->tradeOfferAsSenderCreatePayload['receiver_items'] = $receiverBooksIds;

        // As receiver creation
        $this->tradeOfferAsSenderCreatePayload = TradeOffer::factory()->raw([
            'sender_id' => $this->sender->id,
            'receiver_id' => $anotherReceiver->id,
        ]);

        $bookType = BookType::factory()->createOne();
        $cover = Cover::factory()->createOne();

        $senderBooks = Book::factory()->count(3)->create([
            'user_id' => $this->sender->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $senderBooksIds = $senderBooks->pluck('id')->all();

        $receiverBooks = Book::factory()->count(3)->create([
            'user_id' => $anotherReceiver->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $receiverBooksIds = $receiverBooks->pluck('id')->all();

        $this->tradeOfferAsSenderCreatePayload['sender_items'] = $senderBooksIds;
        $this->tradeOfferAsSenderCreatePayload['receiver_items'] = $receiverBooksIds;

        // updating
        $senderBooks = Book::factory()->count(3)->create([
            'user_id' => $this->sender->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $senderBooksIds = $senderBooks->pluck('id')->all();

        $receiverBooks = Book::factory()->count(3)->create([
            'user_id' => $this->receiver->id,
            'book_type_id' => $bookType->id,
            'cover_id' => $cover->id,
        ]);

        $receiverBooksIds = $receiverBooks->pluck('id')->all();

        $this->tradeOfferUpdatePayload = [
            'sender_items' => $senderBooksIds,
            'receiver_items' => $receiverBooksIds,
        ];
    }

    public function test_user_cannot_index_trade_offer(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson('/api/v1/trade_offers');
        $response->assertForbidden();
    }

    public function test_admin_can_index_trade_offer(): void
    {
        Sanctum::actingAs($this->admin);

        $response = $this->getJson('/api/v1/trade_offers');
        $response->assertOk();
    }

    public function test_user_can_get_related_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertOk();
    }

    public function test_user_can_get_related_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertOk();
    }

    public function test_user_cannot_get_others_trade_offer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}");
        $response->assertForbidden();
    }

    public function test_user_can_create_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->anotherSender);

        $response = $this->postJson('/api/v1/trade_offers', $this->tradeOfferAsSenderCreatePayload);
        $response->assertCreated();
    }

    public function test_user_can_create_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->postJson('/api/v1/trade_offers', $this->tradeOfferAsReceiverCreatePayload);
        $response->assertCreated();
    }

    public function test_user_can_update_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertOk();
    }

    public function test_user_can_update_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertOk();
    }

    public function test_user_cannot_update_others_trade_offer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->putJson("/api/v1/trade_offers/{$this->tradeOffer->id}", $this->tradeOfferUpdatePayload);
        $response->assertForbidden();
    }

    public function test_user_can_accept_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/accept");
        $response->assertOk();
    }

    public function test_user_cannot_accept_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/accept");
        $response->assertForbidden();
    }

    public function test_user_cannot_accept_others_trade_offer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/accept");
        $response->assertForbidden();
    }

    public function test_user_can_reject_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/reject");
        $response->assertOk();
    }

    public function test_user_can_reject_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/reject");
        $response->assertOk();
    }

    public function test_user_cannot_reject_others_trade_offer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->patchJson("/api/v1/trade_offers/{$this->tradeOffer->id}/reject");
        $response->assertForbidden();
    }

    public function test_user_can_get_items_of_trade_offer_as_sender(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}/items");
        $response->assertOk();
    }

    public function test_user_can_get_items_of_trade_offer_as_receiver(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}/items");
        $response->assertOk();
    }

    public function test_user_cannot_get_items_of_others_trade_offer(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/trade_offers/{$this->tradeOffer->id}/items");
        $response->assertOk();
    }

    public function test_user_can_get_by_sender_trade_offers(): void
    {
        Sanctum::actingAs($this->sender);

        $response = $this->getJson("/api/v1/trade_offers/by_sender/{$this->sender->id}");
        $response->assertOk();
    }

    public function test_user_cannot_get_by_sender_trade_offers(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/trade_offers/by_receiver/{$this->receiver->id}");
        $response->assertOk();
    }

    public function test_user_can_get_by_receiver_trade_offers(): void
    {
        Sanctum::actingAs($this->receiver);

        $response = $this->getJson("/api/v1/trade_offers/by_receiver/{$this->receiver->id}");
        $response->assertOk();
    }

    public function test_user_cannot_get_by_receiver_trade_offers(): void
    {
        Sanctum::actingAs($this->other);

        $response = $this->getJson("/api/v1/trade_offers/by_receiver/{$this->receiver->id}");
        $response->assertOk();
    }
}
