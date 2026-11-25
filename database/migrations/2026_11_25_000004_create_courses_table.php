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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->uuid('record_uuid')->unique();
            $table->string('code', 64);
            $table->string('title', 200);
            $table->foreignId('level_id')->constrained('levels')->cascadeOnDelete();
            $table->foreignId('academic_session_id')->constrained('academic_sessions')->cascadeOnDelete();
            $table->unsignedInteger('semester');
            $table->boolean('is_active')->default(true);
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('schema_version')->default(1);
            $table->unsignedInteger('change_version')->default(1);
            $table->timestamps();
            
            $table->index(['lecturer_id', 'is_active']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};