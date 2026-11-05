<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    /** @use HasFactory<CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'author_name',
        'body',
        'status',
    ];

    /**
     * Зв'язок M:1 — коментар належить допису.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Зв'язок M:1 — коментар залишив користувач.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
