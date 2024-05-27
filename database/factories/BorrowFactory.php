<?php
// database/factories/BorrowFactory.php

namespace Database\Factories;

use App\Models\Borrow;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowFactory extends Factory
{
    /**
     * Определение модели, которую фабрика будет использовать.
     *
     * @var string
     */
    protected $model = Borrow::class;

    /**
     * Определение начальных значений атрибутов фабрики.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'book_id' => function () {
                return \App\Models\Book::factory()->create()->id;
            },
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'borrowed_at' => now(),
            'returned_at' => null,
        ];
    }
}
