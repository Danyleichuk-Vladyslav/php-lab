<?php

namespace Database\Factories;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    public function definition(): array
    {
        $fileName = fake()->unique()->slug().'.jpg';

        return [
            'post_id' => Post::factory(),
            'file_name' => $fileName,
            'mime_type' => 'image/jpeg',
            'size_bytes' => fake()->numberBetween(10_000, 2_000_000),
            'path' => 'attachments/'.$fileName,
        ];
    }
}
