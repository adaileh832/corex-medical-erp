<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'operation_id',
        'invoice_id',
        'procedure_id',
        'patient_id',
        'transaction_date',
        'doctor_role',
        'description',
        'amount',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}