<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public static array $categories  = [
            'Software Development',
            'Healthcare',
            'Finance',
            'Education',
            'Marketing',
            'Design',
            'Engineering',
            'Customer Service',
            'Sales',
            'Human Resources',
            'Administrative',
            'Information Technology',
            'Data Science',
            'Cybersecurity',
            'Product Management'
        ];


    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Set the slug when creating or updating a category
     *
     * Listen to the creating and updating events and set the slug
     * based on the name.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
