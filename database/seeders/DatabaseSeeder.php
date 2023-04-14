<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(4)->create();
        Account::factory(4)->create();

        $accounts = Account::all();
        User::all()->each(function ($user) use ($accounts) {
            $user->accounts()->attach($accounts->random(rand(1, 4))->pluck('id')->toArray());
        });
    }
}
