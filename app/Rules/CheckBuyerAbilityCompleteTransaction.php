<?php

namespace App\Rules;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class CheckBuyerAbilityCompleteTransaction implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $transaction = Transaction::find(request()->transaction_id);
        $buyer = User::find(request()->buyer_id);
        $amount_with_fee = $transaction->receiving_amount + round($transaction->receiving_amount * 0.02);

        if (!$buyer->wallets()->where([
            ['currency', $transaction->receiving_currency],
            ['amount', '>=', $amount_with_fee]
        ])->exists()) return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You cannot apply for this transaction. You have to have enough amount on corresponded wallet.';
    }
}
