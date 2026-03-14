<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\PayrollItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View
    {
        $payrolls = Payroll::query()
            ->withCount('items')
            ->latest('period_year')
            ->latest('period_month')
            ->latest('id')
            ->paginate(12);

        return view('payrolls.index', compact('payrolls'));
    }

    public function create(): View
    {
        return view('payrolls.create', [
            'nextPayrollNumber' => $this->generatePayrollNumber(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payroll_number' => ['required', 'string', 'max:100', 'unique:payrolls,payroll_number'],
            'period_year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'period_month' => ['required', 'integer', 'min:1', 'max:12'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = Payroll::query()
            ->where('period_year', $validated['period_year'])
            ->where('period_month', $validated['period_month'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'period_month' => __('app.payroll_period_exists'),
                ])
                ->withInput();
        }

        DB::transaction(function () use ($validated) {
            $payroll = Payroll::create([
                'payroll_number' => $validated['payroll_number'],
                'period_year' => $validated['period_year'],
                'period_month' => $validated['period_month'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            $employees = Employee::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

            foreach ($employees as $employee) {
                $attendance = AttendanceRecord::query()
                    ->where('employee_id', $employee->id)
                    ->whereBetween('attendance_date', [$validated['period_start'], $validated['period_end']])
                    ->get();

                $presentDays = $attendance->where('status', 'present')->count();
                $absentDays = $attendance->where('status', 'absent')->count();
                $leaveDays = $attendance->where('status', 'leave')->count();
                $workedDays = $presentDays;

                $baseSalary = (float) $employee->monthly_salary;
                $dailyWage = (float) $employee->daily_wage;
                $grossAmount = 0.0;
                $deductions = 0.0;

                if ($employee->employment_type === 'monthly') {
                    $daysInMonth = Carbon::create($validated['period_year'], $validated['period_month'], 1)->daysInMonth;
                    $dailyRate = $daysInMonth > 0 ? $baseSalary / $daysInMonth : 0;
                    $deductions = round($dailyRate * $absentDays, 2);
                    $grossAmount = $baseSalary;
                } else {
                    $grossAmount = round($dailyWage * $workedDays, 2);
                }

                $netAmount = max(0, $grossAmount - $deductions);

                PayrollItem::create([
                    'payroll_id' => $payroll->id,
                    'employee_id' => $employee->id,
                    'employment_type' => $employee->employment_type,
                    'base_salary' => $baseSalary,
                    'daily_wage' => $dailyWage,
                    'present_days' => $presentDays,
                    'absent_days' => $absentDays,
                    'leave_days' => $leaveDays,
                    'worked_days' => $workedDays,
                    'gross_amount' => $grossAmount,
                    'deductions' => $deductions,
                    'net_amount' => $netAmount,
                    'notes' => null,
                ]);
            }
        });

        return redirect()
            ->route('payrolls.index')
            ->with('success', __('app.payroll_created'));
    }

    public function show(Payroll $payroll): View
    {
        $payroll->load(['items.employee', 'creator']);

        $totalGross = (float) $payroll->items->sum('gross_amount');
        $totalDeductions = (float) $payroll->items->sum('deductions');
        $totalNet = (float) $payroll->items->sum('net_amount');

        return view('payrolls.show', compact('payroll', 'totalGross', 'totalDeductions', 'totalNet'));
    }

    public function payslip(PayrollItem $payrollItem): View
    {
        $payrollItem->load(['employee', 'payroll']);

        return view('payrolls.payslip', compact('payrollItem'));
    }

    protected function generatePayrollNumber(): string
    {
        $last = Payroll::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'PAYROLL-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}