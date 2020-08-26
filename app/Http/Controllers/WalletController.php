<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @return Wallet
     */
    public function create(Request $request): Wallet
    {
        $wallet = new Wallet(['name' => $request->post('name')]);
        /**@var $user User*/
        $user = Auth::user();
        $user->wallets()->save($wallet);

        return $wallet;
    }

    /**
     * @param Wallet $wallet
     * @param Request $request
     * @return bool
     */
    public function update(Wallet $wallet, Request $request): bool
    {
        return $wallet->update(['name' => $request->post('name')]);
    }

    /**
     * @param Wallet $wallet
     * @return bool|null
     * @throws \Exception
     */
    public function delete(Wallet $wallet): ?bool
    {
        return $wallet->delete();
    }

    /**
     * @param Wallet $wallet
     * @return Wallet
     */
    public function view(Wallet $wallet): Wallet
    {
        return $wallet;
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function walletUserList(User $user)
    {
        return $user->wallets();
    }
}