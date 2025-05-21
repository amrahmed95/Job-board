<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(\App\Models\Job::class)->constrained()->onDelete('cascade');
            $table->unsignedInteger('expected_salary');
            $table->text('cover_letter')->nullable(); // Optional cover letter
            $table->string('resume_path'); // Path to the uploaded resume file
            $table->enum('status', [
                'submitted',
                'under_review',
                'interview_scheduled',
                'offer_extended',
                'hired',
                'rejected'
            ])->default('submitted');
            $table->text('feedback')->nullable();

            $table->timestamps();

            // Ensure a user can only apply once per job
            $table->unique(['job_id', 'user_id']);

            // Indexes for better query performance
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
