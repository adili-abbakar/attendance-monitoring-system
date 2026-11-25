<?php

namespace Database\Factories;

use App\Models\LectureSession;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LectureSession>
 */
class LectureSessionFactory extends Factory
{
    protected $model = LectureSession::class;

    public function definition(): array
    {
        return [
            'record_uuid' => Str::uuid(),
            'course_id' => Course::factory(),
            'lecturer_id' => User::factory()->lecturer(),
            'session_number' => $this->faker->numberBetween(1, 20),
            'week_number' => $this->faker->numberBetween(1, 15),
            'lecture_date' => $this->faker->date(),
            'from_time' => '09:00:00',
            'to_time' => '10:00:00',
            'duration_minutes' => 60,
            'status' => $this->faker->randomElement(['scheduled', 'active', 'completed']),
            'started_at' => now(),
            'schema_version' => 1,
            'change_version' => 1,
        ];
    }
}