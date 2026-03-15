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
        $patients = Patient::query()
            ->orderBy('full_name')
            ->get();

        $procedures = Procedure::query()
            ->orderBy('name')
            ->get();

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
            'payment_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
        ], [
            'patient_id.required' => 'المريض مطلوب / Patient is required.',
            'payment_method.required' => 'طريقة الدفع مطلوبة / Payment method is required.',
        ]);

        $rawItems = $request->input('items', []);

        $filteredItems = collect($rawItems)
            ->filter(function ($item) {
                return !empty($item['procedure_id']);
            })
            ->values()
            ->all();

        if (count($filteredItems) === 0) {
            return back()
                ->withErrors([
                    'items' => 'يجب إضافة إجراء واحد على الأقل / At least one procedure is required.',
                ])
                ->withInput();
        }

        foreach ($filteredItems as $item) {
            $validator = validator($item, [
                'procedure_id' => ['required', 'exists:procedures,id'],
                'quantity' => ['required', 'integer', 'min:1'],
            ], [
                'procedure_id.required' => 'الإجراء مطلوب / Procedure is required.',
                'quantity.required' => 'الكمية مطلوبة / Quantity is required.',
                'quantity.min' => 'الكمية يجب أن تكون 1 أو أكثر / Quantity must be at least 1.',
            ]);

            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::transaction(function () use ($validated, $filteredItems) {
            $discount = (float) ($validated['discount'] ?? 0);
            $paidAmount = (float) ($validated['paid_amount'] ?? 0);

            $procedureNames = [];
            $subtotal = 0;
            $preparedItems = [];

            foreach ($filteredItems as $item) {
                $procedure = Procedure::findOrFail($item['procedure_id']);
                $quantity = (int) $item['quantity'];
                $price = (float) $procedure->price;
                $lineTotal = $price * $quantity;

                $procedureNames[] = $procedure->display_name;

                $preparedItems[] = [
                    'procedure_id' => $procedure->id,
                    'procedure_name' => $procedure->display_name,
                    'price' => $price,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];

                $subtotal += $lineTotal;
            }

            $total = max($subtotal - $discount, 0);

            $status = 'unpaid';
            if ($paidAmount > 0 && $paidAmount < $total) {
                $status = 'partial';
            } elseif ($paidAmount >= $total && $total > 0) {
                $status = 'paid';
            }

            $invoiceData = [];

            if (Schema::hasColumn('invoices', 'invoice_number')) {
                $invoiceData['invoice_number'] = $this->generateInvoiceNumber();
            }

            if (Schema::hasColumn('invoices', 'patient_id')) {
                $invoiceData['patient_id'] = $validated['patient_id'];
            }

            if (Schema::hasColumn('invoices', 'invoice_date')) {
                $invoiceData['invoice_date'] = now();
            }

            if (Schema::hasColumn('invoices', 'subtotal')) {
                $invoiceData['subtotal'] = $subtotal;
            }

            if (Schema::hasColumn('invoices', 'discount')) {
                $invoiceData['discount'] = $discount;
            }

            if (Schema::hasColumn('invoices', 'tax')) {
                $invoiceData['tax'] = 0;
            }

            if (Schema::hasColumn('invoices', 'total')) {
                $invoiceData['total'] = $total;
            }

            if (Schema::hasColumn('invoices', 'paid_amount')) {
                $invoiceData['paid_amount'] = $paidAmount;
            }

            if (Schema::hasColumn('invoices', 'status')) {
                $invoiceData['status'] = $status;
            }

            if (Schema::hasColumn('invoices', 'notes')) {
                $invoiceData['notes'] = $validated['notes'] ?? null;
            }

            if (Schema::hasColumn('invoices', 'payment_method')) {
                $invoiceData['payment_method'] = $validated['payment_method'];
            }

            if (Schema::hasColumn('invoices', 'payment_date')) {
                $invoiceData['payment_date'] = !empty($validated['payment_date'])
                    ? $validated['payment_date']
                    : ($paidAmount > 0 ? now() : null);
            }

            if (Schema::hasColumn('invoices', 'currency_code')) {
                $invoiceData['currency_code'] = config('hospital.currency_code', 'JOD');
            }

            if (Schema::hasColumn('invoices', 'service_name')) {
                $invoiceData['service_name'] = implode(' + ', $procedureNames);
            }

            if (Schema::hasColumn('invoices', 'name')) {
                $invoiceData['name'] = implode(' + ', $procedureNames);
            }

            $invoice = Invoice::create($invoiceData);

            foreach ($preparedItems as $preparedItem) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'procedure_id' => $preparedItem['procedure_id'],
                    'procedure_name' => $preparedItem['procedure_name'],
                    'price' => $preparedItem['price'],
                    'quantity' => $preparedItem['quantity'],
                    'line_total' => $preparedItem['line_total'],
                ]);
            }
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