<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'account_code',
        'name',
        'name_en',
        'account_type',
        'normal_balance',
        'is_active',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(Account::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Account::class, 'parent_id');
    }

    public function journalEntryLines()
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    public function cashVouchers()
    {
        return $this->hasMany(CashVoucher::class, 'account_id');
    }

    public function bankTransactions()
    {
        return $this->hasMany(BankTransaction::class, 'account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTotalDebitAttribute(): float
    {
        return (float) $this->journalEntryLines()->sum('debit');
    }

    public function getTotalCreditAttribute(): float
    {
        return (float) $this->journalEntryLines()->sum('credit');
    }

    public function getBalanceAttribute(): float
    {
        if ($this->normal_balance === 'debit') {
            return (float) $this->total_debit - (float) $this->total_credit;
        }

        return (float) $this->total_credit - (float) $this->total_debit;
    }
}