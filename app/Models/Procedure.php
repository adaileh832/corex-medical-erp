<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'price',
        'gynecologist_fee',
        'anesthetist_fee',
        'pediatrician_fee',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'gynecologist_fee' => 'decimal:2',
            'anesthetist_fee' => 'decimal:2',
            'pediatrician_fee' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
}