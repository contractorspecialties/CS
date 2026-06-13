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
        Schema::create('sc_appointment_exceptions', function (Blueprint $table) {
            $table->id();
            // Ties back to the parent schedule pattern rule
            $table->foreignId('recurrence_template_id')->constrained('sc_recurrence_templates')->onDelete('cascade');
            
            // Action log flag: skip (homeowner called off), reschedule (shifted time)
            $table->string('exception_type'); 
            
            // The original calendar date that is being altered
            $table->date('original_date');
            
            // New target window map if the job was shifted instead of dropped completely
            $table->dateTime('rescheduled_start_at')->nullable();
            $table->dateTime('rescheduled_end_at')->nullable();
            
            $table->text('reason_notes')->nullable(); // e.g. "Customer out of town" or "Rain day call-off"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_appointment_exceptions');
    }
};