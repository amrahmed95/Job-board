<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create 15 categories
        Category::factory()
            ->count(15)
            ->create();

    }
}
