<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PostAccessTest extends TestCase
{
    /**
     * Тест блокування: запит без заголовка X-Author-Role повинен бути відхилений Middleware.
     */
    public function test_request_without_client_header_is_blocked_by_middleware(): void
    {
        $response = $this->postJson('/posts', [
            'title' => 'Новий допис блогу',
            'body' => 'Текст нового допису для перевірки middleware.',
            'category_id' => 1,
            'published_at' => '2026-11-03 12:00:00',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => 'access_denied',
            ]);
    }

    /**
     * Тест успіху: запит із валідним заголовком проходить до контролера
     * та збагачується службовим заголовком пост-обробки.
     */
    public function test_request_with_valid_header_passes_middleware(): void
    {
        $response = $this->withHeaders([
            'X-Author-Role' => 'editor',
        ])->postJson('/posts', [
            'title' => 'Новий допис блогу',
            'body' => 'Текст нового допису для перевірки middleware.',
            'category_id' => 1,
            'published_at' => '2026-11-03 12:00:00',
        ]);

        $response->assertStatus(201);
        $response->assertHeader('X-Security-Policy', 'Enforced');
    }
}
