<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Core Identity Anchors
            $table->string('client_name');
            $table->string('project_title');
            
            // Scheduling Allocation Blocks
            $table->date('scheduled_date');
            $table->string('start_time')->nullable(); // e.g., "08:00 AM"
            $table->text('crew_notes')->nullable();
            
            // Management Flags
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};