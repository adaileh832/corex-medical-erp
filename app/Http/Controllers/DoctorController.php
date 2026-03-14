<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(): View
    {
        $doctors = Doctor::query()
            ->latest()
            ->paginate(10);

        return view('doctors.index', [
            'doctors' => $doctors,
        ]);
    }

    public function create(): View
    {
        return view('doctors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'digits:10'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'full_name.required' => 'اسم الطبيب مطلوب / Doctor name is required.',
            'national_id.required' => 'الرقم الوطني مطلوب / National ID is required.',
            'national_id.digits' => 'الرقم الوطني يجب أن يكون 10 أرقام / National ID must be exactly 10 digits.',
        ]);

        $data = $this->buildDoctorData($validated);

        Doctor::create($data);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'تم إنشاء الطبيب بنجاح / Doctor created successfully.');
    }

    public function show(Doctor $doctor): View
    {
        return view('doctors.show', [
            'doctor' => $doctor,
        ]);
    }

    public function edit(Doctor $doctor): View
    {
        return view('doctors.edit', [
            'doctor' => $doctor,
        ]);
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'digits:10'],
            'specialty' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ], [
            'full_name.required' => 'اسم الطبيب مطلوب / Doctor name is required.',
            'national_id.required' => 'الرقم الوطني مطلوب / National ID is required.',
            'national_id.digits' => 'الرقم الوطني يجب أن يكون 10 أرقام / National ID must be exactly 10 digits.',
        ]);

        $data = $this->buildDoctorData($validated);

        $doctor->update($data);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'تم تحديث بيانات الطبيب بنجاح / Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', 'تم حذف الطبيب بنجاح / Doctor deleted successfully.');
    }

    private function buildDoctorData(array $validated): array
    {
        $data = [
            'full_name' => $validated['full_name'],
            'national_id' => $validated['national_id'],
            'specialty' => $validated['specialty'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'license_number' => $validated['license_number'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        if (Schema::hasColumn('doctors', 'name')) {
            $data['name'] = $validated['full_name'];
        }

        if (Schema::hasColumn('doctors', 'doctor_type')) {
            $data['doctor_type'] = 'doctor';
        }

        return $data;
    }
}
