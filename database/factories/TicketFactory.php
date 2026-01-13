<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject'     => fake()->sentence(4),
            'description' => fake()->paragraph(3),
            'category'    => fake()->randomElement(['Access', 'Hardware', 'Network', 'Bug', 'Other']),
            'severity'    => fake()->numberBetween(1, 5),
            'status'      => fake()->randomElement(['Open', 'In Progress', 'Resolved', 'Closed']),
            // These will be overridden in the Seeder
            'created_by'  => \App\Models\User::factory(),
            'assigned_to' => null, 
        ];
    }
}
