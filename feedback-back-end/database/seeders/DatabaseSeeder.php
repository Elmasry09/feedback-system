<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Order;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fake = \Faker\Factory::create();
        for ($i=0 ; $i < 120 ; $i++) { 
            $ordersIds = Order::doesntHave('answer')->get()->pluck('id')->toArray();
            Answer::create([
                'order_id' => $fake->randomElement($ordersIds),
                'phone' => $fake->phoneNumber,
                'created_at' => $fake->dateTimeThisYear()
            ]);
        }
    }
}
