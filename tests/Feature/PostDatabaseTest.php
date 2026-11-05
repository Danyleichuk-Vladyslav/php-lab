<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_posts_with_relations(): void
    {
        $category = Category::factory()->create(['name' => 'Подорожі']);
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Подорож Карпатами',
            'status' => 'published',
        ]);
        $tag = Tag::factory()->create(['name' => 'Гори']);
        $post->tags()->attach($tag->id);

        $response = $this->getJson('/posts');

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Подорож Карпатами'])
            ->assertJsonFragment(['name' => 'Подорожі']);
    }

    public function test_can_create_post_with_category_and_tags_in_database(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $payload = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Новий допис із тегами',
            'body' => 'Текст допису для перевірки збереження в SQLite.',
            'status' => 'published',
            'published_at' => '2026-11-05 10:00:00',
            'tags' => $tags->pluck('id')->all(),
        ];

        $response = $this->withHeaders([
            'X-Author-Role' => 'editor',
        ])->postJson('/posts', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('posts', [
            'title' => 'Новий допис із тегами',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $postId = $response->json('data.id');
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('post_tag', [
                'post_id' => $postId,
                'tag_id' => $tag->id,
            ]);
        }
    }
}
