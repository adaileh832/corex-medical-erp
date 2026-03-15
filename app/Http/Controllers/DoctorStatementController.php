<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorLedger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorStatementController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::query()
            ->latest()
            ->get();

        return view('doctor-statements.index', [
            'doctors' => $doctors,
        ]);
    }

    public function show(Doctor $doctor): View
    {
        $doctor->load([
            'ledgers' => fn ($query) => $query->latest('entry_date'),
            'payments' => fn ($query) => $query->latest('payment_date'),
        ]);

        return view('doctor-statements.show', [
            'doctor' => $doctor,
        ]);
    }

    public function storeEntry(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'reference' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'amount_due' => ['required', 'numeric', 'min:0.01'],
            'entry_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ], [
            'description.required' => 'الوصف مطلوب / Description is required.',
            'amount_due.required' => 'المبلغ المستحق مطلوب / Due amount is required.',
            'entry_date.required' => 'تاريخ القيد مطلوب / Entry date is required.',
        ]);

        DoctorLedger::create([
            'doctor_id' => $doctor->id,
            'reference' => $validated['reference'] ?? null,
            'description' => $validated['description'],
            'amount_due' => $validated['amount_due'],
            'entry_date' => $validated['entry_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('doctor-statements.show', $doctor)
            ->with('success', 'تمت إضافة مستحق للطبيب بنجاح / Doctor due entry added successfully.');
    }
}