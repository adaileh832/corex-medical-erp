@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.low_stock_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.low_stock_report_description') }}</p>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.item_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.category') }}</th>
                        <th>{{ __('app.current_quantity') }}</th>
                        <th>{{ __('app.minimum_quantity') }}</th>
                        <th>{{ __('app.unit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category ?: '-' }}</td>
                            <td>{{ number_format((float) $item->current_quantity, 2) }}</td>
                            <td>{{ number_format((float) $item->minimum_quantity, 2) }}</td>
                            <td>{{ $item->unit }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('app.no_data') }}</td>
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