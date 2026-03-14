@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.edit_invoice') }}</h1>
            <p class="text-muted mb-0">{{ __('app.edit_invoice_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.invoice_number') }}</label>
                    <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.invoice_date') }}</label>
                    <input type="date" name="invoice_date" class="form-control" value="{{ old('invoice_date', $invoice->invoice_date?->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.status') }}</label>
                    <select name="status" class="form-select" required>
                        <option value="unpaid" @selected(old('status', $invoice->status) === 'unpaid')>{{ __('app.unpaid') }}</option>
                        <option value="partially_paid" @selected(old('status', $invoice->status) === 'partially_paid')>{{ __('app.partially_paid') }}</option>
                        <option value="paid" @selected(old('status', $invoice->status) === 'paid')>{{ __('app.paid') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.patient') }}</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id', $invoice->patient_id) == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.doctor') }}</label>
                    <select name="doctor_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('doctor_id', $invoice->doctor_id) == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.procedure') }}</label>
                    <select name="procedure_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($procedures as $procedure)
                            <option value="{{ $procedure->id }}" @selected(old('procedure_id', $invoice->procedure_id) == $procedure->id)>{{ $procedure->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label">{{ __('app.service_name') }}</label>
                    <input type="text" name="service_name" class="form-control" value="{{ old('service_name', $invoice->service_name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.amount') }}</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $invoice->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-warning text-dark">{{ __('app.update') }}</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection