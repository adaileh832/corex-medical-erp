<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $invoices = Invoice::query()
            ->with(['patient', 'doctor', 'procedure', 'payments', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('service_name', 'like', "%{$search}%")
                        ->orWhereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('doctor', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('procedure', fn ($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate('invoice_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('invoice_date', '<=', $dateTo))
            ->latest('invoice_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', compact('invoices', 'search', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('invoices.create', [
            'patients' => Patient::query()->orderBy('name')->get(),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('name')->get(),
            'procedures' => Procedure::query()->where('is_active', true)->orderBy('name')->get(),
            'nextInvoiceNumber' => $this->generateInvoiceNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number' => ['required', 'string', 'max:100', 'unique:invoices,invoice_number'],
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'procedure_id' => ['nullable', 'exists:procedures,id'],
            'service_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'invoice_date' => ['required', 'date'],
            'status' => ['required', 'in:unpaid,partially_paid,paid'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        Invoice::create($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', __('app.invoice_created'));
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['patient', 'doctor', 'procedure', 'payments.creator', 'creator']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        return view('invoices.edit', [
            'invoice' => $invoice,
            'patients' => Patient::query()->orderBy('name')->get(),
            'doctors' => Doctor::query()->where('is_active', true)->orderBy('name')->get(),
            'procedures' => Procedure::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_number' => ['required', 'string', 'max:100', 'unique:invoices,invoice_number,' . $invoice->id],
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'procedure_id' => ['nullable', 'exists:procedures,id'],
            'service_name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'invoice_date' => ['required', 'date'],
            'status' => ['required', 'in:unpaid,partially_paid,paid'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('invoices.index')
            ->with('success', __('app.invoice_updated'));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->payments()->delete();
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', __('app.invoice_deleted'));
    }

    protected function generateInvoiceNumber(): string
    {
        $lastInvoice = Invoice::query()->latest('id')->first();
        $nextId = $lastInvoice ? ($lastInvoice->id + 1) : 1;

        return 'INV-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}