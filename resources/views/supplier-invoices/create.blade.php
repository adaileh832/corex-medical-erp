@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_supplier_invoice') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_supplier_invoice_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('supplier-invoices.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.invoice_number') }}</label>
                    <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $nextInvoiceNumber) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.invoice_date') }}</label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.amount') }}</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', 0) }}" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{ __('app.supplier') }}</label>
                    <select name="supplier_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.description') }}</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('supplier-invoices.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection