<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\Exceptions\TransactionException;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * @param Wallet $from
     * @param Wallet $to
     * @param string $amount
     * @return Transaction
     * @throws TransactionException
     */
    public function send(Wallet $from, Wallet $to, string $amount): Transaction
    {
        if ($from->id === $to->id) {
            throw new TransactionException('');
        }

        return DB::transaction(function() use ($from, $to, $amount) {

            Wallet::query()->where('id', $from->id)
                ->decrement('amount', $amount);
            Wallet::query()->where('id', $to->id)
                ->increment('amount', $amount);

            return Transaction::query()->create([
                'from_wallet' => $from->id,
                'to_wallet'   => $to->id,
                'amount'      => $amount
            ]);
        });
    }

    /**
     * @param Transaction $transaction
     * @return bool|null
     */
    public function revert(Transaction $transaction): ?bool
    {
        return DB::transaction(function() use ($transaction) {
            Wallet::query()->where('id', $transaction->to_wallet)
                ->decrement('amount', $transaction->amount);
            Wallet::query()->where('id', $transaction->from_wallet)
                ->increment('amount', $transaction->amount);

            return $transaction->delete();
        });
    }
}
