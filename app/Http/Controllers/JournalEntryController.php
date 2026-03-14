<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class JournalEntryController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $journalEntries = JournalEntry::query()
            ->withCount('lines')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('entry_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate('entry_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('entry_date', '<=', $dateTo))
            ->latest('entry_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('journal-entries.index', compact('journalEntries', 'search', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('journal-entries.create', [
            'accounts' => Account::query()->where('is_active', true)->orderBy('account_code')->get(),
            'nextEntryNumber' => $this->generateEntryNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entry_number' => ['required', 'string', 'max:100', 'unique:journal_entries,entry_number'],
            'entry_date' => ['required', 'date'],
            'description' => ['required', 'string', 'max:255'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
            'lines' => ['required', 'array', 'min:2'],
            'lines.*.account_id' => ['required', 'exists:accounts,id'],
            'lines.*.description' => ['nullable', 'string', 'max:255'],
            'lines.*.debit' => ['nullable', 'numeric', 'min:0'],
            'lines.*.credit' => ['nullable', 'numeric', 'min:0'],
        ]);

        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($validated['lines'] as $line) {
            $debit = (float) ($line['debit'] ?? 0);
            $credit = (float) ($line['credit'] ?? 0);

            $totalDebit += $debit;
            $totalCredit += $credit;

            if (($debit > 0 && $credit > 0) || ($debit <= 0 && $credit <= 0)) {
                return back()
                    ->withErrors([
                        'lines' => __('app.invalid_journal_line'),
                    ])
                    ->withInput();
            }
        }

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()
                ->withErrors([
                    'lines' => __('app.journal_entry_not_balanced'),
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            $entry = JournalEntry::create([
                'entry_number' => $validated['entry_number'],
                'entry_date' => $validated['entry_date'],
                'description' => $validated['description'],
                'reference_type' => $validated['reference_type'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            foreach ($validated['lines'] as $line) {
                JournalEntryLine::create([
                    'journal_entry_id' => $entry->id,
                    'account_id' => $line['account_id'],
                    'description' => $line['description'] ?? null,
                    'debit' => (float) ($line['debit'] ?? 0),
                    'credit' => (float) ($line['credit'] ?? 0),
                ]);
            }
        });

        return redirect()
            ->route('journal-entries.index')
            ->with('success', __('app.journal_entry_created'));
    }

    public function show(JournalEntry $journalEntry): View
    {
        $journalEntry->load(['lines.account', 'creator']);

        return view('journal-entries.show', compact('journalEntry'));
    }

    protected function generateEntryNumber(): string
    {
        $last = JournalEntry::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'JV-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}