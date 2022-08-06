<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(3)->create()
            ->each(function ($user) {
                // add user wallets
                $user->wallets()->saveMany(Wallet::factory(3)->make());
                // add transactions
                $user->transactions()->saveMany(Transaction::factory(2)->checkTransaction($user)->make());
            });
    }
}
