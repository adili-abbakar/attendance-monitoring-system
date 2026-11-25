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
        Schema::create('student_admission_reservations', function (Blueprint $table) {
            $table->id();
            $table->string('reservation_id')->unique();
            $table->string('admission_number_hash', 64);
            $table->foreignId('lecturer_id')->constrained('users')->cascadeOnDelete();
            $table->uuid('student_record_uuid');
            $table->timestamp('reserved_at')->useCurrent();
            $table->timestamp('expires_at');
            
            $table->index('admission_number_hash');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_admission_reservations');
    }
};