<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('description');

            $table->string('category'); // Access, Hardware, Network, Bug, Other
            $table->integer('severity'); // 1 to 5
            $table->string('status')->default('Open'); // Open, In Progress, Resolved, Closed
            
            // Relationships
            // created_by is the Employee
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // assigned_to is the Agent (can be null if unassigned)
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
