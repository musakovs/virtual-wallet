<?php

namespace App\Policies;

use App\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class WalletPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function pay(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function view(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function update(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function delete(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function restore(User $user, Wallet $wallet): bool
    {
        return $wallet->user_id === $user->id;
    }

    /**
     * @param User $user
     * @param Wallet $wallet
     * @return bool
     */
    public function forceDelete(User $user, Wallet $wallet): bool
    {
        return false;
    }
}
