@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.movement_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.movement_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('inventory-reports.movement-report') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-4">
                    <select name="movement_type" class="form-select">
                        <option value="">{{ __('app.all_movement_types') }}</option>
                        <option value="in" @selected($movementType === 'in')>{{ __('app.stock_in') }}</option>
                        <option value="out" @selected($movementType === 'out')>{{ __('app.stock_out') }}</option>
                        <option value="adjustment" @selected($movementType === 'adjustment')>{{ __('app.stock_adjustment') }}</option>
                    </select>
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
                        <th>{{ __('app.movement_date') }}</th>
                        <th>{{ __('app.item_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.movement_type') }}</th>
                        <th>{{ __('app.quantity') }}</th>
                        <th>{{ __('app.balance_after') }}</th>
                        <th>{{ __('app.reason') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->movement_date?->format('Y-m-d') }}</td>
                            <td>{{ $movement->item?->item_code ?? '-' }}</td>
                            <td>{{ $movement->item?->name ?? '-' }}</td>
                            <td>{{ $movement->movement_type }}</td>
                            <td>{{ number_format((float) $movement->quantity, 2) }}</td>
                            <td>{{ number_format((float) $movement->balance_after, 2) }}</td>
                            <td>{{ $movement->reason }}</td>
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
            {{ $movements->links() }}
        </div>
    </div>
@endsection