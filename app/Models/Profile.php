<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /** @use HasFactory<ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pen_name',
        'biography',
        'posts_count',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'posts_count' => 'integer',
            'rating' => 'float',
        ];
    }

    /**
     * Зв'язок 1:1 — профіль належить одному користувачу.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
