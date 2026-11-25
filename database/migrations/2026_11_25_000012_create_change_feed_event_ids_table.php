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
        Schema::create('change_feed_event_ids', function (Blueprint $table) {
            $table->id();
            $table->string('event_id', 64)->unique();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('collection_name');
            $table->unsignedBigInteger('document_id');
            $table->enum('operation', ['create', 'update', 'delete']);
            $table->unsignedInteger('source_revision')->nullable();
            $table->uuid('change_id');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['lecturer_id', 'collection_name', 'document_id', 'operation', 'source_revision']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_feed_event_ids');
    }
};