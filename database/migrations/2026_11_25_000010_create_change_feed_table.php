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
        Schema::create('change_feed', function (Blueprint $table) {
            $table->id();
            $table->uuid('change_id')->unique();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('collection_name');
            $table->unsignedBigInteger('document_id');
            $table->enum('operation', ['create', 'update', 'delete']);
            $table->jsonb('data')->nullable();
            $table->timestamp('server_timestamp')->useCurrent();
            $table->unsignedBigInteger('sequence');
            $table->unsignedInteger('source_revision')->nullable();
            $table->string('event_id', 64);
            $table->unsignedInteger('schema_version')->default(1);
            $table->timestamps();
            
            $table->unique(['lecturer_id', 'collection_name', 'sequence'], 'change_feed_lecturer_collection_sequence_unique');
            $table->index(['lecturer_id', 'collection_name', 'server_timestamp']);
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_feed');
    }
};