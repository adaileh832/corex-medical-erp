<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'name',
        'phone',
        'identity_number',
        'address',
        'notes',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}