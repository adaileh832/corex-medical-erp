@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.edit_supplier_payment') }}</h1>
        <p class="text-muted mb-0">{{ __('app.edit_supplier_payment_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('supplier-payments.update', $supplierPayment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.payment_number') }}</label>
                    <input type="text" name="payment_number" class="form-control" value="{{ old('payment_number', $supplierPayment->payment_number) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.payment_date') }}</label>
                    <input type="date" name="payment_date" class="form-control" value="{{ old('payment_date', $supplierPayment->payment_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.amount') }}</label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount', $supplierPayment->amount) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.supplier') }}</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id', $supplierPayment->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.payment_method') }}</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="{{ __('app.cash') }}" @selected(old('payment_method', $supplierPayment->payment_method) === __('app.cash'))>{{ __('app.cash') }}</option>
                        <option value="{{ __('app.bank_transfer') }}" @selected(old('payment_method', $supplierPayment->payment_method) === __('app.bank_transfer'))>{{ __('app.bank_transfer') }}</option>
                        <option value="{{ __('app.card') }}" @selected(old('payment_method', $supplierPayment->payment_method) === __('app.card'))>{{ __('app.card') }}</option>
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $supplierPayment->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success">{{ __('app.update') }}</button>
                <a href="{{ route('supplier-payments.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection