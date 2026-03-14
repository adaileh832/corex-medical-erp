<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class AttendanceReportController extends Controller
{
    public function monthly(Request $request): View
    {
        $employeeId = $request->get('employee_id');
        $year = (int) ($request->get('year') ?: now()->year);
        $month = (int) ($request->get('month') ?: now()->month);

        $employees = Employee::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $employee = null;
        $records = collect();
        $summary = [
            'present_days' => 0,
            'absent_days' => 0,
            'leave_days' => 0,
        ];

        if ($employeeId) {
            $employee = Employee::query()->findOrFail($employeeId);

            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = Carbon::create($year, $month, 1)->endOfMonth();

            $records = AttendanceRecord::query()
                ->where('employee_id', $employeeId)
                ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('attendance_date')
                ->get();

            $summary = [
                'present_days' => $records->where('status', 'present')->count(),
                'absent_days' => $records->where('status', 'absent')->count(),
                'leave_days' => $records->where('status', 'leave')->count(),
            ];
        }

        return view('attendance-reports.monthly', compact('employees', 'employee', 'records', 'summary', 'employeeId', 'year', 'month'));
    }

    public function summary(Request $request): View
    {
        $year = (int) ($request->get('year') ?: now()->year);
        $month = (int) ($request->get('month') ?: now()->month);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();

        $employees = Employee::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $rows = $employees->map(function (Employee $employee) use ($start, $end) {
            $records = AttendanceRecord::query()
                ->where('employee_id', $employee->id)
                ->whereBetween('attendance_date', [$start->toDateString(), $end->toDateString()])
                ->get();

            return [
                'employee' => $employee,
                'present_days' => $records->where('status', 'present')->count(),
                'absent_days' => $records->where('status', 'absent')->count(),
                'leave_days' => $records->where('status', 'leave')->count(),
            ];
        });

        return view('attendance-reports.summary', compact('rows', 'year', 'month'));
    }
}