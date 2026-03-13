<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'payment_number',
        'payment_date',
        'amount',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}