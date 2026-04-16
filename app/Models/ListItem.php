<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListItem extends Model
{
    protected $fillable = ['list_id', 'text', 'url', 'note', 'sort_order', 'is_completed'];

    protected $casts = ['is_completed' => 'boolean'];

    public function list(): BelongsTo
    {
        return $this->belongsTo(BlogList::class, 'list_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
