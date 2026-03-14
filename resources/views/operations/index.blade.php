@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.operations') }}</h1>
            <p class="text-muted mb-0">{{ __('app.operations_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('operations.create') }}" class="btn btn-secondary">
                {{ __('app.add_operation') }}
            </a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('operations.index') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_operations') }}" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-1">
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
                        <th>{{ __('app.operation_date') }}</th>
                        <th>{{ __('app.patient') }}</th>
                        <th>{{ __('app.procedure') }}</th>
                        <th>{{ __('app.gynecologist') }}</th>
                        <th>{{ __('app.anesthetist') }}</th>
                        <th>{{ __('app.pediatrician') }}</th>
                        <th>{{ __('app.invoice_number') }}</th>
                        <th style="width: 180px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operations as $operation)
                        <tr>
                            <td>{{ $operation->operation_date?->format('Y-m-d') }}</td>
                            <td>{{ $operation->patient?->name ?? '-' }}</td>
                            <td>{{ $operation->procedure?->name ?? '-' }}</td>
                            <td>{{ $operation->gynecologist?->name ?? '-' }}</td>
                            <td>{{ $operation->anesthetist?->name ?? '-' }}</td>
                            <td>{{ $operation->pediatrician?->name ?? '-' }}</td>
                            <td>{{ $operation->invoice?->invoice_number ?? '-' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('operations.show', $operation) }}" class="btn btn-sm btn-info text-white">{{ __('app.view') }}</a>

                                    <form action="{{ route('operations.destroy', $operation) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
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
            {{ $operations->links() }}
        </div>
    </div>
@endsection