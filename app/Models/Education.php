<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'institution',
        'degree',
        'field',
        'description',
        'period_start',
        'period_end',
        'logo_url',
        'sort_order',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function getPeriodAttribute(): string
    {
        $start = $this->period_start ? $this->period_start->format('Y') : '';
        $end = $this->period_end ? $this->period_end->format('Y') : 'Present';
        return $start . ' – ' . $end;
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderByDesc('period_start');
    }
}
