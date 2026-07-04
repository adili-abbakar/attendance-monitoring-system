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
        Schema::create('students', function (Blueprint $table) {

            $table->foreignId('user_id')
                ->primary()
                ->constrained('users')
                ->cascadeOnDelete();
            $table->string('admission_number')->unique();
            $table->string('phone')->nullable();
            $table->enum('gender', [
                'male',
                'female'
            ])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('passport')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
