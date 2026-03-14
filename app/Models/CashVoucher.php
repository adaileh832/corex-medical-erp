<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_number',
        'voucher_date',
        'voucher_type',
        'account_id',
        'amount',
        'description',
        'notes',
        'journal_entry_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'voucher_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}