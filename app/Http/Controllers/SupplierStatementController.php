<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierInvoice;
use App\Models\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierStatementController extends Controller
{
    public function index(Request $request): View
    {
        $supplierId = $request->get('supplier_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $suppliers = Supplier::query()->orderBy('name')->get();

        $selectedSupplier = null;
        $supplierInvoices = collect();
        $supplierPayments = collect();
        $totalInvoices = 0;
        $totalPayments = 0;
        $balance = 0;

        if ($supplierId) {
            $selectedSupplier = Supplier::query()->findOrFail($supplierId);

            $supplierInvoices = SupplierInvoice::query()
                ->where('supplier_id', $supplierId)
                ->when($dateFrom, fn ($query) => $query->whereDate('invoice_date', '>=', $dateFrom))
                ->when($dateTo, fn ($query) => $query->whereDate('invoice_date', '<=', $dateTo))
                ->orderBy('invoice_date')
                ->orderBy('id')
                ->get();

            $supplierPayments = SupplierPayment::query()
                ->where('supplier_id', $supplierId)
                ->when($dateFrom, fn ($query) => $query->whereDate('payment_date', '>=', $dateFrom))
                ->when($dateTo, fn ($query) => $query->whereDate('payment_date', '<=', $dateTo))
                ->orderBy('payment_date')
                ->orderBy('id')
                ->get();

            $totalInvoices = (float) $supplierInvoices->sum('amount');
            $totalPayments = (float) $supplierPayments->sum('amount');
            $balance = $totalInvoices - $totalPayments;
        }

        return view('supplier-statements.index', compact(
            'suppliers',
            'selectedSupplier',
            'supplierInvoices',
            'supplierPayments',
            'totalInvoices',
            'totalPayments',
            'balance',
            'supplierId',
            'dateFrom',
            'dateTo'
        ));
    }
}