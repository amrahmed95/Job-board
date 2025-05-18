<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use \App\Models\Job;
use \App\Models\Employer;
use \App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create storage link if it doesn't exist
        if (!file_exists(public_path('storage'))) {
            \Artisan::call('storage:link');
        }

         $categories = Category::factory()->count(15)->create();

        // User::factory(10)->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Create 300 users - some will be employers, some job seekers
        $users = User::factory()->count(300)->create();

        // Make random 30 users employers
        $users->shuffle()->take(30)->each(function ($user) use ($categories) {
            $employer = Employer::factory()->create([
                'user_id' => $user->id,
                'category_id' => $categories->random()->id,
                'logo' => $this->getPlaceholderLogo(),
            ]);

            // Each employer creates 2-10 jobs
            $job = Job::factory()->count(rand(2, 10))->create([
                'employer_id' => $employer->id,
                'category_id' => $categories->random()->id
            ]);
        });



        // Remaining users are job seekers (no employer record)

    }

    private function getPlaceholderLogo()
    {
        $logos = [
            'company-logo-1.png',
            'company-logo-2.png',
            'company-logo-3.png',
        ];

        return 'employers/logos/' . fake()->randomElement($logos);
    }
}
