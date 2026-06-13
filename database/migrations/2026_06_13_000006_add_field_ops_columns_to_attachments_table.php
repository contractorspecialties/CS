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
        Schema::table('attachments', function (Blueprint $table) {
            // Adds the visibility toggle for the homeowner portal view deck
            $table->boolean('is_visible_to_client')->default(false);
            
            // Tracks which subcontractor or field worker uploaded the asset
            $table->foreignId('uploaded_by_worker_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Houses text logs, contractor remarks, or client signature event notes
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by_worker_id']);
            $table->dropColumn(['is_visible_to_client', 'uploaded_by_worker_id', 'note']);
        });
    }
};