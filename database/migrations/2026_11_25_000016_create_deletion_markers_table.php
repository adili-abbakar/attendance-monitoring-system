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
        Schema::create('deletion_markers', function (Blueprint $table) {
            $table->id();
            $table->string('marker_id')->unique();
            $table->uuid('record_uuid');
            $table->enum('record_type', ['course', 'lecture_session', 'attendance_record', 'enrolment']);
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('attendance_record_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->timestamp('deleted_at')->useCurrent();
            $table->unsignedInteger('schema_version')->default(1);
            $table->unsignedInteger('change_version')->default(1);
            $table->timestamps();
            
            $table->index(['lecturer_id', 'record_type']);
            $table->index(['record_type', 'record_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deletion_markers');
    }
};