<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enforce safe deployment parameters regardless of custom prefix settings (e.g. 'sc_')
        Schema::table('users', function (Blueprint $table) {
            // Core System Access Overlays
            $table->boolean('is_admin')->default(false)->after('id');
            $table->boolean('is_gc')->default(false)->after('is_admin');
            $table->boolean('is_restricted')->default(false)->after('is_gc');
            
            // Specialized Trade Profile Fields
            $table->string('business_name')->nullable()->after('name');
            $table->string('slogan')->nullable()->after('business_name');
            $table->string('company_website')->nullable()->after('slogan');
            $table->string('logo_path')->nullable()->after('company_website');
            $table->string('theme_color')->default('#0F2D5A')->after('logo_path');
            
            // Frictionless Magic Link Token Tracking Components
            $table->string('magic_link_token', 64)->nullable()->unique()->after('remember_token');
            $table->timestamp('magic_link_expires_at')->nullable()->after('magic_link_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_admin', 'is_gc', 'is_restricted', 
                'business_name', 'slogan', 'company_website', 
                'logo_path', 'theme_color', 
                'magic_link_token', 'magic_link_expires_at'
            ]);
        });
    }
};