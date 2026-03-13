<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $supplierPayments = SupplierPayment::query()
            ->with(['supplier', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('payment_number', 'like', "%{$search}%")
                        ->orWhere('payment_method', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('supplier', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate('payment_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('payment_date', '<=', $dateTo))
            ->latest('payment_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('supplier-payments.index', compact('supplierPayments', 'search', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('supplier-payments.create', [
            'suppliers' => Supplier::query()->orderBy('name')->get(),
            'nextPaymentNumber' => $this->generatePaymentNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'payment_number' => ['required', 'string', 'max:100', 'unique:supplier_payments,payment_number'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        SupplierPayment::create($validated);

        return redirect()
            ->route('supplier-payments.index')
            ->with('success', __('app.supplier_payment_created'));
    }

    public function edit(SupplierPayment $supplierPayment): View
    {
        return view('supplier-payments.edit', [
            'supplierPayment' => $supplierPayment,
            'suppliers' => Supplier::query()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, SupplierPayment $supplierPayment): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'payment_number' => ['required', 'string', 'max:100', 'unique:supplier_payments,payment_number,' . $supplierPayment->id],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $supplierPayment->update($validated);

        return redirect()
            ->route('supplier-payments.index')
            ->with('success', __('app.supplier_payment_updated'));
    }

    public function destroy(SupplierPayment $supplierPayment): RedirectResponse
    {
        $supplierPayment->delete();

        return redirect()
            ->route('supplier-payments.index')
            ->with('success', __('app.supplier_payment_deleted'));
    }

    protected function generatePaymentNumber(): string
    {
        $last = SupplierPayment::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'SUP-PAY-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}