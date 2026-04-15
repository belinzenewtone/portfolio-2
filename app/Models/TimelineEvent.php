<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineEvent extends Model
{
    protected $fillable = [
        'title', 'description', 'event_date', 'category', 'emoji', 'sort_order',
    ];

    protected $casts = ['event_date' => 'date'];

    public function scopeOrdered($query)
    {
        return $query->orderByDesc('event_date')->orderBy('sort_order');
    }
}
