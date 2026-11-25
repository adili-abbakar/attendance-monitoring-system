<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Level;
use App\Models\AcademicSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'record_uuid' => Str::uuid(),
            'code' => 'CS' . $this->faker->numberBetween(100, 499),
            'title' => $this->faker->sentence(3),
            'level_id' => Level::factory(),
            'academic_session_id' => AcademicSession::factory(),
            'semester' => $this->faker->numberBetween(1, 2),
            'is_active' => true,
            'lecturer_id' => \App\Models\User::factory()->lecturer(),
            'schema_version' => 1,
            'change_version' => 1,
        ];
    }
}