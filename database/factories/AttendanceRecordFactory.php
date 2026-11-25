<?php

namespace Database\Factories;

use App\Models\AttendanceRecord;
use App\Models\LectureSession;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<AttendanceRecord>
 */
class AttendanceRecordFactory extends Factory
{
    protected $model = AttendanceRecord::class;

    public function definition(): array
    {
        return [
            'record_uuid' => Str::uuid(),
            'lecture_session_id' => LectureSession::factory(),
            'student_id' => Student::factory(),
            'status' => $this->faker->randomElement(['present', 'absent', 'late']),
            'scanned_at' => $this->faker->dateTimeThisYear(),
            'source_revision' => 1,
            'schema_version' => 1,
            'change_version' => 1,
        ];
    }
}