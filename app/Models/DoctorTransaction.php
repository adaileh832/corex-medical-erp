<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'operation_id',
        'patient_id',
        'procedure_id',
        'transaction_date',
        'amount',
        'type',
        'description',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}