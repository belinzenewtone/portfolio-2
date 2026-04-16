<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'date',
        'logo_url',
        'project_url',
        'bg_color',
        'sort_order',
        'is_visible',
        'is_featured',
    ];

    protected $casts = [
        'date'        => 'date',
        'is_visible'  => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('date');
    }
}
