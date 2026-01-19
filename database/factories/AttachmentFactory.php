<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => \App\Models\Ticket::factory(),
            'file_path' => 'tickets/attachments/fake_file.jpg',
            'original_name' => 'screenshot.jpg',
            'file_type' => 'image/jpeg',
            'file_size' => 1024 * 10000, // 10 MB
        ];
    }
}
