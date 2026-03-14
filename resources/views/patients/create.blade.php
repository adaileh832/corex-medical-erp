@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.add_patient') }}</h1>
            <p class="text-muted mb-0">{{ __('app.add_patient_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.identity_number') }}</label>
                    <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address') }}</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection