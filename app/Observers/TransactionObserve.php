<?php

namespace App\Observers;

use App\Models\Transaction;

class TransactionObserve
{
    /**
     * Handle the Transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {
        $this->createFeeRecord($transaction);
    }

    private function createFeeRecord($transaction)
    {
        $fee_amount = round($transaction->receiving_amount * 0.02);

        $transaction->fee()->create([
            'amount' => $fee_amount,
            'currency' => $transaction->receiving_currency
        ]);
    }
}
