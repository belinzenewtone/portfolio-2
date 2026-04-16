<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'name',
        'title',
        'subtitle',
        'location',
        'bio',
        'email',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'profile_photo',
        'current_company',
        'years_experience',
        'cv_url',
        'is_available',
        'availability_text',
        'show_availability',
    ];

    protected $casts = [
        'is_available'      => 'boolean',
        'show_availability' => 'boolean',
    ];
}
