<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $table = 'procedures';

    protected $fillable = [
        'name',
        'title',
        'code',
        'price',
        'duration_minutes',
        'description',
        'is_active',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: ($this->title ?? '-');
    }

    public function getDisplayStatusAttribute(): string
    {
        if (array_key_exists('is_active', $this->attributes)) {
            return $this->is_active ? 'active' : 'inactive';
        }

        return $this->status ?? 'inactive';
    }
}
