@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_attendance') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_attendance_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('attendance.store') }}" method="POST">
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
                    <label class="form-label">{{ __('app.attendance_date') }}</label>
                    <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.status') }}</label>
                    <select name="status" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="present" @selected(old('status') === 'present')>{{ __('app.present') }}</option>
                        <option value="absent" @selected(old('status') === 'absent')>{{ __('app.absent') }}</option>
                        <option value="leave" @selected(old('status') === 'leave')>{{ __('app.leave') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.check_in') }}</label>
                    <input type="time" name="check_in" class="form-control" value="{{ old('check_in') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.check_out') }}</label>
                    <input type="time" name="check_out" class="form-control" value="{{ old('check_out') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-dark">{{ __('app.save') }}</button>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection