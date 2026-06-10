<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            // Relational trade mapping node
            $blueprint->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            
            // Programmatic SEO Parameter Hooks
            $blueprint->string('slug')->nullable()->unique(); // Unique company routing string
            $blueprint->string('city')->nullable();          // Regional landing hook
            $blueprint->string('state', 2)->nullable();       // Regional state code indexing
            
            // Public Dynamic Profile Metadata
            $blueprint->text('bio')->nullable();
            $blueprint->boolean('is_gc')->default(false)->change(); // Explicit flag validation consistency
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['specialty_id']);
            $blueprint->dropColumn(['specialty_id', 'slug', 'city', 'state', 'bio']);
        });
    }
};