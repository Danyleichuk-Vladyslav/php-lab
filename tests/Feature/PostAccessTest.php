<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_is_available(): void
    {
        Category::factory()->create();
        User::factory()->create();

        $this->get('/posts/create')
            ->assertStatus(200)
            ->assertSee('Створення нового допису')
            ->assertSee('Категорія');
    }

    public function test_update_and_delete_via_forms(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Старий заголовок',
        ]);

        $this->put('/posts/'.$post->id, [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Оновлений заголовок',
            'body' => 'Оновлений текст допису для форми редагування.',
            'status' => 'published',
        ])->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Оновлений заголовок',
        ]);

        $this->delete('/posts/'.$post->id)
            ->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
