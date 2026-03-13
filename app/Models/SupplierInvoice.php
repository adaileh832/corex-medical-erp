<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'amount',
        'description',
        'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}