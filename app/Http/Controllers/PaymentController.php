<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function create(Invoice $invoice): View
    {
        $paymentMethods = [
            'cash' => 'كاش / Cash',
            'cliq' => 'كليك / CliQ',
        ];

        return view('payments.create', compact('invoice', 'paymentMethods'));
    }

    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,cliq'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ], [
            'amount.required' => 'مبلغ الدفعة مطلوب / Payment amount is required.',
            'amount.numeric' => 'مبلغ الدفعة يجب أن يكون رقمًا / Payment amount must be numeric.',
            'amount.min' => 'مبلغ الدفعة يجب أن يكون أكبر من صفر / Payment amount must be greater than zero.',
            'payment_method.required' => 'طريقة الدفع مطلوبة / Payment method is required.',
            'payment_method.in' => 'طريقة الدفع يجب أن تكون Cash أو CliQ / Payment method must be Cash or CliQ.',
            'payment_date.required' => 'تاريخ الدفع مطلوب / Payment date is required.',
            'payment_date.date' => 'تاريخ الدفع غير صالح / Payment date is invalid.',
        ]);

        DB::transaction(function () use ($validated, $invoice) {
            Payment::create([
                'invoice_id' => $invoice->id,
                'receipt_number' => $this->generateReceiptNumber(),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_date' => $validated['payment_date'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $this->refreshInvoiceFinancials($invoice->fresh());
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'تم تسجيل الدفعة بنجاح / Payment recorded successfully.');
    }

    public function destroy(Invoice $invoice, Payment $payment): RedirectResponse
    {
        if ((int) $payment->invoice_id !== (int) $invoice->id) {
            abort(404);
        }

        DB::transaction(function () use ($invoice, $payment) {
            $payment->delete();
            $this->refreshInvoiceFinancials($invoice->fresh());
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'تم حذف الدفعة بنجاح / Payment deleted successfully.');
    }

    private function generateReceiptNumber(): string
    {
        $datePrefix = now()->format('Ymd');
        $basePrefix = 'RCPT-' . $datePrefix . '-';

        $lastPayment = Payment::query()
            ->whereDate('created_at', now()->toDateString())
            ->where('receipt_number', 'like', $basePrefix . '%')
            ->latest('id')
            ->first();

        $nextSequence = 1;

        if ($lastPayment && !empty($lastPayment->receipt_number)) {
            $parts = explode('-', $lastPayment->receipt_number);
            $lastSequence = (int) end($parts);
            $nextSequence = $lastSequence + 1;
        }

        return $basePrefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    private function refreshInvoiceFinancials(Invoice $invoice): void
    {
        $paidAmount = (float) $invoice->payments()->sum('amount');

        $invoiceTable = $invoice->getTable();
        $updates = [];

        $grandTotal = 0.0;

        if (Schema::hasColumn($invoiceTable, 'grand_total')) {
            $grandTotal = (float) ($invoice->grand_total ?? 0);
        } elseif (Schema::hasColumn($invoiceTable, 'total_amount')) {
            $grandTotal = (float) ($invoice->total_amount ?? 0);
        } elseif (Schema::hasColumn($invoiceTable, 'total')) {
            $grandTotal = (float) ($invoice->total ?? 0);
        }

        $remainingAmount = max($grandTotal - $paidAmount, 0);

        if (Schema::hasColumn($invoiceTable, 'paid_amount')) {
            $updates['paid_amount'] = $paidAmount;
        }

        if (Schema::hasColumn($invoiceTable, 'remaining_amount')) {
            $updates['remaining_amount'] = $remainingAmount;
        }

        if (Schema::hasColumn($invoiceTable, 'due_amount')) {
            $updates['due_amount'] = $remainingAmount;
        }

        if (Schema::hasColumn($invoiceTable, 'payment_status')) {
            $updates['payment_status'] = match (true) {
                $paidAmount <= 0 => 'unpaid',
                $remainingAmount <= 0 => 'paid',
                default => 'partial',
            };
        }

        if (Schema::hasColumn($invoiceTable, 'status')) {
            $updates['status'] = match (true) {
                $paidAmount <= 0 => 'unpaid',
                $remainingAmount <= 0 => 'paid',
                default => 'partial',
            };
        }

        if (!empty($updates)) {
            $invoice->update($updates);
        }
    }
}