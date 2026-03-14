<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::query()
            ->with('patient')
            ->latest()
            ->paginate(10);

        return view('invoices.index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(): View
    {
        $patients = Patient::query()->orderBy('full_name')->get();
        $procedures = Procedure::query()->orderBy('name')->get();

        return view('invoices.create', [
            'patients' => $patients,
            'procedures' => $procedures,
            'paymentMethods' => config('hospital.payment_methods', []),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['required', 'in:cash,cliq'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.procedure_id' => ['required', 'exists:procedures,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ], [
            'patient_id.required' => 'المريض مطلوب / Patient is required.',
            'items.required' => 'يجب إضافة إجراء واحد على الأقل / At least one procedure is required.',
            'payment_method.required' => 'طريقة الدفع مطلوبة / Payment method is required.',
        ]);

        DB::transaction(function () use ($validated) {
            $discount = (float) ($validated['discount'] ?? 0);
            $paidAmount = (float) ($validated['paid_amount'] ?? 0);

            $invoice = Invoice::create([
                'invoice_number' => $this->generateInvoiceNumber(),
                'patient_id' => $validated['patient_id'],
                'subtotal' => 0,
                'discount' => $discount,
                'tax' => 0,
                'total' => 0,
                'paid_amount' => $paidAmount,
                'status' => 'unpaid',
                'notes' => $validated['notes'] ?? null,
                'payment_method' => $validated['payment_method'],
                'currency_code' => config('hospital.currency_code', 'JOD'),
            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $procedure = Procedure::findOrFail($item['procedure_id']);
                $quantity = (int) $item['quantity'];
                $price = (float) $procedure->price;
                $lineTotal = $price * $quantity;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'procedure_id' => $procedure->id,
                    'procedure_name' => $procedure->display_name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ]);

                $subtotal += $lineTotal;
            }

            $total = max($subtotal - $discount, 0);

            $status = 'unpaid';
            if ($paidAmount > 0 && $paidAmount < $total) {
                $status = 'partial';
            } elseif ($paidAmount >= $total && $total > 0) {
                $status = 'paid';
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => $status,
            ]);
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'تم إنشاء الفاتورة بنجاح / Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['patient', 'items']);

        return view('invoices.show', [
            'invoice' => $invoice,
            'paymentMethods' => config('hospital.payment_methods', []),
        ]);
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح / Invoice deleted successfully.');
    }

    private function generateInvoiceNumber(): string
    {
        $prefix = 'INV-';
        $datePart = now()->format('Ymd');
        $count = Invoice::query()->count() + 1;

        return $prefix . $datePart . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}
