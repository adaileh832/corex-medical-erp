<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Invoice $invoice): View
    {
        $invoice->load(['patient', 'doctor', 'procedure', 'payments']);

        return view('payments.create', [
            'invoice' => $invoice,
            'nextReceiptNumber' => $this->generateReceiptNumber(),
        ]);
    }

    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->load('payments');

        $remainingAmount = max(0, (float) $invoice->remaining_amount);

        $validated = $request->validate([
            'receipt_number' => ['required', 'string', 'max:100', 'unique:payments,receipt_number'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . max(0.01, $remainingAmount)],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['invoice_id'] = $invoice->id;
        $validated['created_by'] = auth()->id();

        Payment::create($validated);

        $invoice->refresh();
        $this->syncInvoiceStatus($invoice);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', __('app.payment_created'));
    }

    public function destroy(Invoice $invoice, Payment $payment): RedirectResponse
    {
        abort_unless((int) $payment->invoice_id === (int) $invoice->id, 404);

        $payment->delete();

        $invoice->refresh();
        $this->syncInvoiceStatus($invoice);

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', __('app.payment_deleted'));
    }

    protected function syncInvoiceStatus(Invoice $invoice): void
    {
        $paid = (float) $invoice->payments()->sum('amount');
        $amount = (float) $invoice->amount;

        if ($paid <= 0) {
            $invoice->update(['status' => 'unpaid']);
            return;
        }

        if ($paid >= $amount) {
            $invoice->update(['status' => 'paid']);
            return;
        }

        $invoice->update(['status' => 'partially_paid']);
    }

    protected function generateReceiptNumber(): string
    {
        $lastPayment = Payment::query()->latest('id')->first();
        $nextId = $lastPayment ? ($lastPayment->id + 1) : 1;

        return 'PAY-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}