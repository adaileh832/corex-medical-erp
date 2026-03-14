<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorPayment;
use App\Models\DoctorTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorPaymentController extends Controller
{
    public function create(Doctor $doctor): View
    {
        $totalTransactions = (float) DoctorTransaction::query()->where('doctor_id', $doctor->id)->sum('amount');
        $totalPayments = (float) DoctorPayment::query()->where('doctor_id', $doctor->id)->sum('amount');
        $balance = max(0, $totalTransactions - $totalPayments);

        return view('doctor-payments.create', [
            'doctor' => $doctor,
            'balance' => $balance,
            'nextPaymentNumber' => $this->generatePaymentNumber(),
        ]);
    }

    public function store(Request $request, Doctor $doctor): RedirectResponse
    {
        $totalTransactions = (float) DoctorTransaction::query()->where('doctor_id', $doctor->id)->sum('amount');
        $totalPayments = (float) DoctorPayment::query()->where('doctor_id', $doctor->id)->sum('amount');
        $balance = max(0, $totalTransactions - $totalPayments);

        $validated = $request->validate([
            'payment_number' => ['required', 'string', 'max:100', 'unique:doctor_payments,payment_number'],
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . max(0.01, $balance)],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['doctor_id'] = $doctor->id;
        $validated['created_by'] = auth()->id();

        DoctorPayment::create($validated);

        return redirect()
            ->route('doctor-statements.index', ['doctor_id' => $doctor->id])
            ->with('success', __('app.doctor_payment_created'));
    }

    public function destroy(DoctorPayment $doctorPayment): RedirectResponse
    {
        $doctorId = $doctorPayment->doctor_id;
        $doctorPayment->delete();

        return redirect()
            ->route('doctor-statements.index', ['doctor_id' => $doctorId])
            ->with('success', __('app.doctor_payment_deleted'));
    }

    protected function generatePaymentNumber(): string
    {
        $lastPayment = DoctorPayment::query()->latest('id')->first();
        $nextId = $lastPayment ? ($lastPayment->id + 1) : 1;

        return 'DOC-PAY-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}