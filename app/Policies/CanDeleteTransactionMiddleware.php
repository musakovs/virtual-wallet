<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\Wallet;
use App\User;
use Illuminate\Validation\ValidationException;

class CanDeleteTransactionMiddleware
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, \Closure $next)
    {
        /**@var $user User
         * @var $wallet Wallet
         * @var $transaction Transaction
         */
        $user        = $request->user();
        $wallet      = $request->wallet;
        $transaction = $request->transaction;

        if (
            $wallet->belongsToUser($user) &&
            (
                $wallet->id === $transaction->from_wallet
                || $wallet->id === $transaction->to_wallet
            )
        ) {
            return $next($request);
        }

        throw ValidationException::withMessages(['cannot delete not your transaction']);
    }
}
