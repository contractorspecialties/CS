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
        Schema::create('sc_recurrence_templates', function (Blueprint $table) {
            $table->id();
            // Ties the repeating contract to the manager/business owner
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Ties back to the baseline estimate pricing and customer profile info
            $table->foreignId('estimate_id')->constrained('estimates')->onDelete('cascade');
            
            // The default worker lane assigned to this repeating sequence
            $table->foreignId('crew_id')->constrained('sc_crews')->onDelete('cascade');
            
            // Plain language rules: weekly, bi_weekly, monthly
            $table->string('frequency'); 
            
            // Day identifier: 1 = Monday, 2 = Tuesday, etc.
            $table->unsignedTinyInteger('day_of_week'); 
            
            // Financial firewall pay scale per single visit
            $table->integer('payout_cents')->default(0); 
            
            // Core duration parameters
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null means indefinite/ongoing service
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_recurrence_templates');
    }
};