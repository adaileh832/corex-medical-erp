@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.create_payroll') }}</h1>
        <p class="text-muted mb-0">{{ __('app.create_payroll_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('payrolls.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.payroll_number') }}</label>
                    <input type="text" name="payroll_number" class="form-control" value="{{ old('payroll_number', $nextPayrollNumber) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.period_year') }}</label>
                    <input type="number" name="period_year" class="form-control" value="{{ old('period_year', now()->year) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.period_month') }}</label>
                    <input type="number" min="1" max="12" name="period_month" class="form-control" value="{{ old('period_month', now()->month) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.period_start') }}</label>
                    <input type="date" name="period_start" class="form-control" value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.period_end') }}</label>
                    <input type="date" name="period_end" class="form-control" value="{{ old('period_end', now()->endOfMonth()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection