<?php

namespace Database\Seeders;

use App\Models\Attachment;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::factory()->count(10)->create();

        $authors = User::factory()
            ->count(5)
            ->has(Profile::factory())
            ->create();

        Category::factory()
            ->count(5)
            ->create()
            ->each(function (Category $category) use ($authors, $tags): void {
                Post::factory()
                    ->count(4)
                    ->for($category)
                    ->for($authors->random())
                    ->has(Comment::factory()->count(2))
                    ->has(Attachment::factory()->count(1))
                    ->create()
                    ->each(function (Post $post) use ($tags): void {
                        $post->tags()->attach($tags->random(2)->pluck('id'));
                    });
            });
    }
}
