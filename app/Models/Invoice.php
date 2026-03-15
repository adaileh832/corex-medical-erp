<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'invoice_date',
        'service_name',
        'name',
        'subtotal',
        'discount',
        'tax',
        'total',
        'paid_amount',
        'status',
        'notes',
        'payment_method',
        'payment_date',
        'currency_code',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)->latest('payment_date');
    }

    public function getBalanceAttribute(): float
    {
        return max((float) $this->total - (float) $this->paid_amount, 0);
    }

    public function getDisplayStatusAttribute(): string
    {
        return $this->status ?: 'unpaid';
    }

    public function getDisplayServiceNameAttribute(): string
    {
        return $this->service_name ?: ($this->name ?? '-');
    }
}