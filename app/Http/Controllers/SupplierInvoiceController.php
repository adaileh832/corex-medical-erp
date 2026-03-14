<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierInvoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierInvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $supplierInvoices = SupplierInvoice::query()
            ->with(['supplier', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate('invoice_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('invoice_date', '<=', $dateTo))
            ->latest('invoice_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('supplier-invoices.index', compact('supplierInvoices', 'search', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('supplier-invoices.create', [
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'nextInvoiceNumber' => $this->generateInvoiceNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'invoice_number' => ['required', 'string', 'max:100', 'unique:supplier_invoices,invoice_number'],
            'invoice_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        SupplierInvoice::create($validated);

        return redirect()
            ->route('supplier-invoices.index')
            ->with('success', __('app.supplier_invoice_created'));
    }

    public function edit(SupplierInvoice $supplierInvoice): View
    {
        return view('supplier-invoices.edit', [
            'supplierInvoice' => $supplierInvoice,
            'suppliers' => Supplier::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SupplierInvoice $supplierInvoice): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'invoice_number' => ['required', 'string', 'max:100', 'unique:supplier_invoices,invoice_number,' . $supplierInvoice->id],
            'invoice_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $supplierInvoice->update($validated);

        return redirect()
            ->route('supplier-invoices.index')
            ->with('success', __('app.supplier_invoice_updated'));
    }

    public function destroy(SupplierInvoice $supplierInvoice): RedirectResponse
    {
        $supplierInvoice->delete();

        return redirect()
            ->route('supplier-invoices.index')
            ->with('success', __('app.supplier_invoice_deleted'));
    }

    protected function generateInvoiceNumber(): string
    {
        $last = SupplierInvoice::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'SUP-INV-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}