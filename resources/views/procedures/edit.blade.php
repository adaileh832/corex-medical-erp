@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.edit_procedure') }}</h1>
            <p class="text-muted mb-0">{{ __('app.edit_procedure_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('procedures.update', $procedure) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $procedure->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $procedure->name_en) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label">{{ __('app.description') }}</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $procedure->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.price') }}</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price', $procedure->price) }}" required>
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $procedure->is_active))>
                        <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.gynecologist_fee') }}</label>
                    <input type="number" step="0.01" min="0" name="gynecologist_fee" class="form-control" value="{{ old('gynecologist_fee', $procedure->gynecologist_fee) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.anesthetist_fee') }}</label>
                    <input type="number" step="0.01" min="0" name="anesthetist_fee" class="form-control" value="{{ old('anesthetist_fee', $procedure->anesthetist_fee) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.pediatrician_fee') }}</label>
                    <input type="number" step="0.01" min="0" name="pediatrician_fee" class="form-control" value="{{ old('pediatrician_fee', $procedure->pediatrician_fee) }}" required>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-info text-white">{{ __('app.update') }}</button>
                <a href="{{ route('procedures.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection