<?php

namespace Database\Factories;

use App\Enums\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $total_copies = $this->faker->numberBetween(1, 1000);
        $available_copies = $this->faker->numberBetween(1, $total_copies);

        return [
            'title' => $this->faker->sentence(4),
            'isbn' => $this->faker->isbn10(),
            'isbn13' => $this->faker->isbn13(),
            'description' => $this->faker->text(),
            'cover' => 'book-cover-placeholder.png',
            'language' => $this->faker->randomElement(Language::cases()),
            'available_copies' => $available_copies,
            'total_copies' => $total_copies,
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'tags' => $this->faker->words(3),
        ];
    }
}
