<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogList extends Model
{
    protected $table = 'lists';

    protected $fillable = ['title', 'description', 'emoji', 'is_published', 'sort_order'];

    protected $casts = ['is_published' => 'boolean'];

    public function items(): HasMany
    {
        return $this->hasMany(ListItem::class, 'list_id')->orderBy('sort_order');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
