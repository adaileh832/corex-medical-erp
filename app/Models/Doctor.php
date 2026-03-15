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

    public function ledgers()
    {
        return $this->hasMany(DoctorLedger::class);
    }

    public function payments()
    {
        return $this->hasMany(DoctorPayment::class);
    }

    public function getTotalDueAttribute(): float
    {
        return (float) $this->ledgers()->sum('amount_due');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        return max($this->total_due - $this->total_paid, 0);
    }
}