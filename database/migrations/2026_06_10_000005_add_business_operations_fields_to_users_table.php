<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('users', 'minimum_service_fee')) {
                $blueprint->integer('minimum_service_fee')->nullable();
            }
            if (!Schema::hasColumn('users', 'hourly_rate')) {
                $blueprint->integer('hourly_rate')->nullable();
            }
            if (!Schema::hasColumn('users', 'crew_size')) {
                $blueprint->integer('crew_size')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['minimum_service_fee', 'hourly_rate', 'crew_size']);
        });
    }
};