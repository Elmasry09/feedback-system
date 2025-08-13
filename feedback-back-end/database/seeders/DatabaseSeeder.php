<?php

namespace Database\Seeders;

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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test2 User',
            'email' => 'reeem@example.com',
            'password' => bcrypt('12345678'), // Ensure the password is hashed
            'test' => ['key' => 'value' ,'array' => ['item1', 'item2']], 
        ]);
    }
}
