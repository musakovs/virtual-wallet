<?php

namespace Tests\Feature;

use App\Models\Wallet;
use App\Repositories\WalletRepository;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WalletRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var WalletRepository
     */
    private $walletRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->walletRepository = $this->app->make(WalletRepository::class);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddWallet()
    {
        /**@var $user User */
        $user = factory(User::class)->create();
        $wallet = factory(Wallet::class)->make();

        $added = $this->walletRepository->addWallet($user, $wallet);

        $this->assertEquals($wallet, $added);
        $this->assertTrue($user->wallets()->get()->contains($wallet));
        $this->assertEquals($user->id, $wallet->user_id);
    }
}
