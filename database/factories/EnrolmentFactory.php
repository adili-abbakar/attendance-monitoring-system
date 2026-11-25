<?php

namespace Database\Factories;

use App\Models\Enrolment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Enrolment>
 */
class EnrolmentFactory extends Factory
{
    protected $model = Enrolment::class;

    public function definition(): array
    {
        return [
            'record_uuid' => Str::uuid(),
            'course_id' => Course::factory(),
            'student_id' => User::factory()->student(),
            'lecturer_id' => User::factory()->lecturer(),
            'enrolled_at' => now(),
            'schema_version' => 1,
            'change_version' => 1,
        ];
    }
}