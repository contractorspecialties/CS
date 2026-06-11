<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Parent Table: High-Level Estimate Details
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            // Connects the estimate directly to the contractor who made it
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Client Contact Info
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            
            // Project Metadata
            $table->string('project_title');
            $table->text('project_description')->nullable();
            
            // Financial Summaries
            $table->integer('subtotal_cents')->default(0);
            $table->integer('tax_cents')->default(0);
            $table->integer('total_cents')->default(0);
            
            // Lifecycle Management
            $table->string('status')->default('draft'); // draft, sent, approved, declined, invoiced
            $table->string('secure_token')->unique(); // For passwordless client viewing pages
            $table->timestamps();
        });

        // 2. Child Table: Individual Line Items
        Schema::create('estimate_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estimate_id')->constrained('estimates')->onDelete('cascade');
            
            $table->string('description');
            $table->string('item_type')->default('labor'); // labor, material, fixture, custom
            
            $table->integer('quantity')->default(1);
            $table->integer('unit_price_cents')->default(0);
            $table->integer('total_price_cents')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimate_items');
        Schema::dropIfExists('estimates');
    }
};