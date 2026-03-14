<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'patients' => $this->countTable('patients'),
            'doctors' => $this->countTable('doctors'),
            'invoices' => $this->countTable('invoices'),
            'users' => $this->countTable('users'),
            'payments' => $this->countTable('payments'),
            'employees' => $this->countTable('employees'),
        ];

        $financial = [
            'invoice_total' => $this->sumTableColumn('invoices', 'total'),
            'paid_total' => $this->sumTableColumn('invoices', 'paid_amount'),
            'payments_total' => $this->sumTableColumn('payments', 'amount'),
        ];

        $recentInvoices = $this->getRecentInvoices();
        $currentUser = Auth::user();

        return view('dashboard.index', [
            'stats' => $stats,
            'financial' => $financial,
            'recentInvoices' => $recentInvoices,
            'currentUser' => $currentUser,
        ]);
    }

    private function countTable(string $table): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        return DB::table($table)->count();
    }

    private function sumTableColumn(string $table, string $column): float
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $column)) {
            return 0;
        }

        return (float) DB::table($table)->sum($column);
    }

    private function getRecentInvoices()
    {
        if (! Schema::hasTable('invoices')) {
            return collect();
        }

        $query = DB::table('invoices');

        if (Schema::hasColumn('invoices', 'invoice_number')) {
            $query->addSelect('invoice_number');
        }

        if (Schema::hasColumn('invoices', 'total')) {
            $query->addSelect('total');
        }

        if (Schema::hasColumn('invoices', 'paid_amount')) {
            $query->addSelect('paid_amount');
        }

        if (Schema::hasColumn('invoices', 'status')) {
            $query->addSelect('status');
        }

        if (Schema::hasColumn('invoices', 'created_at')) {
            $query->addSelect('created_at')->orderByDesc('created_at');
        } else {
            $query->orderByDesc('id');
        }

        return $query->limit(5)->get();
    }
}