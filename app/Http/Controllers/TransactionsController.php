<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransactionsController extends Controller
{
    /**
     * @param Wallet $wallet
     * @return string
     */
    public function index(Wallet $wallet)
    {
        return view('transactions', ['wallet' => $wallet]);
    }

    /**
     * @param Wallet $wallet
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list(Wallet $wallet)
    {
        return Transaction::query()
            ->with('from.user')
            ->with('to.user')
            ->orWhere('to_wallet', $wallet->id)
            ->orWhere('from_wallet', $wallet->id)
            ->orderBy('id', 'desc')
            //->paginate(10);
            ->get();
    }

    /**
     * @param Wallet $wallet
     * @param Request $request
     * @param TransactionService $transactionService
     * @return \Illuminate\Database\Eloquent\Model|Transaction
     * @throws \App\Services\Exceptions\TransactionException
     */
    public function create(Wallet $wallet, CreateTransactionRequest $request, TransactionService $transactionService)
    {
        /**@var $to Wallet */
        $to = Wallet::query()->whereHas('user', function ($query) use ($request) {
            $query->where('email', $request->get('email'));
        })
            ->where('name', $request->get('wallet'))
            ->firstOrFail();

        $amount = $request->get('amount');

        $transaction = $transactionService->send($wallet, $to, $amount);

        return Transaction::query()
            ->with('from.user')
            ->with('to.user')
            ->find($transaction->id);
    }

    /**
     * @param TransactionService $transactionService
     * @param Wallet $wallet
     * @param Transaction $transaction
     * @return bool|null
     * @throws ValidationException
     */
    public function delete(TransactionService $transactionService, Wallet $wallet, Transaction $transaction)
    {
        if ($transaction->to()->first()->amount < $transaction->amount) {
            throw ValidationException::withMessages(['cannot revert this operation']);
        };

        return $transactionService->revert($transaction);
    }
}
