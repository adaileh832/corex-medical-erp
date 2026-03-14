<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        $patients = Patient::query()
            ->latest()
            ->paginate(10);

        return view('patients.index', [
            'patients' => $patients,
        ]);
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string'],
        ], [
            'full_name.required' => 'اسم المريض مطلوب / Patient name is required.',
        ]);

        $data = $this->buildPatientData($validated);

        Patient::create($data);

        return redirect()
            ->route('patients.index')
            ->with('success', 'تم إنشاء المريض بنجاح / Patient created successfully.');
    }

    public function show(Patient $patient): View
    {
        return view('patients.show', [
            'patient' => $patient,
        ]);
    }

    public function edit(Patient $patient): View
    {
        return view('patients.edit', [
            'patient' => $patient,
        ]);
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'gender' => ['nullable', 'in:male,female'],
            'date_of_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string'],
        ], [
            'full_name.required' => 'اسم المريض مطلوب / Patient name is required.',
        ]);

        $data = $this->buildPatientData($validated);

        $patient->update($data);

        return redirect()
            ->route('patients.index')
            ->with('success', 'تم تحديث بيانات المريض بنجاح / Patient updated successfully.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', 'تم حذف المريض بنجاح / Patient deleted successfully.');
    }

    private function buildPatientData(array $validated): array
    {
        $data = [
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        if (Schema::hasColumn('patients', 'name')) {
            $data['name'] = $validated['full_name'];
        }

        return $data;
    }
}