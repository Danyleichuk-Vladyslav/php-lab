<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_page_displays_entities(): void
    {
        $post = Post::factory()->create([
            'title' => 'Унікальний Тестовий Допис',
        ]);

        $response = $this->get('/posts');

        $response->assertStatus(200)
            ->assertSee('Унікальний Тестовий Допис')
            ->assertDontSee('Додати новий запис');

        $this->assertNotNull($post->id);
    }

    public function test_authenticated_user_sees_create_button(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/posts')
            ->assertOk()
            ->assertSee('Додати новий запис');
    }

    public function test_create_form_validates_input(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body', 'category_id']);
        $response->assertSessionDoesntHaveErrors(['user_id']);
    }

    public function test_store_creates_post_with_category_and_tags(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $response = $this->actingAs($user)->post('/posts', [
            'category_id' => $category->id,
            'title' => 'Новий допис із тегами',
            'body' => 'Текст допису для перевірки збереження через Blade-форму.',
            'status' => 'published',
            'tags' => $tags->pluck('id')->all(),
        ]);

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'title' => 'Новий допис із тегами',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        $post = Post::where('title', 'Новий допис із тегами')->firstOrFail();
        foreach ($tags as $tag) {
            $this->assertDatabaseHas('post_tag', [
                'post_id' => $post->id,
                'tag_id' => $tag->id,
            ]);
        }
    }

    public function test_home_page_renders_navigation(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Інтернет-блог');
    }
}
