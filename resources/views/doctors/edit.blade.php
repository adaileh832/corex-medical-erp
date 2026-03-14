@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.edit_doctor') }}</h1>
            <p class="text-muted mb-0">{{ __('app.edit_doctor_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('doctors.update', $doctor) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $doctor->name_en) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.doctor_type') }}</label>
                    <select name="doctor_type" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="{{ __('app.gynecologist') }}" @selected(old('doctor_type', $doctor->doctor_type) === __('app.gynecologist'))>{{ __('app.gynecologist') }}</option>
                        <option value="{{ __('app.anesthetist') }}" @selected(old('doctor_type', $doctor->doctor_type) === __('app.anesthetist'))>{{ __('app.anesthetist') }}</option>
                        <option value="{{ __('app.pediatrician') }}" @selected(old('doctor_type', $doctor->doctor_type) === __('app.pediatrician'))>{{ __('app.pediatrician') }}</option>
                        <option value="{{ __('app.other_doctor') }}" @selected(old('doctor_type', $doctor->doctor_type) === __('app.other_doctor'))>{{ __('app.other_doctor') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.specialty') }}</label>
                    <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $doctor->specialty) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.phone') }}</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->phone) }}">
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $doctor->is_active))>
                        <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $doctor->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success">{{ __('app.update') }}</button>
                <a href="{{ route('doctors.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection