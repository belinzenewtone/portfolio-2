<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Skill extends Model
{
    protected $fillable = ['name', 'category', 'icon_url', 'color', 'sort_order', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];
    public function scopeOrdered($q) { return $q->orderBy('sort_order')->orderBy('name'); }
    public function scopeVisible($q) { return $q->where('is_visible', true); }
}
