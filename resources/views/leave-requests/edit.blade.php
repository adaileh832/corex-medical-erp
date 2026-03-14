@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.edit_leave_request') }}</h1>
        <p class="text-muted mb-0">{{ __('app.edit_leave_request_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('leave-requests.update', $leaveRequest) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.employee') }}</label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id', $leaveRequest->employee_id) == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.leave_type') }}</label>
                    <input type="text" name="leave_type" class="form-control" value="{{ old('leave_type', $leaveRequest->leave_type) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.status') }}</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" @selected(old('status', $leaveRequest->status) === 'pending')>{{ __('app.pending') }}</option>
                        <option value="approved" @selected(old('status', $leaveRequest->status) === 'approved')>{{ __('app.approved') }}</option>
                        <option value="rejected" @selected(old('status', $leaveRequest->status) === 'rejected')>{{ __('app.rejected') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.start_date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $leaveRequest->start_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.end_date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $leaveRequest->end_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.reason') }}</label>
                    <textarea name="reason" class="form-control" rows="3">{{ old('reason', $leaveRequest->reason) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes', $leaveRequest->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-dark">{{ __('app.update') }}</button>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection