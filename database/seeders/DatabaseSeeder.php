<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(5)->create();
        \App\Models\Author::factory(5)->create();
        \App\Models\Category::factory(5)->create();
        \App\Models\Book::factory(10)->create();
        \App\Models\Review::factory(10)->create();
        \App\Models\Borrow::factory(3)->create();
    }
}
