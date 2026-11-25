<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'record_uuid' => Str::uuid(),
            'admission_number' => 'ADM' . $this->faker->unique()->numberBetween(10000, 99999),
            'phone' => $this->faker->phoneNumber(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date('Y-m-d', '2005-01-01'),
            'passport' => $this->faker->imageUrl(200, 200),
            'address' => $this->faker->address(),
            'status' => true,
            'schema_version' => 1,
            'change_version' => 1,
        ];
    }
}