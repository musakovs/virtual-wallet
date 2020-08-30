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
            throw new TransactionException('cannot send to self');
        }

        if ($from->amount < $amount) {
            throw new TransactionException('cannot send, not enough money');
        }

        try {
            $transaction = DB::transaction(function () use ($from, $to, $amount) {

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
        } catch (\Throwable $exception) {
            throw new TransactionException('error');
        }

        $from->amount = bcsub($from->amount, $amount, 2);
        $to->amount   = bcadd($to->amount, $amount, 2);

        return $transaction;
    }

    /**
     * @param Transaction $transaction
     * @return bool|null
     * @throws TransactionException
     */
    public function revert(Transaction $transaction): ?bool
    {
        try {
            return DB::transaction(function () use ($transaction) {
                Wallet::query()->where('id', $transaction->to_wallet)
                    ->decrement('amount', $transaction->amount);
                Wallet::query()->where('id', $transaction->from_wallet)
                    ->increment('amount', $transaction->amount);

                return $transaction->delete();
            });
        } catch (\Throwable $exception) {
            throw new TransactionException('error');
        }
    }
}
