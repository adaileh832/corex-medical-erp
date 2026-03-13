<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $patients = Patient::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('identity_number', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('patients.index', compact('patients', 'search'));
    }

    public function create(): View
    {
        return view('patients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = auth()->id();

        Patient::create($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', __('app.patient_created'));
    }

    public function edit(Patient $patient): View
    {
        $this->authorizePatientAccess($patient);

        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $this->authorizePatientAccess($patient);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
        ]);

        $patient->update($validated);

        return redirect()
            ->route('patients.index')
            ->with('success', __('app.patient_updated'));
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $this->authorizePatientAccess($patient);

        $patient->delete();

        return redirect()
            ->route('patients.index')
            ->with('success', __('app.patient_deleted'));
    }

    protected function authorizePatientAccess(Patient $patient): void
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, __('app.unauthorized'));
        }

        if ($user->hasRole('manager')) {
            return;
        }

        if ($user->hasRole('reception') && (int) $patient->created_by === (int) $user->id) {
            return;
        }

        abort(403, __('app.unauthorized'));
    }
}