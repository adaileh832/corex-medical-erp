@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_leave_request') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_leave_request_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('leave-requests.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.employee') }}</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.leave_type') }}</label>
                    <input type="text" name="leave_type" class="form-control" value="{{ old('leave_type') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.status') }}</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" @selected(old('status', 'pending') === 'pending')>{{ __('app.pending') }}</option>
                        <option value="approved" @selected(old('status') === 'approved')>{{ __('app.approved') }}</option>
                        <option value="rejected" @selected(old('status') === 'rejected')>{{ __('app.rejected') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.start_date') }}</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.end_date') }}</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.reason') }}</label>
                    <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-dark">{{ __('app.save') }}</button>
                <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection