@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_doctor_payment') }}</h1>
        <p class="text-muted mb-0">{{ $doctor->name }}</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card content-card p-4">
                <h5 class="mb-3">{{ __('app.doctor_payment_summary') }}</h5>
                <div class="mb-2"><strong>{{ __('app.doctor') }}:</strong> {{ $doctor->name }}</div>
                <div class="mb-0"><strong>{{ __('app.balance') }}:</strong> {{ number_format($balance, 2) }}</div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card content-card p-4">
                <form action="{{ route('doctor-payments.store', $doctor) }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.payment_number') }}</label>
                            <input type="text" name="payment_number" class="form-control" value="{{ old('payment_number', $nextPaymentNumber) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.payment_date') }}</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.amount') }}</label>
                            <input type="number" step="0.01" min="0.01" max="{{ $balance }}" name="amount" class="form-control" value="{{ old('amount', $balance) }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('app.notes') }}</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">{{ __('app.save') }}</button>
                        <a href="{{ route('doctor-statements.index', ['doctor_id' => $doctor->id]) }}" class="btn btn-secondary">{{ __('app.back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection