<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\BankTransaction;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BankTransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactionType = trim((string) $request->get('transaction_type'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $bankTransactions = BankTransaction::query()
            ->with(['account', 'journalEntry'])
            ->when($transactionType, fn ($query) => $query->where('transaction_type', $transactionType))
            ->when($dateFrom, fn ($query) => $query->whereDate('transaction_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('transaction_date', '<=', $dateTo))
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('bank-transactions.index', compact('bankTransactions', 'transactionType', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('bank-transactions.create', [
            'accounts' => Account::query()->where('is_active', true)->orderBy('account_code')->get(),
            'nextTransactionNumber' => $this->generateTransactionNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_number' => ['required', 'string', 'max:100', 'unique:bank_transactions,transaction_number'],
            'transaction_date' => ['required', 'date'],
            'transaction_type' => ['required', 'in:deposit,withdraw'],
            'account_id' => ['required', 'exists:accounts,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $bankAccount = Account::query()->where('account_code', '1120')->first();

        if (! $bankAccount) {
            return back()
                ->withErrors([
                    'account_id' => __('app.bank_account_missing'),
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated, $bankAccount) {
            $entry = JournalEntry::create([
                'entry_number' => 'BANK-' . $validated['transaction_number'],
                'entry_date' => $validated['transaction_date'],
                'description' => $validated['description'],
                'reference_type' => 'bank_transaction',
                'reference_number' => $validated['transaction_number'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            if ($validated['transaction_type'] === 'deposit') {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $bankAccount->id,
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
                    'account_id' => $bankAccount->id,
                    'description' => $validated['description'],
                    'debit' => 0,
                    'credit' => $validated['amount'],
                ]);
            }

            BankTransaction::create([
                'transaction_number' => $validated['transaction_number'],
                'transaction_date' => $validated['transaction_date'],
                'transaction_type' => $validated['transaction_type'],
                'account_id' => $validated['account_id'],
                'amount' => $validated['amount'],
                'description' => $validated['description'],
                'notes' => $validated['notes'] ?? null,
                'journal_entry_id' => $entry->id,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('bank-transactions.index')
            ->with('success', __('app.bank_transaction_created'));
    }

    protected function generateTransactionNumber(): string
    {
        $last = BankTransaction::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'BT-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}