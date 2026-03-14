<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected function casts(): array
    {
        return [
            'operation_date' => 'date',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function gynecologist()
    {
        return $this->belongsTo(Doctor::class, 'gynecologist_id');
    }

    public function anesthetist()
    {
        return $this->belongsTo(Doctor::class, 'anesthetist_id');
    }

    public function pediatrician()
    {
        return $this->belongsTo(Doctor::class, 'pediatrician_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function doctorTransactions()
    {
        return $this->hasMany(DoctorTransaction::class);
    }
}