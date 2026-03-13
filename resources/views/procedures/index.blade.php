@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.procedures') }}</h1>
            <p class="text-muted mb-0">{{ __('app.procedures_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('procedures.create') }}" class="btn btn-info text-white">
                {{ __('app.add_procedure') }}
            </a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('procedures.index') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="{{ __('app.search_procedures') }}"
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
                        <th>{{ __('app.name_en') }}</th>
                        <th>{{ __('app.price') }}</th>
                        <th>{{ __('app.gynecologist_fee') }}</th>
                        <th>{{ __('app.anesthetist_fee') }}</th>
                        <th>{{ __('app.pediatrician_fee') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 180px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($procedures as $procedure)
                        <tr>
                            <td>{{ $procedure->name }}</td>
                            <td>{{ $procedure->name_en ?: '-' }}</td>
                            <td>{{ number_format((float) $procedure->price, 2) }}</td>
                            <td>{{ number_format((float) $procedure->gynecologist_fee, 2) }}</td>
                            <td>{{ number_format((float) $procedure->anesthetist_fee, 2) }}</td>
                            <td>{{ number_format((float) $procedure->pediatrician_fee, 2) }}</td>
                            <td>
                                @if($procedure->is_active)
                                    <span class="badge bg-success">{{ __('app.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('procedures.edit', $procedure) }}" class="btn btn-sm btn-warning">
                                        {{ __('app.edit') }}
                                    </a>

                                    <form action="{{ route('procedures.destroy', $procedure) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
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
                            <td colspan="8" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $procedures->links() }}
        </div>
    </div>
@endsection