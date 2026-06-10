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
            $blueprint->string('name')->unique();
            $blueprint->string('slug')->unique(); // For routing (e.g., "electricians")
            $blueprint->string('icon')->nullable(); // For emoji or UI symbols
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('specialties');
    }
};