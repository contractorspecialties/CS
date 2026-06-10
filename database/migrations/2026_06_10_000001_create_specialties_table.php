<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('specialties', function (Blueprint $blueprint) {
            $blueprint->id();
            
            // Core Identity Mappings
            $blueprint->string('name')->unique();
            $blueprint->string('slug')->unique(); // Routing hook (e.g., "electricians")
            $blueprint->string('icon')->nullable(); // Emoji symbol parameter
            $blueprint->string('category'); // UI Grouping (e.g., "Structural Crews", "Specialty Trades")
            
            // Intelligence Data & Telemetry Matrix
            $blueprint->json('aliases'); // Search synonyms array (e.g., ["GC", "Builder"])
            $blueprint->enum('operational_type', ['project', 'service', 'route'])->nullable(); // CPP behavioral routing hooks
            $blueprint->boolean('is_regulated')->default(false); // Licensing requirements filter switch
            $blueprint->text('description')->nullable(); // Contextual programmatic SEO copy block
            
            // Operational Visibility Control Flags
            $blueprint->integer('sort_order')->default(0);
            $blueprint->boolean('is_active')->default(true);
            
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};