<?php
// database/factories/ReviewFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        $book = \App\Models\Book::inRandomOrder()->first();
        $user = \App\Models\User::inRandomOrder()->first();

        return [
            'book_id' => $book ? $book->id : null,
            'user_id' => $user ? $user->id : null,
            // 'user_id' => 1,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph,
        ];
    }
}
