@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.stock_summary_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.stock_summary_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('inventory-reports.stock-summary') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_inventory_items') }}" value="{{ $search }}">
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
                        <th>{{ __('app.item_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.category') }}</th>
                        <th>{{ __('app.item_type') }}</th>
                        <th>{{ __('app.unit') }}</th>
                        <th>{{ __('app.current_quantity') }}</th>
                        <th>{{ __('app.minimum_quantity') }}</th>
                        <th>{{ __('app.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category ?: '-' }}</td>
                            <td>{{ $item->item_type }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format((float) $item->current_quantity, 2) }}</td>
                            <td>{{ number_format((float) $item->minimum_quantity, 2) }}</td>
                            <td>
                                @if((float) $item->current_quantity <= 0)
                                    <span class="badge bg-danger">{{ __('app.out_of_stock') }}</span>
                                @elseif((float) $item->current_quantity <= (float) $item->minimum_quantity)
                                    <span class="badge bg-warning text-dark">{{ __('app.low_stock') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('app.available') }}</span>
                                @endif
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
            {{ $items->links() }}
        </div>
    </div>
@endsection