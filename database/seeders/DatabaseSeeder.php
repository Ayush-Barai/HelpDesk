<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Attachment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create an Agent for testing
        $agent = User::factory()->create([
            'name'  => 'Support Agent',
            'email' => 'agent@test.com',
            'password'=> 'password',
            'role'  => 'agent',
        ]);

        // 2. Create an Employee for testing
        $employee = User::factory()->create([
            'name'  => 'Ayush Employee',
            'email' => 'employee@test.com',
            'password'=> 'password',
            'role'  => 'employee',
        ]);

        // 3. Create 10 tickets for this specific employee
        Ticket::factory(10)->create([
            'created_by' => $employee->id,
        ]);

        // 4. Create 20 random tickets assigned to the agent
        Ticket::factory(20)->create([
            'assigned_to' => $agent->id,
        ]);

        // 5. Add attachments to a few tickets
        Ticket::all()->take(5)->each(function ($ticket) {
            Attachment::factory(2)->create([
                'ticket_id' => $ticket->id,
            ]);
        });
    }
}
