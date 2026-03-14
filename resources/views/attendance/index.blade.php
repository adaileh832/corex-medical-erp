@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.attendance') }}</h1>
            <p class="text-muted mb-0">{{ __('app.attendance_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('attendance.create') }}" class="btn btn-dark">{{ __('app.add_attendance') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="employee_id" class="form-select">
                        <option value="">{{ __('app.all_employees') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected((string) $employeeId === (string) $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">{{ __('app.all_statuses') }}</option>
                        <option value="present" @selected($status === 'present')>{{ __('app.present') }}</option>
                        <option value="absent" @selected($status === 'absent')>{{ __('app.absent') }}</option>
                        <option value="leave" @selected($status === 'leave')>{{ __('app.leave') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-1">
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
                        <th>{{ __('app.attendance_date') }}</th>
                        <th>{{ __('app.employee') }}</th>
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
                            <td>{{ $record->employee?->name ?? '-' }}</td>
                            <td>
                                @if($record->status === 'present')
                                    <span class="badge bg-success">{{ __('app.present') }}</span>
                                @elseif($record->status === 'absent')
                                    <span class="badge bg-danger">{{ __('app.absent') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('app.leave') }}</span>
                                @endif
                            </td>
                            <td>{{ $record->check_in ?: '-' }}</td>
                            <td>{{ $record->check_out ?: '-' }}</td>
                            <td>{{ $record->notes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $records->links() }}
        </div>
    </div>
@endsection