<?php

namespace App\Services;

use App\Models\Transaction;
use App\Events\TransactionCreated;
use App\Jobs\ProcessPayout;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function getAllTransactions()
    {
        return Transaction::with(['user', 'pilot'])->latest()->paginate(20);
    }

    public function getUserTransactions(int $userId)
    {
        return Transaction::where('user_id', $userId)->latest()->paginate(20);
    }

    public function getPilotTransactions(int $pilotId)
    {
        return Transaction::where('pilot_id', $pilotId)->latest()->paginate(20);
    }

    public function createTransaction(array $data)
    {
        return DB::transaction(function () use ($data) {
            $transaction = Transaction::create($data);

            event(new TransactionCreated($transaction));

            if ($transaction->type === Transaction::TYPE_PAYOUT) {
                ProcessPayout::dispatch($transaction);
            }

            return $transaction;
        });
    }

    public function processRefund(Transaction $transaction)
    {
        return DB::transaction(function () use ($transaction) {
            $refundTransaction = $transaction->replicate();
            $refundTransaction->type = Transaction::TYPE_REFUND;
            $refundTransaction->amount = -$transaction->amount;
            $refundTransaction->save();

            $transaction->update(['status' => Transaction::STATUS_REFUNDED]);

            return $refundTransaction;
        });
    }
}