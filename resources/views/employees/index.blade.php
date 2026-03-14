@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.employees') }}</h1>
            <p class="text-muted mb-0">{{ __('app.employees_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="{{ route('employees.create') }}" class="btn btn-dark">{{ __('app.add_employee') }}</a>
            <a href="{{ route('attendance.index') }}" class="btn btn-outline-dark">{{ __('app.attendance') }}</a>
            <a href="{{ route('payrolls.index') }}" class="btn btn-outline-primary">{{ __('app.payrolls') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="row g-3">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_employees') }}" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select name="employment_type" class="form-select">
                        <option value="">{{ __('app.all_employment_types') }}</option>
                        <option value="monthly" @selected($employmentType === 'monthly')>{{ __('app.monthly_employee') }}</option>
                        <option value="daily" @selected($employmentType === 'daily')>{{ __('app.daily_employee') }}</option>
                    </select>
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
                        <th>{{ __('app.employee_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.job_title') }}</th>
                        <th>{{ __('app.employment_type') }}</th>
                        <th>{{ __('app.monthly_salary') }}</th>
                        <th>{{ __('app.daily_wage') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 200px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->job_title }}</td>
                            <td>
                                {{ $employee->employment_type === 'monthly' ? __('app.monthly_employee') : __('app.daily_employee') }}
                            </td>
                            <td>{{ number_format((float) $employee->monthly_salary, 2) }}</td>
                            <td>{{ number_format((float) $employee->daily_wage, 2) }}</td>
                            <td>
                                @if($employee->is_active)
                                    <span class="badge bg-success">{{ __('app.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>
@endsection