<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostApiSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_post_via_api(): void
    {
        $category = Category::factory()->create();

        $response = $this->postJson('/api/v1/posts', [
            'category_id' => $category->id,
            'title' => 'Секретний допис',
            'body' => 'Текст без авторизації',
        ]);

        $response->assertUnauthorized();
    }

    public function test_guest_cannot_update_post_via_api(): void
    {
        $post = Post::factory()->create();

        $response = $this->putJson('/api/v1/posts/'.$post->id, [
            'category_id' => $post->category_id,
            'title' => 'Злам',
            'body' => 'Спроба оновити без токена',
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_cannot_update_foreign_post(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        Sanctum::actingAs($intruder);

        $response = $this->putJson('/api/v1/posts/'.$post->id, [
            'category_id' => $post->category_id,
            'title' => 'Чужий допис',
            'body' => 'Спроба змінити чужий запис',
        ]);

        $response->assertForbidden();
    }

    public function test_authenticated_user_cannot_delete_foreign_post(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        Sanctum::actingAs($intruder);

        $this->deleteJson('/api/v1/posts/'.$post->id)->assertForbidden();
    }

    public function test_owner_can_update_own_post(): void
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        Sanctum::actingAs($owner);

        $response = $this->putJson('/api/v1/posts/'.$post->id, [
            'category_id' => $post->category_id,
            'title' => 'Оновлений заголовок',
            'body' => 'Оновлений текст допису власником',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Оновлений заголовок');
    }

    public function test_authenticated_user_can_create_post_with_own_user_id(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/posts', [
            'category_id' => $category->id,
            'title' => 'Мій API-допис',
            'body' => 'Автор підставляється з токена Sanctum',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user_id', $user->id);

        $this->assertDatabaseHas('posts', [
            'title' => 'Мій API-допис',
            'user_id' => $user->id,
        ]);
    }

    public function test_public_can_list_posts(): void
    {
        Post::factory()->count(2)->create();

        $this->getJson('/api/v1/posts')->assertOk();
    }
}
