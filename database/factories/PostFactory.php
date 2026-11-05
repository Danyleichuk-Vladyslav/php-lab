<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'title' => fake()->sentence(4),
            'body' => fake()->paragraphs(2, true),
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'views_count' => fake()->numberBetween(0, 500),
        ];
    }
}
