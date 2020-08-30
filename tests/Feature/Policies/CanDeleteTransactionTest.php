<?php

namespace Tests\Feature\Policies;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CanDeleteTransactionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var TransactionService
     */
    private $transactionsService;

    /**
     * @var User
     */
    private $user1;

    /**
     * @var User
     */
    private $user2;

    /**
     * @var Wallet
     */
    private $wallet1;

    /**
     * @var Wallet
     */
    private $wallet2;

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var User
     */
    private $user3;

    public function setUp(): void
    {
        parent::setUp();

        $this->transactionsService = app()->make(TransactionService::class);

        $this->user1   = factory(User::class)->create();
        $this->user2   = factory(User::class)->create();
        $this->user3   = factory(User::class)->create();
        $this->wallet1 = factory(Wallet::class)->make();
        $this->wallet2 = factory(Wallet::class)->make();

        $this->user1->wallets()->save($this->wallet1);
        $this->user2->wallets()->save($this->wallet2);

        $this->transaction = $this->transactionsService->send($this->wallet1, $this->wallet2, 100);
    }

    public function testCanNotDeleteTransaction()
    {
        $response = $this->actingAs($this->user2)
            ->delete('/wallet/' . $this->wallet1->id . '/transaction/delete/' . $this->transaction->id);

        //todo how to catch this error - in browser returns 422 code, but here another
        $this->assertNotSame($response->getStatusCode(), 200);

        $response2 = $this->actingAs($this->user3)
            ->delete('/wallet/' . $this->wallet1->id . '/transaction/delete/' . $this->transaction->id);

        //todo how to catch this error
        $this->assertNotSame($response2->getStatusCode(), 200);
    }

    public function testCanDeleteTransaction()
    {
        $response = $this->actingAs($this->user1)
            ->delete('/wallet/' . $this->wallet1->id . '/transaction/delete/' . $this->transaction->id);

        $response->assertSuccessful();
    }
}
