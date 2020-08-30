<?php

namespace Tests\Feature\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Exceptions\TransactionException;
use App\Services\TransactionService;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TransactionsServiceTest extends TestCase
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

    public function setUp(): void
    {
        parent::setUp();

        $this->transactionsService = app()->make(TransactionService::class);

        $this->user1   = factory(User::class)->create();
        $this->user2   = factory(User::class)->create();
        $this->wallet1 = factory(Wallet::class)->make();
        $this->wallet2 = factory(Wallet::class)->make();

        $this->user1->wallets()->save($this->wallet1);
        $this->user2->wallets()->save($this->wallet2);
    }

    public function testSendFail()
    {
        $this->expectException(TransactionException::class);
        $this->transactionsService->send($this->wallet1, $this->wallet2, 10000);
    }

    public function testSend()
    {
        $transaction = $this->transactionsService->send($this->wallet1, $this->wallet2, 100);

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('900.00', $this->wallet1->amount);
        $this->assertEquals('1100.00', $this->wallet2->amount);
    }

    public function testRevert()
    {
        $transaction = $this->transactionsService->send($this->wallet1, $this->wallet2, 100);

        $this->transactionsService->revert($transaction);

        $from = Wallet::query()->find($this->wallet1->id);
        $to   = Wallet::query()->find($this->wallet2->id);

        $this->assertTrue($from->amount == 1000);
        $this->assertTrue($to->amount == 1000);
    }
}
