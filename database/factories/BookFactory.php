<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;




/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    public function definition(): array
    {
        
        Log::info('Текущая локаль: ' . app()->getLocale());
        return [
            'title' => $this->faker->sentence(5, true, 'en_US'),
            'isbn' => $this->faker->isbn13,
            'publication_year' => $this->faker->year,
            'description' => $this->faker->paragraph(3, true, 'en_US'),
            'author_id' => \App\Models\Author::factory(),
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}

