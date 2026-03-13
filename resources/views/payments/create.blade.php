@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.add_payment') }}</h1>
            <p class="text-muted mb-0">{{ __('app.add_payment_description') }}</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card content-card p-4">
                <h5 class="mb-3">{{ __('app.invoice_summary') }}</h5>

                <div class="mb-2"><strong>{{ __('app.invoice_number') }}:</strong> {{ $invoice->invoice_number }}</div>
                <div class="mb-2"><strong>{{ __('app.patient') }}:</strong> {{ $invoice->patient?->name ?? '-' }}</div>
                <div class="mb-2"><strong>{{ __('app.service_name') }}:</strong> {{ $invoice->service_name }}</div>
                <div class="mb-2"><strong>{{ __('app.amount') }}:</strong> {{ number_format((float) $invoice->amount, 2) }}</div>
                <div class="mb-2"><strong>{{ __('app.paid_amount') }}:</strong> {{ number_format((float) $invoice->paid_amount, 2) }}</div>
                <div class="mb-0"><strong>{{ __('app.remaining_amount') }}:</strong> {{ number_format((float) $invoice->remaining_amount, 2) }}</div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card content-card p-4">
                <form action="{{ route('payments.store', $invoice) }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.receipt_number') }}</label>
                            <input type="text" name="receipt_number" class="form-control" value="{{ old('receipt_number', $nextReceiptNumber) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.payment_date') }}</label>
                            <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.amount') }}</label>
                            <input type="number" step="0.01" min="0.01" max="{{ $invoice->remaining_amount }}" name="amount" class="form-control" value="{{ old('amount', $invoice->remaining_amount) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('app.payment_method') }}</label>
                            <select name="payment_method" class="form-select" required>
                                <option value="">{{ __('app.select_option') }}</option>
                                <option value="{{ __('app.cash') }}" @selected(old('payment_method') === __('app.cash'))>{{ __('app.cash') }}</option>
                                <option value="{{ __('app.bank_transfer') }}" @selected(old('payment_method') === __('app.bank_transfer'))>{{ __('app.bank_transfer') }}</option>
                                <option value="{{ __('app.card') }}" @selected(old('payment_method') === __('app.card'))>{{ __('app.card') }}</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('app.notes') }}</label>
                            <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button class="btn btn-success">{{ __('app.save') }}</button>
                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">{{ __('app.back') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection