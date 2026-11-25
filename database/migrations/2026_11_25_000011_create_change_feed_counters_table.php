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
        Schema::create('change_feed_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->string('collection_name');
            $table->unsignedBigInteger('next_sequence')->default(0);
            $table->timestamps();
            
            $table->unique(['lecturer_id', 'collection_name'], 'change_feed_counter_lecturer_collection_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_feed_counters');
    }
};