<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
        ];
    }

    public function checkTransaction($user)
    {
        return $this->state(function () use ($user) {
            $usd_amount = $this->faker->numberBetween(10, 100);
            $grn_amount = $usd_amount * 40;

            return [
                'seller_id'          => $user->id,
                'sell_amount'        => $usd_amount,
                'sell_currency'      => 'USD',
                'receiving_amount'   => $grn_amount,
                'receiving_currency' => 'UAH',
                'status'             => 'open',
            ];
        });
    }
}
