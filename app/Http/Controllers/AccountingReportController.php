<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountingReportController extends Controller
{
    public function ledger(Request $request): View
    {
        $accountId = $request->get('account_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $accounts = Account::query()->where('is_active', true)->orderBy('account_code')->get();

        $selectedAccount = null;
        $lines = collect();
        $totalDebit = 0;
        $totalCredit = 0;
        $balance = 0;

        if ($accountId) {
            $selectedAccount = Account::query()->findOrFail($accountId);

            $lines = JournalEntryLine::query()
                ->with(['journalEntry'])
                ->where('account_id', $accountId)
                ->when($dateFrom, fn ($query) => $query->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $dateFrom)))
                ->when($dateTo, fn ($query) => $query->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '<=', $dateTo)))
                ->whereHas('journalEntry')
                ->get()
                ->sortBy([
                    fn ($a, $b) => strcmp($a->journalEntry->entry_date?->format('Y-m-d') ?? '', $b->journalEntry->entry_date?->format('Y-m-d') ?? ''),
                    fn ($a, $b) => $a->journalEntry->id <=> $b->journalEntry->id,
                ])
                ->values();

            $totalDebit = (float) $lines->sum('debit');
            $totalCredit = (float) $lines->sum('credit');
            $balance = $selectedAccount->normal_balance === 'debit'
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;
        }

        return view('accounting-reports.ledger', compact(
            'accounts',
            'selectedAccount',
            'lines',
            'totalDebit',
            'totalCredit',
            'balance',
            'accountId',
            'dateFrom',
            'dateTo'
        ));
    }

    public function trialBalance(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $accounts = Account::query()
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();

        $rows = $accounts->map(function (Account $account) use ($dateFrom, $dateTo) {
            $linesQuery = JournalEntryLine::query()
                ->where('account_id', $account->id)
                ->whereHas('journalEntry');

            if ($dateFrom) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $dateFrom));
            }

            if ($dateTo) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '<=', $dateTo));
            }

            $debit = (float) $linesQuery->sum('debit');

            $linesQuery = JournalEntryLine::query()
                ->where('account_id', $account->id)
                ->whereHas('journalEntry');

            if ($dateFrom) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $dateFrom));
            }

            if ($dateTo) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '<=', $dateTo));
            }

            $credit = (float) $linesQuery->sum('credit');

            return [
                'account' => $account,
                'debit' => $debit,
                'credit' => $credit,
            ];
        })->filter(fn ($row) => $row['debit'] > 0 || $row['credit'] > 0)->values();

        $totalDebit = (float) $rows->sum('debit');
        $totalCredit = (float) $rows->sum('credit');

        return view('accounting-reports.trial-balance', compact('rows', 'dateFrom', 'dateTo', 'totalDebit', 'totalCredit'));
    }
}