<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('users', 'license_number')) {
                $blueprint->string('license_number')->nullable();
            }
            if (!Schema::hasColumn('users', 'established_year')) {
                $blueprint->integer('established_year')->nullable();
            }
            if (!Schema::hasColumn('users', 'is_insured')) {
                $blueprint->boolean('is_insured')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['license_number', 'established_year', 'is_insured']);
        });
    }
};