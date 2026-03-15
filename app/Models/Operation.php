<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'procedure_id',
        'invoice_id',
        'operation_date',
        'gynecologist_id',
        'anesthetist_id',
        'pediatrician_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'operation_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gynecologist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'gynecologist_id');
    }

    public function anesthetist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anesthetist_id');
    }

    public function pediatrician(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'pediatrician_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function doctorTransactions(): HasMany
    {
        return $this->hasMany(DoctorTransaction::class);
    }
}