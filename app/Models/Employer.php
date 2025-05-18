<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employer extends Model
{
    /** @use HasFactory<\Database\Factories\EmployerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'website',
        'logo',
        'category_id',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($employer) {
            $employer->slug = Str::slug($employer->name);
        });

        static::updating(function ($employer) {
            $employer->slug = Str::slug($employer->name);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getWebsiteHostAttribute()
    {
        return $this->website ? parse_url($this->website, PHP_URL_HOST) : null;
    }
}
