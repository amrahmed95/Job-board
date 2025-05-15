<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Job as JobModel;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('salary');
            $table->string('salary_currency', 3)->nullable()->default('USD');
            $table->enum('salary_period', JobModel::$salary_period)->nullable()->default('monthly');
            $table->enum('employment_type', JobModel::$employment_type)->nullable()->default('Full-time');
            $table->enum('work_location_type', JobModel::$work_location_type)->nullable()->default('On-site');
            $table->string('city');
            $table->string('country');
            $table->foreignIdFor(Category::class)->constrained()->onDelete('cascade');
            $table->enum('experience', JobModel::$experience)->default('entry');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes for better performance on searches
            $table->index(['title']);
            $table->index(['employment_type']);
            $table->index(['work_location_type']);
            $table->index(['experience']);
            $table->index(['city', 'country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
