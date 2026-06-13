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
        Schema::create('sc_checkpoints', function (Blueprint $table) {
            $table->id();
            // Binds the checkpoint requirement item to its explicit appointment execution sheet
            $table->foreignId('appointment_id')->constrained('sc_appointments')->onDelete('cascade');
            
            // Itemized trade directive parameters (e.g., "Verify double-bevel cuts on inner corner tracks")
            $table->string('title');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_checkpoints');
    }
};