<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employer>
 */
class EmployerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $company = $this->faker->company();

        return [
            'name' => $this->faker->company(),
            'slug' => \Illuminate\Support\Str::slug($company),
            'website' => $this->faker->url(),
            'logo' => null,
            'category_id' => \App\Models\Category::factory(),
            'user_id' => \App\Models\User::factory()->employer(),
        ];

    }
}
