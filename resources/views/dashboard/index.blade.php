@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.dashboard') }}</h1>
            <p class="text-muted mb-0">{{ __('app.dashboard_welcome') }}, {{ auth()->user()->name }}</p>
        </div>
        <div class="mt-3 mt-md-0">
            <span class="badge bg-dark fs-6">
                {{ __('app.current_role') }}:
                {{ auth()->user()->role?->slug === 'manager' ? __('app.manager') : __('app.reception') }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <h5>{{ __('app.total_users') }}</h5>
                <h2>{{ $usersCount }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card p-4">
                <h5>{{ __('app.total_roles') }}</h5>
                <h2>{{ $rolesCount }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card p-4">
                <h5>{{ __('app.total_permissions') }}</h5>
                <h2>{{ $permissionsCount }}</h2>
            </div>
        </div>
    </div>

    <div class="card content-card mt-4 p-4">
        <h4 class="mb-3">{{ __('app.phase_one_ready') }}</h4>
        <p class="mb-2">{{ __('app.phase_one_line_1') }}</p>
        <p class="mb-2">{{ __('app.phase_one_line_2') }}</p>
        <p class="mb-2">{{ __('app.phase_one_line_3') }}</p>
        <p class="mb-0">{{ __('app.phase_one_line_4') }}</p>
    </div>
@endsection