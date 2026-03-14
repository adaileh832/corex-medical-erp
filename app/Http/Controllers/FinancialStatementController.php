<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FinancialStatementController extends Controller
{
    public function incomeStatement(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $revenueAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'revenue')
            ->orderBy('account_code')
            ->get();

        $expenseAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'expense')
            ->orderBy('account_code')
            ->get();

        $revenues = $this->buildAccountRows($revenueAccounts, $dateFrom, $dateTo);
        $expenses = $this->buildAccountRows($expenseAccounts, $dateFrom, $dateTo);

        $totalRevenue = (float) $revenues->sum('balance');
        $totalExpense = (float) $expenses->sum('balance');
        $netProfit = $totalRevenue - $totalExpense;

        return view('financial-statements.income-statement', compact(
            'revenues',
            'expenses',
            'totalRevenue',
            'totalExpense',
            'netProfit',
            'dateFrom',
            'dateTo'
        ));
    }

    public function balanceSheet(Request $request): View
    {
        $dateTo = $request->get('date_to');

        $assetAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'asset')
            ->orderBy('account_code')
            ->get();

        $liabilityAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'liability')
            ->orderBy('account_code')
            ->get();

        $equityAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'equity')
            ->orderBy('account_code')
            ->get();

        $assets = $this->buildAccountRows($assetAccounts, null, $dateTo);
        $liabilities = $this->buildAccountRows($liabilityAccounts, null, $dateTo);
        $equity = $this->buildAccountRows($equityAccounts, null, $dateTo);

        $revenueAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'revenue')
            ->get();

        $expenseAccounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'expense')
            ->get();

        $revenues = $this->buildAccountRows($revenueAccounts, null, $dateTo);
        $expenses = $this->buildAccountRows($expenseAccounts, null, $dateTo);

        $retainedEarnings = (float) $revenues->sum('balance') - (float) $expenses->sum('balance');

        $totalAssets = (float) $assets->sum('balance');
        $totalLiabilities = (float) $liabilities->sum('balance');
        $totalEquity = (float) $equity->sum('balance') + $retainedEarnings;
        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquity;

        return view('financial-statements.balance-sheet', compact(
            'assets',
            'liabilities',
            'equity',
            'retainedEarnings',
            'totalAssets',
            'totalLiabilities',
            'totalEquity',
            'totalLiabilitiesAndEquity',
            'dateTo'
        ));
    }

    public function revenueReport(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $accounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'revenue')
            ->orderBy('account_code')
            ->get();

        $rows = $this->buildAccountRows($accounts, $dateFrom, $dateTo);
        $totalRevenue = (float) $rows->sum('balance');

        return view('financial-statements.revenue-report', compact('rows', 'totalRevenue', 'dateFrom', 'dateTo'));
    }

    public function expenseReport(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $accounts = Account::query()
            ->where('is_active', true)
            ->where('account_type', 'expense')
            ->orderBy('account_code')
            ->get();

        $rows = $this->buildAccountRows($accounts, $dateFrom, $dateTo);
        $totalExpense = (float) $rows->sum('balance');

        return view('financial-statements.expense-report', compact('rows', 'totalExpense', 'dateFrom', 'dateTo'));
    }

    public function cashMovement(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $cashAccount = Account::query()->where('account_code', '1110')->first();

        $rows = collect();
        $totalIn = 0.0;
        $totalOut = 0.0;
        $balance = 0.0;

        if ($cashAccount) {
            [$rows, $totalIn, $totalOut, $balance] = $this->buildMovementRows($cashAccount, $dateFrom, $dateTo);
        }

        return view('financial-statements.cash-movement', compact(
            'cashAccount',
            'rows',
            'totalIn',
            'totalOut',
            'balance',
            'dateFrom',
            'dateTo'
        ));
    }

    public function bankMovement(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $bankAccount = Account::query()->where('account_code', '1120')->first();

        $rows = collect();
        $totalIn = 0.0;
        $totalOut = 0.0;
        $balance = 0.0;

        if ($bankAccount) {
            [$rows, $totalIn, $totalOut, $balance] = $this->buildMovementRows($bankAccount, $dateFrom, $dateTo);
        }

        return view('financial-statements.bank-movement', compact(
            'bankAccount',
            'rows',
            'totalIn',
            'totalOut',
            'balance',
            'dateFrom',
            'dateTo'
        ));
    }

    protected function buildAccountRows(Collection $accounts, ?string $dateFrom, ?string $dateTo): Collection
    {
        return $accounts->map(function (Account $account) use ($dateFrom, $dateTo) {
            $linesQuery = JournalEntryLine::query()
                ->where('account_id', $account->id)
                ->whereHas('journalEntry');

            if ($dateFrom) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $dateFrom));
            }

            if ($dateTo) {
                $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '<=', $dateTo));
            }

            $debit = (float) (clone $linesQuery)->sum('debit');
            $credit = (float) (clone $linesQuery)->sum('credit');

            $balance = $account->normal_balance === 'debit'
                ? $debit - $credit
                : $credit - $debit;

            return [
                'account' => $account,
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $balance,
            ];
        })->filter(fn ($row) => round((float) $row['balance'], 2) !== 0.0)->values();
    }

    protected function buildMovementRows(Account $account, ?string $dateFrom, ?string $dateTo): array
    {
        $linesQuery = JournalEntryLine::query()
            ->with('journalEntry')
            ->where('account_id', $account->id)
            ->whereHas('journalEntry');

        if ($dateFrom) {
            $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $dateFrom));
        }

        if ($dateTo) {
            $linesQuery->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '<=', $dateTo));
        }

        $lines = $linesQuery->get()
            ->sortBy([
                fn ($a, $b) => strcmp($a->journalEntry->entry_date?->format('Y-m-d') ?? '', $b->journalEntry->entry_date?->format('Y-m-d') ?? ''),
                fn ($a, $b) => $a->journalEntry->id <=> $b->journalEntry->id,
            ])
            ->values();

        $runningBalance = 0.0;
        $totalIn = 0.0;
        $totalOut = 0.0;

        $rows = $lines->map(function (JournalEntryLine $line) use (&$runningBalance, &$totalIn, &$totalOut, $account) {
            $inAmount = 0.0;
            $outAmount = 0.0;

            if ($account->normal_balance === 'debit') {
                $inAmount = (float) $line->debit;
                $outAmount = (float) $line->credit;
                $runningBalance += $inAmount - $outAmount;
            } else {
                $inAmount = (float) $line->credit;
                $outAmount = (float) $line->debit;
                $runningBalance += $inAmount - $outAmount;
            }

            $totalIn += $inAmount;
            $totalOut += $outAmount;

            return [
                'entry_date' => $line->journalEntry?->entry_date,
                'entry_number' => $line->journalEntry?->entry_number,
                'description' => $line->description ?: ($line->journalEntry?->description ?? '-'),
                'in_amount' => $inAmount,
                'out_amount' => $outAmount,
                'running_balance' => $runningBalance,
            ];
        });

        return [$rows, $totalIn, $totalOut, $runningBalance];
    }
}