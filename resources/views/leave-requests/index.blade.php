@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.leave_requests') }}</h1>
            <p class="text-muted mb-0">{{ __('app.leave_requests_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('leave-requests.create') }}" class="btn btn-dark">{{ __('app.add_leave_request') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('leave-requests.index') }}">
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
                        <option value="pending" @selected($status === 'pending')>{{ __('app.pending') }}</option>
                        <option value="approved" @selected($status === 'approved')>{{ __('app.approved') }}</option>
                        <option value="rejected" @selected($status === 'rejected')>{{ __('app.rejected') }}</option>
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
                        <th>{{ __('app.employee') }}</th>
                        <th>{{ __('app.leave_type') }}</th>
                        <th>{{ __('app.start_date') }}</th>
                        <th>{{ __('app.end_date') }}</th>
                        <th>{{ __('app.days_count') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 180px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $item)
                        <tr>
                            <td>{{ $item->employee?->name ?? '-' }}</td>
                            <td>{{ $item->leave_type }}</td>
                            <td>{{ $item->start_date?->format('Y-m-d') }}</td>
                            <td>{{ $item->end_date?->format('Y-m-d') }}</td>
                            <td>{{ $item->days_count }}</td>
                            <td>
                                @if($item->status === 'approved')
                                    <span class="badge bg-success">{{ __('app.approved') }}</span>
                                @elseif($item->status === 'rejected')
                                    <span class="badge bg-danger">{{ __('app.rejected') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">{{ __('app.pending') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('leave-requests.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <form action="{{ route('leave-requests.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $leaveRequests->links() }}
        </div>
    </div>
@endsection