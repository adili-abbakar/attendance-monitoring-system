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
        Schema::create('sync_devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('device_id')->unique();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('schema_version')->default(1);
            $table->timestamps();
            $table->timestamp('last_seen_at')->nullable();
            
            $table->index(['lecturer_id', 'device_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_devices');
    }
};