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
        // Stand up the primary customer CRM directory table
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Contractor owner context
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();

            // Ensure a contractor cannot duplicate the exact same customer profile block
            $table->unique(['user_id', 'email', 'phone']);
        });

        // Inject the relationship bridge columns into your operational tracks
        Schema::table('estimates', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('user_id')->constrained('clients')->onDelete('set null');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('user_id')->constrained('clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });

        Schema::dropIfExists('clients');
    }
};