<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    /** @use HasFactory<\Database\Factories\JobFactory> */
    use HasFactory;

    public static array $salary_period = ['hourly', 'daily', 'weekly', 'monthly', 'yearly', 'Commission-based',];
    public static array $employment_type = [
                'Full-time',
                'Part-time',
                'Contract',
                'Freelance',
                'Internship',
                'Temporary',
                'Project-based',
                'Volunteer',
    ];

    public static array $work_location_type = ['Remote', 'On-site','Hybrid'];
    public static array $experience = ['entry', 'mid', 'mid-senior', 'senior', 'manager', 'director', 'executive'];

    protected $fillable = [
        'title',
        'description',
        'salary',
        'salary_currency',
        'salary_period',
        'employment_type',
        'work_location_type',
        'city',
        'country',
        'category_id',
        'experience',
        'user_id',
        'employer_id',
    ];

    protected $casts = [
        'salary' => 'integer',
    ];

    // Employment type constants
    const EMPLOYMENT_TYPE_FULL_TIME = 'full-time';
    const EMPLOYMENT_TYPE_PART_TIME = 'part-time';
    const EMPLOYMENT_TYPE_CONTRACT = 'contract';
    const EMPLOYMENT_TYPE_FREELANCE = 'freelance';
    const EMPLOYMENT_TYPE_INTERNSHIP = 'internship';
    const EMPLOYMENT_TYPE_TEMPORARY = 'temporary';

    // Work location type constants
    const WORK_LOCATION_REMOTE = 'remote';
    const WORK_LOCATION_ON_SITE = 'on-site';
    const WORK_LOCATION_HYBRID = 'hybrid';

    // Experience level constants
    const EXPERIENCE_ENTRY = 'entry';
    const EXPERIENCE_MID = 'mid';
    const EXPERIENCE_MID_SENIOR = 'mid-senior';
    const EXPERIENCE_SENIOR = 'senior';
    const EXPERIENCE_MANAGER = 'manager';
    const EXPERIENCE_DIRECTOR = 'director';
    const EXPERIENCE_EXECUTIVE = 'executive';

    // Salary period constants
    const SALARY_PERIOD_HOUR = 'hour';
    const SALARY_PERIOD_DAY = 'day';
    const SALARY_PERIOD_WEEK = 'week';
    const SALARY_PERIOD_MONTH = 'month';
    const SALARY_PERIOD_YEAR = 'year';
    const SALARY_PERIOD_PROJECT = 'project';


    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function user()
    {
        return $this->through('employer')->has('user');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function hasUserApplied(User $user)
    {
        return $this->jobApplications()
            ->where('user_id', $user->id)
            ->exists();
    }

    // Accessors
    public function getFormattedSalaryAttribute()
    {
        if (!$this->salary) {
            return 'Negotiable';
        }

        $periods = [
            self::SALARY_PERIOD_HOUR => '/hr',
            self::SALARY_PERIOD_DAY => '/day',
            self::SALARY_PERIOD_WEEK => '/wk',
            self::SALARY_PERIOD_MONTH => '/mo',
            self::SALARY_PERIOD_YEAR => '/yr',
            self::SALARY_PERIOD_PROJECT => ' total',
        ];

        $symbol = $this->salary_currency === 'USD' ? '$' : $this->salary_currency;
        $period = $periods[$this->salary_period] ?? '';

        return $symbol . number_format($this->salary) . $period;
    }

    public function getEmploymentTypeNameAttribute()
    {
        return match($this->employment_type) {
            self::EMPLOYMENT_TYPE_FULL_TIME => 'Full Time',
            self::EMPLOYMENT_TYPE_PART_TIME => 'Part Time',
            self::EMPLOYMENT_TYPE_CONTRACT => 'Contract',
            self::EMPLOYMENT_TYPE_FREELANCE => 'Freelance',
            self::EMPLOYMENT_TYPE_INTERNSHIP => 'Internship',
            self::EMPLOYMENT_TYPE_TEMPORARY => 'Temporary',
            default => $this->employment_type,
        };
    }

    public function getWorkLocationTypeNameAttribute()
    {
        return match($this->work_location_type) {
            self::WORK_LOCATION_REMOTE => 'Remote',
            self::WORK_LOCATION_ON_SITE => 'On-site',
            self::WORK_LOCATION_HYBRID => 'Hybrid',
            default => $this->work_location_type,
        };
    }

    public function getExperienceLevelNameAttribute()
    {
        return match($this->experience) {
            self::EXPERIENCE_ENTRY => 'Entry Level',
            self::EXPERIENCE_MID => 'Mid Level',
            self::EXPERIENCE_MID_SENIOR => 'Mid-Senior Level',
            self::EXPERIENCE_SENIOR => 'Senior Level',
            self::EXPERIENCE_MANAGER => 'Manager',
            self::EXPERIENCE_DIRECTOR => 'Director',
            self::EXPERIENCE_EXECUTIVE => 'Executive',
            default => $this->experience,
        };
    }

    // Scopes
    public function scopeFullTime($query)
    {
        return $query->where('employment_type', self::EMPLOYMENT_TYPE_FULL_TIME);
    }

    public function scopePartTime($query)
    {
        return $query->where('employment_type', self::EMPLOYMENT_TYPE_PART_TIME);
    }

    public function scopeRemote($query)
    {
        return $query->where('work_location_type', self::WORK_LOCATION_REMOTE);
    }

    public function scopeHybrid($query)
    {
        return $query->where('work_location_type', self::WORK_LOCATION_HYBRID);
    }

    public function scopeOnSite($query)
    {
        return $query->where('work_location_type', self::WORK_LOCATION_ON_SITE);
    }

    public function scopeEntryLevel($query)
    {
        return $query->where('experience', self::EXPERIENCE_ENTRY);
    }

    public function scopeSeniorLevel($query)
    {
        return $query->where('experience', self::EXPERIENCE_SENIOR);
    }

    /**
     * Filter jobs by search term (title, description or employer name)
     */
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        if (!$searchTerm) {
            return $query;
        }

        return $query->where(function($query) use ($searchTerm) {
            $query->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhereHas('employer', function($query) use ($searchTerm) {
                      $query->where('name', 'like', "%{$searchTerm}%");
                  });
        });
    }

    /**
     * Filter jobs by salary range
     */
    public function scopeSalaryRange(Builder $query, ?int $minSalary, ?int $maxSalary): Builder
    {
        $minSalary = $minSalary ?: 0;
        $maxSalary = $maxSalary ?: PHP_INT_MAX;

        if ($minSalary > $maxSalary) {
            return $query;
        }

        return $query->whereBetween('salary', [$minSalary, $maxSalary]);
    }

    /**
     * Filter jobs by experience level
     */
    public function scopeExperienceLevel(Builder $query, ?string $experience): Builder
    {
        if (!$experience) {
            return $query;
        }

        return $query->where('experience', $experience);
    }

    /**
     * Filter jobs by category
     */
    public function scopeCategory(Builder $query, ?int $categoryId): Builder
    {
        if (!$categoryId) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    /**
     * Filter jobs by location (city and/or country)
     */
    public function scopeLocation(Builder $query, ?string $city, ?string $country): Builder
    {
        if ($city) {
            $query->where('city', 'like', "%{$city}%");
        }

        if ($country) {
            $query->where('country', 'like', "%{$country}%");
        }

        return $query;
    }

    /**
     * Filter jobs by employment type
     */
    public function scopeEmploymentType(Builder $query, ?string $employmentType): Builder
    {
        if (!$employmentType) {
            return $query;
        }

        return $query->where('employment_type', $employmentType);
    }


    /**
     * Apply all filters from request
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->search($filters['title'] ?? null)
            ->salaryRange(
                isset($filters['min_salary']) ? (int)$filters['min_salary'] : null,
                isset($filters['max_salary']) ? (int)$filters['max_salary'] : null
            )
            ->experienceLevel($filters['experience'] ?? null)
            ->category($filters['category'] ?? null)
            ->location(
                $filters['city'] ?? null,
                $filters['country'] ?? null
            )
            ->employmentType($filters['employment_type'] ?? null);
    }
}

