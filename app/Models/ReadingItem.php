<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingItem extends Model
{
    protected $fillable = [
        'title', 'author', 'cover_url', 'status',
        'year_read', 'rating', 'notes', 'buy_url', 'sort_order',
    ];

    public function scopeOrdered($query)
    {
        return $query
            ->orderByRaw("
                CASE status
                    WHEN 'currently_reading' THEN 1
                    WHEN 'completed' THEN 2
                    WHEN 'want_to_read' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('sort_order');
    }
}
