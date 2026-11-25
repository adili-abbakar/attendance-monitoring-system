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
        Schema::create('sync_pending_pages', function (Blueprint $table) {
            $table->id();
            $table->uuid('page_id')->unique();
            $table->foreignId('device_id')->constrained('sync_devices')->cascadeOnDelete();
            $table->string('collection_name');
            $table->uuid('expected_cursor')->nullable();
            $table->uuid('next_cursor')->nullable();
            $table->unsignedInteger('schema_version')->default(1);
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['device_id', 'collection_name']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_pending_pages');
    }
};