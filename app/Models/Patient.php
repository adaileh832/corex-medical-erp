<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'name',
        'full_name',
        'phone',
        'email',
        'gender',
        'date_of_birth',
        'address',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: ($this->name ?? '-');
    }
}