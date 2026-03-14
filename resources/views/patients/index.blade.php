@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.patients') }}</h1>
            <p class="text-muted mb-0">{{ __('app.patients_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                {{ __('app.add_patient') }}
            </a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('patients.index') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="{{ __('app.search_patients') }}"
                        value="{{ $search }}"
                    >
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100">{{ __('app.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.phone') }}</th>
                        <th>{{ __('app.identity_number') }}</th>
                        <th>{{ __('app.address') }}</th>
                        <th>{{ __('app.notes') }}</th>
                        <th>{{ __('app.created_by') }}</th>
                        <th style="width: 180px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->phone ?: '-' }}</td>
                            <td>{{ $patient->identity_number ?: '-' }}</td>
                            <td>{{ $patient->address ?: '-' }}</td>
                            <td>{{ $patient->notes ?: '-' }}</td>
                            <td>{{ $patient->creator?->name ?: '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('patients.edit', $patient) }}" class="btn btn-sm btn-warning">
                                        {{ __('app.edit') }}
                                    </a>

                                    <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            {{ __('app.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $patients->links() }}
        </div>
    </div>
@endsection