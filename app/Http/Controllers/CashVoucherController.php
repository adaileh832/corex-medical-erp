<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CashVoucher;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashVoucherController extends Controller
{
    public function index(Request $request): View
    {
        $voucherType = trim((string) $request->get('voucher_type'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $cashVouchers = CashVoucher::query()
            ->with(['account', 'journalEntry'])
            ->when($voucherType, fn ($query) => $query->where('voucher_type', $voucherType))
            ->when($dateFrom, fn ($query) => $query->whereDate('voucher_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('voucher_date', '<=', $dateTo))
            ->latest('voucher_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('cash-vouchers.index', compact('cashVouchers', 'voucherType', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('cash-vouchers.create', [
            'accounts' => Account::query()->where('is_active', true)->orderBy('account_code')->get(),
            'nextVoucherNumber' => $this->generateVoucherNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'voucher_number' => ['required', 'string', 'max:100', 'unique:cash_vouchers,voucher_number'],
            'voucher_date' => ['required', 'date'],
            'voucher_type' => ['required', 'in:receipt,payment'],
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $cashAccount = Account::query()->where('account_code', '1110')->first();

        if (! $cashAccount) {
            return back()
                ->withErrors([
                    'account_id' => __('app.cash_account_missing'),
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $cashAccount) {
            $entry = JournalEntry::create([
                'entry_number' => 'CASH-' . $validated['voucher_number'],
                'entry_date' => $validated['voucher_date'],
                'description' => $validated['description'],
                'reference_type' => 'cash_voucher',
                'reference_number' => $validated['voucher_number'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            if ($validated['voucher_type'] === 'receipt') {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $cashAccount->id,
                    'description' => $validated['description'],
                    'debit' => $validated['amount'],
                    'credit' => 0,
                ]);

                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $validated['account_id'],
                    'description' => $validated['description'],
                    'debit' => 0,
                    'credit' => $validated['amount'],
                ]);
            } else {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $validated['account_id'],
                    'description' => $validated['description'],
                    'debit' => $validated['amount'],
                    'credit' => 0,
                ]);

                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $cashAccount->id,
                    'description' => $validated['description'],
                    'debit' => 0,
                    'credit' => $validated['amount'],
                ]);
            }

            CashVoucher::create([
                'voucher_number' => $validated['voucher_number'],
                'voucher_date' => $validated['voucher_date'],
                'voucher_type' => $validated['voucher_type'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'notes' => $validated['notes'] ?? null,
                'journal_entry_id' => $entry->id,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('cash-vouchers.index')
            ->with('success', __('app.cash_voucher_created'));
    }

    protected function generateVoucherNumber(): string
    {
        $last = CashVoucher::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'CV-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}