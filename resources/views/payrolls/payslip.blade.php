@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.payslip') }}</h1>
            <p class="text-muted mb-0">{{ $payrollItem->employee?->name ?? '-' }}</p>
        </div>

        <a href="{{ route('payrolls.show', $payrollItem->payroll_id) }}" class="btn btn-secondary">{{ __('app.back') }}</a>
    </div>

    <div class="card content-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>{{ __('app.payroll_number') }}</strong>
                <div>{{ $payrollItem->payroll?->payroll_number ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.employee') }}</strong>
                <div>{{ $payrollItem->employee?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.employment_type') }}</strong>
                <div>{{ $payrollItem->employment_type === 'monthly' ? __('app.monthly_employee') : __('app.daily_employee') }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.present_days') }}</strong>
                <div>{{ $payrollItem->present_days }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.absent_days') }}</strong>
                <div>{{ $payrollItem->absent_days }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.leave_days') }}</strong>
                <div>{{ $payrollItem->leave_days }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.worked_days') }}</strong>
                <div>{{ $payrollItem->worked_days }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card p-4">
        <table class="table table-bordered align-middle">
            <tbody>
                <tr>
                    <th>{{ __('app.base_salary') }}</th>
                    <td>{{ number_format((float) $payrollItem->base_salary, 2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('app.daily_wage') }}</th>
                    <td>{{ number_format((float) $payrollItem->daily_wage, 2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('app.gross_amount') }}</th>
                    <td>{{ number_format((float) $payrollItem->gross_amount, 2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('app.deductions') }}</th>
                    <td>{{ number_format((float) $payrollItem->deductions, 2) }}</td>
                </tr>
                <tr>
                    <th>{{ __('app.net_amount') }}</th>
                    <td><strong>{{ number_format((float) $payrollItem->net_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection