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
        Schema::create('lecture_sessions', function (Blueprint $table) {
            $table->id();
            $table->uuid('record_uuid')->unique();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('session_number');
            $table->unsignedInteger('week_number');
            $table->date('lecture_date');
            $table->time('from_time');
            $table->time('to_time');
            $table->unsignedInteger('duration_minutes');
            $table->enum('status', ['scheduled', 'active', 'completed'])->default('scheduled');
            $table->timestamp('started_at')->nullable();
            $table->unsignedInteger('schema_version')->default(1);
            $table->unsignedInteger('change_version')->default(1);
            $table->timestamps();
            
            $table->index(['lecturer_id', 'status']);
            $table->index(['course_id', 'lecture_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecture_sessions');
    }
};