<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    /** @use HasFactory<\Database\Factories\JobApplicationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'expected_salary',
        'cover_letter',
        'resume_path',
        'status',
        'feedback'
    ];

    // Status constants
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const STATUS_OFFER_EXTENDED = 'offer_extended';
    const STATUS_HIRED = 'hired';
    const STATUS_REJECTED = 'rejected';

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all possible status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under Review',
            self::STATUS_INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::STATUS_OFFER_EXTENDED => 'Offer Extended',
            self::STATUS_HIRED => 'Hired',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }
}
