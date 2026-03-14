<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $employmentType = trim((string) $request->get('employment_type'));

        $employees = Employee::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('employee_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('job_title', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('identity_number', 'like', "%{$search}%");
                });
            })
            ->when($employmentType, fn ($query) => $query->where('employment_type', $employmentType))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('employees.index', compact('employees', 'search', 'employmentType'));
    }

    public function create(): View
    {
        return view('employees.create', [
            'nextEmployeeCode' => $this->generateEmployeeCode(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:100', 'unique:employees,employee_code'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'in:monthly,daily'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'daily_wage' => ['nullable', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:100'],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();
        $validated['monthly_salary'] = $validated['monthly_salary'] ?? 0;
        $validated['daily_wage'] = $validated['daily_wage'] ?? 0;

        Employee::create($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', __('app.employee_created'));
    }

    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => ['required', 'string', 'max:100', 'unique:employees,employee_code,' . $employee->id],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'in:monthly,daily'],
            'monthly_salary' => ['nullable', 'numeric', 'min:0'],
            'daily_wage' => ['nullable', 'numeric', 'min:0'],
            'hire_date' => ['required', 'date'],
            'phone' => ['nullable', 'string', 'max:100'],
            'identity_number' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['monthly_salary'] = $validated['monthly_salary'] ?? 0;
        $validated['daily_wage'] = $validated['daily_wage'] ?? 0;

        $employee->update($validated);

        return redirect()
            ->route('employees.index')
            ->with('success', __('app.employee_updated'));
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        if ($employee->attendanceRecords()->exists() || $employee->payrollItems()->exists()) {
            return redirect()
                ->route('employees.index')
                ->withErrors([
                    'delete' => __('app.employee_has_transactions'),
                ]);
        }

        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', __('app.employee_deleted'));
    }

    protected function generateEmployeeCode(): string
    {
        $last = Employee::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'EMP-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}