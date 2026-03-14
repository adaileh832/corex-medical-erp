<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $employeeId = $request->get('employee_id');
        $status = trim((string) $request->get('status'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $leaveRequests = LeaveRequest::query()
            ->with(['employee', 'creator'])
            ->when($employeeId, fn ($query) => $query->where('employee_id', $employeeId))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($dateFrom, fn ($query) => $query->whereDate('start_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('end_date', '<=', $dateTo))
            ->latest('start_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $employees = Employee::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('leave-requests.index', compact('leaveRequests', 'employees', 'employeeId', 'status', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('leave-requests.create', [
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
            'leave_type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        LeaveRequest::create([
            'employee_id' => $validated['employee_id'],
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $startDate->diffInDays($endDate) + 1,
            'status' => $validated['status'],
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', __('app.leave_request_created'));
    }

    public function edit(LeaveRequest $leaveRequest): View
    {
        return view('leave-requests.edit', [
            'leaveRequest' => $leaveRequest,
            'employees' => Employee::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'leave_type' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:pending,approved,rejected'],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);

        $leaveRequest->update([
            'employee_id' => $validated['employee_id'],
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $startDate->diffInDays($endDate) + 1,
            'status' => $validated['status'],
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', __('app.leave_request_updated'));
    }

    public function destroy(LeaveRequest $leaveRequest): RedirectResponse
    {
        $leaveRequest->delete();

        return redirect()
            ->route('leave-requests.index')
            ->with('success', __('app.leave_request_deleted'));
    }
}