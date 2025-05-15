<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Category;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['New York', 'London', 'Berlin', 'Paris', 'Tokyo', 'Sydney', 'Dubai'];
        $countries = ['USA', 'UK', 'Germany', 'France', 'Japan', 'Australia', 'UAE'];
        $currencies = ['USD', 'EUR', 'GBP', 'JPY', 'AUD'];

        return [
            'title' => fake()->jobTitle,
            'description' => $this->faker->paragraph(3, true),
            'salary'=> fake()->numberBetween(10000, 1000000),
            'salary_currency' => $this->faker->randomElement($currencies),
            'salary_period' => $this->faker->randomElement(Job::$salary_period),
            'employment_type' => $this->faker->randomElement(Job::$employment_type),
            'work_location_type' => $this->faker->randomElement(Job::$work_location_type),
            'city' => $this->faker->randomElement($cities),
            'country' => $this->faker->randomElement($countries),
            'category_id' => Category::inRandomOrder()->first()->id,
            'experience' => $this->faker->randomElement(Job::$experience),
            'user_id' => User::factory(),
        ];
    }
}
