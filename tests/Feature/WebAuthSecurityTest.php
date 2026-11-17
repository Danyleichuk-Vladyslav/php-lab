<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebAuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_create_post(): void
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_store_post(): void
    {
        $category = Category::factory()->create();

        $this->post(route('posts.store'), [
            'category_id' => $category->id,
            'title' => 'Без логіну',
            'body' => 'Текст',
        ])->assertRedirect(route('login'));
    }

    public function test_user_cannot_edit_foreign_post(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->get(route('posts.edit', $post))
            ->assertForbidden();
    }

    public function test_user_cannot_update_foreign_post(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($intruder)
            ->put(route('posts.update', $post), [
                'category_id' => $post->category_id,
                'title' => 'Злам',
                'body' => 'Чужий текст',
            ])
            ->assertForbidden();
    }

    public function test_owner_can_edit_own_post(): void
    {
        $owner = User::factory()->create();
        $post = Post::factory()->for($owner)->create();

        $this->actingAs($owner)
            ->get(route('posts.edit', $post))
            ->assertOk()
            ->assertSee('Автор (не змінюється)');
    }

    public function test_login_and_logout_work(): void
    {
        $user = User::factory()->create([
            'email' => 'author@example.com',
            'password' => 'password',
        ]);

        $this->post(route('login'), [
            'email' => 'author@example.com',
            'password' => 'password',
        ])->assertRedirect(route('posts.index'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_register_creates_user_and_logs_in(): void
    {
        $this->post(route('register'), [
            'name' => 'Новий Автор',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect(route('posts.index'));

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }
}
