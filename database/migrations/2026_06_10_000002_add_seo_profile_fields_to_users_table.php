<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Relational trade mapping node
        if (!Schema::hasColumn('users', 'specialty_id')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->foreignId('specialty_id')->nullable()->constrained('specialties')->nullOnDelete();
            });
        }

        // 2. Core Communications Vector
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->string('phone', 30)->nullable();
            });
        }

        // 3. Programmatic SEO Parameter Hooks
        if (!Schema::hasColumn('users', 'slug')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->string('slug')->nullable()->unique();
            });
        }

        if (!Schema::hasColumn('users', 'city')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->string('city')->nullable();
            });
        }

        if (!Schema::hasColumn('users', 'state')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->string('state', 2)->nullable();
            });
        }

        // 4. Public Dynamic Profile Metadata
        if (!Schema::hasColumn('users', 'bio')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->text('bio')->nullable();
            });
        }

        // 5. Explicit flag validation consistency
        if (Schema::hasColumn('users', 'is_gc')) {
            Schema::table('users', function (Blueprint $blueprint) {
                $blueprint->boolean('is_gc')->default(false)->change();
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            if (Schema::hasColumn('users', 'specialty_id')) {
                $blueprint->dropForeign(['specialty_id']);
            }
            $blueprint->dropColumn(array_filter([
                Schema::hasColumn('users', 'specialty_id') ? 'specialty_id' : null,
                Schema::hasColumn('users', 'phone') ? 'phone' : null,
                Schema::hasColumn('users', 'slug') ? 'slug' : null,
                Schema::hasColumn('users', 'city') ? 'city' : null,
                Schema::hasColumn('users', 'state') ? 'state' : null,
                Schema::hasColumn('users', 'bio') ? 'bio' : null,
            ]));
        });
    }
};