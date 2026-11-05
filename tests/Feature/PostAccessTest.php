<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_without_client_header_is_blocked_by_middleware(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->postJson('/posts', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Новий допис блогу',
            'body' => 'Текст нового допису для перевірки middleware.',
            'published_at' => '2026-11-03 12:00:00',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'access_denied',
            ]);
    }

    public function test_request_with_valid_header_passes_middleware(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->withHeaders([
            'X-Author-Role' => 'editor',
        ])->postJson('/posts', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Новий допис блогу',
            'body' => 'Текст нового допису для перевірки middleware.',
            'published_at' => '2026-11-03 12:00:00',
        ]);

        $response->assertStatus(201);
        $response->assertHeader('X-Security-Policy', 'Enforced');
    }
}
