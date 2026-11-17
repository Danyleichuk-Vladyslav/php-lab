<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Comment $comment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        return $comment->user_id !== null && $user->id === $comment->user_id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $comment->user_id !== null && $user->id === $comment->user_id;
    }
}
