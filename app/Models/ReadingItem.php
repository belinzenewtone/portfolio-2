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
        return $query->orderByRaw("FIELD(status, 'currently_reading', 'completed', 'want_to_read')")
                     ->orderBy('sort_order');
    }
}
