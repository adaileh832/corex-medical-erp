<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $employeeId = $request->get('employee_id');
        $status = trim((string) $request->get('status'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $records = AttendanceRecord::query()
            ->with(['employee', 'creator'])
            ->when($employeeId, fn ($query) => $query->where('employee_id', $employeeId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($dateFrom, fn ($query) => $query->whereDate('attendance_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('attendance_date', '<=', $dateTo))
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $employees = Employee::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('attendance.index', compact('records', 'employees', 'employeeId', 'status', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('attendance.create', [
            'employees' => Employee::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'attendance_date' => ['required', 'date'],
            'status' => ['required', 'in:present,absent,leave'],
            'check_in' => ['nullable', 'date_format:H:i'],
            'check_out' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
        ]);

        AttendanceRecord::query()->updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            [
                'status' => $validated['status'],
                'check_in' => $validated['check_in'] ?? null,
                'check_out' => $validated['check_out'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]
        );

        return redirect()
            ->route('attendance.index')
            ->with('success', __('app.attendance_saved'));
    }
}