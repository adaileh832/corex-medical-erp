<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorLedger extends Model
{
    use HasFactory;

    protected $table = 'doctor_ledgers';

    protected $fillable = [
        'doctor_id',
        'reference',
        'description',
        'amount_due',
        'entry_date',
        'notes',
    ];

    protected $casts = [
        'amount_due' => 'decimal:2',
        'entry_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}