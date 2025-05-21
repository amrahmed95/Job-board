<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobApplication>
 */
class JobApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            'submitted',
            'under_review',
            'interview_scheduled',
            'offer_extended',
            'hired',
            'rejected'
        ];

        return [
            'expected_salary' => fake()->numberBetween(30000, 150000),
            'cover_letter' => fake()->paragraph(3,true),
            'resume_path' => 'resumes/' . $this->faker->uuid() . '.pdf',
            'status' => $this->faker->randomElement($statuses),
            'feedback' => $this->faker->boolean(30) ? $this->faker->paragraphs(2, true) : null,
        ];
    }
}
