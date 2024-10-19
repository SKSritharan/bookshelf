<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Member;

class BorrowRecordFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BorrowRecord::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'borrow_date' => $this->faker->date(),
            'return_date' => $this->faker->date(),
            'due_date' => $this->faker->date(),
            'status' => $this->faker->word(),
            'member_id' => Member::factory(),
            'book_id' => Book::factory(),
        ];
    }
}
