<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Account;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(100)->create();

        Account::factory(367)
            ->hasAttached(
                Product::factory(random_int(5,40))->create(),
                    function() {
                        $now = fake()->dateTimeBetween(now()->subYears(8), now());
                        return [
                            'created_at' => $now,
                            'expiration_date' => fake()->dateTimeBetween($now, Carbon::create($now)->addDays(random_int(10,30))),
                            'ran_out_at' => fake()->dateTimeBetween($now, Carbon::create($now)->addDays(random_int(1,20))),
                        ];
                    }
            )
            ->create();

        $accounts = Account::all();
        User::all()->each(function ($user) use ($accounts) {
            $user->accounts()->attach($accounts->random(rand(1, 4))->pluck('id')->toArray());
        });
    }
}
