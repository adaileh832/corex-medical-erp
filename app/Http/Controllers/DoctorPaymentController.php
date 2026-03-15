<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorPayment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DoctorPaymentController extends Controller
{
    public function create(Doctor $doctor): View
    {
        return view('doctor-payments.create', [
            'doctor' => $doctor,
            'paymentMethods' => config('hospital.payment_methods', []),
        ]);
    }

    public function store(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['required', 'in:cash,cliq'],
            'payment_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ], [
            'amount.required' => 'مبلغ الدفعة مطلوب / Payment amount is required.',
            'payment_method.required' => 'طريقة الدفع مطلوبة / Payment method is required.',
            'payment_date.required' => 'تاريخ الدفع مطلوب / Payment date is required.',
        ]);

        $data = [];

        if (Schema::hasColumn('doctor_payments', 'doctor_id')) {
            $data['doctor_id'] = $doctor->id;
        }

        if (Schema::hasColumn('doctor_payments', 'payment_number')) {
            $data['payment_number'] = $this->generatePaymentNumber();
        }

        if (Schema::hasColumn('doctor_payments', 'amount')) {
            $data['amount'] = $validated['amount'];
        }

        if (Schema::hasColumn('doctor_payments', 'payment_method')) {
            $data['payment_method'] = $validated['payment_method'];
        }

        if (Schema::hasColumn('doctor_payments', 'payment_date')) {
            $data['payment_date'] = $validated['payment_date'];
        }

        if (Schema::hasColumn('doctor_payments', 'notes')) {
            $data['notes'] = $validated['notes'] ?? null;
        }

        DoctorPayment::create($data);

        return redirect()
            ->route('doctor-statements.show', $doctor)
            ->with('success', 'تمت إضافة دفعة للطبيب بنجاح / Doctor payment added successfully.');
    }

    public function destroy(DoctorPayment $doctorPayment): RedirectResponse
    {
        $doctorId = $doctorPayment->doctor_id;
        $doctorPayment->delete();

        return redirect()
            ->route('doctor-statements.show', $doctorId)
            ->with('success', 'تم حذف دفعة الطبيب بنجاح / Doctor payment deleted successfully.');
    }

    private function generatePaymentNumber(): string
    {
        $prefix = 'DP-';
        $datePart = now()->format('Ymd');
        $count = DoctorPayment::query()->count() + 1;

        return $prefix . $datePart . '-' . str_pad((string) $count, 4, '0', STR_PAD_LEFT);
    }
}