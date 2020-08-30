<?php

namespace App\Repositories;

use App\Models\Wallet;
use App\User;

class WalletRepository
{
    /**
     * @param User $user
     * @param Wallet $wallet
     * @return Wallet
     */
    public function addWallet(User $user, Wallet $wallet): Wallet
    {
        $user->wallets()->save($wallet);

        return $wallet;
    }

}
