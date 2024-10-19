<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Member;
use App\Models\User;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->text(),
            'membership_date' => $this->faker->date(),
            'user_id' => User::factory(),
        ];
    }
}
