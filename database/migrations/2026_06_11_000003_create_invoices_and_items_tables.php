<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('estimate_id')->nullable()->constrained('estimates')->onDelete('set null');
            
            // Client Target Data
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->string('client_phone')->nullable();
            
            // Scope Parameters
            $table->string('project_title');
            $table->text('project_description')->nullable();
            
            // Financial Ledger Matrix
            $table->integer('subtotal_cents')->default(0);
            $table->integer('tax_cents')->default(0);
            $table->integer('total_cents')->default(0);
            $table->integer('amount_paid_cents')->default(0);
            
            // Management Flags
            $table->string('status')->default('draft'); // draft, sent, paid, void, archived
            $table->timestamp('due_at')->nullable();
            $table->string('secure_token')->unique();
            $table->timestamps();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->string('description');
            $table->string('item_type')->default('labor');
            $table->integer('quantity')->default(1);
            $table->integer('unit_price_cents')->default(0);
            $table->integer('total_price_cents')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
    }
};