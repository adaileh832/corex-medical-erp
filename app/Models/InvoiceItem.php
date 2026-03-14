<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'procedure_id',
        'procedure_name',
        'price',
        'quantity',
        'line_total',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }
}
