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
        Schema::create('sc_appointments', function (Blueprint $table) {
            $table->id();
            // Ties back to the parent estimate umbrella containing high-level project specs
            $table->foreignId('estimate_id')->constrained('estimates')->onDelete('cascade');
            
            // Ties to the specific crew resource dispatched to execute this order
            $table->foreignId('crew_id')->constrained('sc_crews')->onDelete('cascade');
            
            // Financial Firewall Isolation Parameters
            $table->string('payout_type')->default('flat'); // flat, hourly, piece_rate
            $table->integer('payout_cents')->default(0); // Explicit crew pay separate from retail bill
            
            // Operational Parameters & Logistics
            $table->string('status')->default('scheduled'); // scheduled, traveling, active, completed, skipped
            $table->text('crew_notes')->nullable(); // Scope modifications specific to this appointment assignment
            
            // Dispatch Timeline Mappings
            $table->dateTime('scheduled_start_at');
            $table->dateTime('scheduled_end_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_appointments');
    }
};