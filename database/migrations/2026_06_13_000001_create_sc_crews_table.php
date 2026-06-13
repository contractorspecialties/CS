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
        Schema::create('sc_crews', function (Blueprint $table) {
            $table->id();
            // Ties the crew directly to the managing business owner
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Name of the crew asset (e.g., "Owner Shell", "Sparky Crew", "Cleaning Team Alpha")
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_crews');
    }
};