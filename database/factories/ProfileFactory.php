<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    protected $model = Profile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pen_name' => fake()->unique()->userName(),
            'biography' => fake()->sentence(),
            'posts_count' => 0,
            'rating' => fake()->randomFloat(2, 0, 5),
        ];
    }
}
