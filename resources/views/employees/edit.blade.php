@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.edit_employee') }}</h1>
        <p class="text-muted mb-0">{{ __('app.edit_employee_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.employee_code') }}</label>
                    <input type="text" name="employee_code" class="form-control" value="{{ old('employee_code', $employee->employee_code) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $employee->name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $employee->name_en) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.job_title') }}</label>
                    <input type="text" name="job_title" class="form-control" value="{{ old('job_title', $employee->job_title) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.employment_type') }}</label>
                    <select name="employment_type" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="monthly" @selected(old('employment_type', $employee->employment_type) === 'monthly')>{{ __('app.monthly_employee') }}</option>
                        <option value="daily" @selected(old('employment_type', $employee->employment_type) === 'daily')>{{ __('app.daily_employee') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.hire_date') }}</label>
                    <input type="date" name="hire_date" class="form-control" value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.monthly_salary') }}</label>
                    <input type="number" step="0.01" min="0" name="monthly_salary" class="form-control" value="{{ old('monthly_salary', $employee->monthly_salary) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.daily_wage') }}</label>
                    <input type="number" step="0.01" min="0" name="daily_wage" class="form-control" value="{{ old('daily_wage', $employee->daily_wage) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.identity_number') }}</label>
                    <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number', $employee->identity_number) }}">
                </div>

                <div class="col-md-8">
                    <label class="form-label">{{ __('app.address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $employee->address) }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $employee->is_active))>
                        <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $employee->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-dark">{{ __('app.update') }}</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection