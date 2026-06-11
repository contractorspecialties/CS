<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Polymorphic Core: Maps cleanly to Estimate, Invoice, or Profile instances
            $table->string('attachable_type');
            $table->unsignedBigInteger('attachable_id');
            
            // File Registry Properties
            $table->string('file_path');
            $table->string('file_type')->default('image'); // image, markup
            
            // Live Canvas Telemetry: Stores canvas JSON vector paths for re-editing brushstrokes later
            $table->json('canvas_metadata')->nullable(); 
            
            $table->timestamps();

            // Compound indexing for instantaneous structural lookup execution speeds
            $table->index(['attachable_type', 'attachable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};