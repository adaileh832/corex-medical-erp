@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.settings') }}</h1>
            <p class="text-muted mb-0">{{ __('app.settings_description') }}</p>
        </div>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('app.hospital_name_ar') }}</label>
                    <input
                        type="text"
                        name="hospital_name"
                        class="form-control"
                        value="{{ old('hospital_name', $settings['hospital_name'] ?? '') }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.hospital_name_en') }}</label>
                    <input
                        type="text"
                        name="hospital_name_en"
                        class="form-control"
                        value="{{ old('hospital_name_en', $settings['hospital_name_en'] ?? '') }}"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.phone') }}</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $settings['phone'] ?? '') }}"
                    >
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.address') }}</label>
                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        value="{{ old('address', $settings['address'] ?? '') }}"
                    >
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.logo') }}</label>
                    <input type="file" name="logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.svg">

                    @if(!empty($settings['logo']))
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" style="max-height: 90px;">
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary">{{ __('app.save') }}</button>
            </div>
        </form>
    </div>
@endsection