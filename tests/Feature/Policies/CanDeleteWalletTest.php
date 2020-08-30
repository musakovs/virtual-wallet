<?php

namespace Tests\Feature\Policies;

use App\Models\Wallet;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CanDeleteWalletTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var User;
     */
    private $user;

    /**
     * @var Wallet;
     */
    private $wallet;

    /**
     * @var User
     */
    private $user2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user   = factory(User::class)->create();
        $this->user2  = factory(User::class)->create();
        $this->wallet = factory(Wallet::class)->make();
        $this->user->wallets()->save($this->wallet);
    }

    public function testCanNotDelete()
    {
        $response = $this->actingAs($this->user2)->delete('/wallet/delete/'. $this->wallet->id);

        $response->assertForbidden();
    }

    public function testCanDelete()
    {
        $response = $this->actingAs($this->user)->delete('/wallet/delete/'. $this->wallet->id);

        $response->assertSuccessful();
    }
}
