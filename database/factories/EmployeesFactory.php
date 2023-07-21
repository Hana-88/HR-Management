<?php

namespace Database\Factories;

use App\Models\Employees;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employees>
 */
class EmployeesFactory extends Factory
{
    protected $model = Employees::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'age' => $this->faker->numberBetween(20, 60),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'hired_date' => $this->faker->date,
            'job_title' => $this->faker->jobTitle,
            'manager_id' => null,
        ];
    }
}
