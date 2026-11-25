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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('record_uuid')->unique();
            $table->foreignId('lecture_session_id')->constrained('lecture_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['present'])->default('present');
            $table->timestamp('scanned_at');
            $table->unsignedInteger('schema_version')->default(1);
            $table->unsignedInteger('change_version')->default(1);
            $table->timestamps();
            
            $table->index(['lecturer_id', 'scanned_at']);
            $table->unique(['lecture_session_id', 'student_id'], 'attendance_session_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};