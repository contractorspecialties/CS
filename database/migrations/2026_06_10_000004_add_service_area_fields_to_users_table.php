<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('users', 'service_radius')) {
                $blueprint->integer('service_radius')->nullable();
            }
            if (!Schema::hasColumn('users', 'service_areas')) {
                $blueprint->text('service_areas')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['service_radius', 'service_areas']);
        });
    }
};