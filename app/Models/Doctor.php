<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';

    protected $fillable = [
        'name',
        'full_name',
        'national_id',
        'doctor_type',
        'specialty',
        'phone',
        'email',
        'license_number',
        'notes',
    ];

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: ($this->name ?? '-');
    }
}
