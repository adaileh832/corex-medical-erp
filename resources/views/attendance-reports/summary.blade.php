@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.attendance_summary_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.attendance_summary_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('attendance-reports.summary') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="number" name="year" class="form-control" value="{{ $year }}" required>
                </div>
                <div class="col-md-5">
                    <input type="number" min="1" max="12" name="month" class="form-control" value="{{ $month }}" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100">{{ __('app.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.employee') }}</th>
                        <th>{{ __('app.present_days') }}</th>
                        <th>{{ __('app.absent_days') }}</th>
                        <th>{{ __('app.leave_days') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['employee']->name }}</td>
                            <td>{{ $row['present_days'] }}</td>
                            <td>{{ $row['absent_days'] }}</td>
                            <td>{{ $row['leave_days'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection