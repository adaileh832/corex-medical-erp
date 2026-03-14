@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.payroll_details') }}</h1>
            <p class="text-muted mb-0">{{ $payroll->payroll_number }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.total_gross') }}</div>
                <h4 class="mb-0">{{ number_format($totalGross, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.total_deductions') }}</div>
                <h4 class="mb-0">{{ number_format($totalDeductions, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.total_net') }}</div>
                <h4 class="mb-0">{{ number_format($totalNet, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="card content-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <strong>{{ __('app.period_year') }}</strong>
                <div>{{ $payroll->period_year }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.period_month') }}</strong>
                <div>{{ $payroll->period_month }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.period_start') }}</strong>
                <div>{{ $payroll->period_start?->format('Y-m-d') }}</div>
            </div>
            <div class="col-md-3">
                <strong>{{ __('app.period_end') }}</strong>
                <div>{{ $payroll->period_end?->format('Y-m-d') }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.employee') }}</th>
                        <th>{{ __('app.employment_type') }}</th>
                        <th>{{ __('app.present_days') }}</th>
                        <th>{{ __('app.absent_days') }}</th>
                        <th>{{ __('app.leave_days') }}</th>
                        <th>{{ __('app.gross_amount') }}</th>
                        <th>{{ __('app.deductions') }}</th>
                        <th>{{ __('app.net_amount') }}</th>
                        <th>{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payroll->items as $item)
                        <tr>
                            <td>{{ $item->employee?->name ?? '-' }}</td>
                            <td>{{ $item->employment_type === 'monthly' ? __('app.monthly_employee') : __('app.daily_employee') }}</td>
                            <td>{{ $item->present_days }}</td>
                            <td>{{ $item->absent_days }}</td>
                            <td>{{ $item->leave_days }}</td>
                            <td>{{ number_format((float) $item->gross_amount, 2) }}</td>
                            <td>{{ number_format((float) $item->deductions, 2) }}</td>
                            <td>{{ number_format((float) $item->net_amount, 2) }}</td>
                            <td>
                                <a href="{{ route('payrolls.payslip', $item) }}" class="btn btn-sm btn-info text-white">{{ __('app.view') }}</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection