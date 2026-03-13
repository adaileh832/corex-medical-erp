@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.add_operation') }}</h1>
            <p class="text-muted mb-0">{{ __('app.add_operation_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('operations.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.operation_date') }}</label>
                    <input type="date" name="operation_date" class="form-control" value="{{ old('operation_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.patient') }}</label>
                    <select name="patient_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.procedure') }}</label>
                    <select name="procedure_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($procedures as $procedure)
                            <option value="{{ $procedure->id }}" @selected(old('procedure_id') == $procedure->id)>{{ $procedure->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.invoice_number') }}</label>
                    <select name="invoice_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($invoices as $invoice)
                            <option value="{{ $invoice->id }}" @selected(old('invoice_id') == $invoice->id)>{{ $invoice->invoice_number }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.gynecologist') }}</label>
                    <select name="gynecologist_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($gynecologists as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('gynecologist_id') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.anesthetist') }}</label>
                    <select name="anesthetist_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($anesthetists as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('anesthetist_id') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.pediatrician') }}</label>
                    <select name="pediatrician_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($pediatricians as $doctor)
                            <option value="{{ $doctor->id }}" @selected(old('pediatrician_id') == $doctor->id)>{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-secondary">{{ __('app.save') }}</button>
                <a href="{{ route('operations.index') }}" class="btn btn-outline-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection