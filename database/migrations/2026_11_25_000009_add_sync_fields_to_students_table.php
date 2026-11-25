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
        Schema::table('students', function (Blueprint $table) {
            $table->uuid('record_uuid')->unique()->nullable()->after('user_id');
            $table->unsignedInteger('schema_version')->default(1)->after('record_uuid');
            $table->unsignedInteger('change_version')->default(1)->after('schema_version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['record_uuid']);
            $table->dropColumn(['record_uuid', 'schema_version', 'change_version']);
        });
    }
};