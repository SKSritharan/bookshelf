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
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        if (app()->environment('local')) {
            \App\Models\Author::factory(15)->create();
            \App\Models\Category::factory(15)->create();
            \App\Models\Book::factory(25)->create();
            \App\Models\Member::factory(15)->create();
            \App\Models\BorrowRecord::factory(15)->create();
        }
    }
}
