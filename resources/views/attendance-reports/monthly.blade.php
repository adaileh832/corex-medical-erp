@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.monthly_attendance_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.monthly_attendance_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('attendance-reports.monthly') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <select name="employee_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($employees as $item)
                            <option value="{{ $item->id }}" @selected((string) $employeeId === (string) $item->id)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="year" class="form-control" value="{{ $year }}" required>
                </div>
                <div class="col-md-3">
                    <input type="number" min="1" max="12" name="month" class="form-control" value="{{ $month }}" required>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-dark w-100">{{ __('app.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    @if($employee)
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.present_days') }}</div>
                    <h4 class="mb-0">{{ $summary['present_days'] }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.absent_days') }}</div>
                    <h4 class="mb-0">{{ $summary['absent_days'] }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.leave_days') }}</div>
                    <h4 class="mb-0">{{ $summary['leave_days'] }}</h4>
                </div>
            </div>
        </div>

        <div class="card table-card p-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.attendance_date') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.check_in') }}</th>
                            <th>{{ __('app.check_out') }}</th>
                            <th>{{ __('app.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            <tr>
                                <td>{{ $record->attendance_date?->format('Y-m-d') }}</td>
                                <td>{{ __('app.' . $record->status) }}</td>
                                <td>{{ $record->check_in ?: '-' }}</td>
                                <td>{{ $record->check_out ?: '-' }}</td>
                                <td>{{ $record->notes ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('app.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection