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
        Schema::create('sync_checkpoints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('sync_devices')->cascadeOnDelete();
            $table->string('collection_name');
            $table->uuid('cursor')->nullable();
            $table->uuid('last_page_id')->nullable();
            $table->unsignedInteger('schema_version')->default(1);
            $table->timestamps();
            
            $table->unique(['device_id', 'collection_name'], 'sync_checkpoint_device_collection_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_checkpoints');
    }
};