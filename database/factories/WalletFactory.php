<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    const CURRENCIES = ['USD', 'UAH', 'EUR'];
    static int $counter = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // make sure creating only one currency type of wallet for each user
        $code = self::CURRENCIES[$this::$counter % count(self::CURRENCIES)];
        $this::$counter += 1;

        return [
            'user_id' => mt_rand(1, User::all()->count()),
            'amount'  => $this->faker->numberBetween(100, 5000),
            'currency' => $code
        ];
    }
}
