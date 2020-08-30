<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Repositories\WalletRepository;
use App\User;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * @param Request $request
     * @param WalletRepository $walletRepository
     * @return Wallet
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(Request $request, WalletRepository $walletRepository): Wallet
    {
        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

        return $walletRepository->addWallet(
            $request->user(),
            new Wallet(['name' => $request->post('name')])
        );
    }

    /**
     * @param Wallet $wallet
     * @param Request $request
     * @return bool
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Wallet $wallet, Request $request): bool
    {
        $this->validate($request, [
            'name' => 'required|string|max:255'
        ]);

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
