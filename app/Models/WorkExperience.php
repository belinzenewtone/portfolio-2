<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'company',
        'role',
        'description',
        'period_start',
        'period_end',
        'is_current',
        'location',
        'employment_type',
        'logo_url',
        'sort_order',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'is_current' => 'boolean',
    ];

    public function getPeriodAttribute(): string
    {
        $start = $this->period_start ? $this->period_start->format('M Y') : '';
        $end = $this->is_current ? 'Present' : ($this->period_end ? $this->period_end->format('M Y') : '');
        return $start . ' – ' . $end;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('period_start');
    }
}
