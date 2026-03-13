@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.inventory_items') }}</h1>
            <p class="text-muted mb-0">{{ __('app.inventory_items_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="{{ route('inventory-items.create') }}" class="btn btn-success">{{ __('app.add_inventory_item') }}</a>
            <a href="{{ route('stock-movements.index') }}" class="btn btn-outline-success">{{ __('app.stock_movements') }}</a>
            <a href="{{ route('inventory-reports.stock-summary') }}" class="btn btn-outline-dark">{{ __('app.inventory_reports') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('inventory-items.index') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_inventory_items') }}" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">{{ __('app.all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected($category === $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="item_type" class="form-select">
                        <option value="">{{ __('app.all_item_types') }}</option>
                        <option value="{{ __('app.medicine') }}" @selected($itemType === __('app.medicine'))>{{ __('app.medicine') }}</option>
                        <option value="{{ __('app.medical_consumable') }}" @selected($itemType === __('app.medical_consumable'))>{{ __('app.medical_consumable') }}</option>
                        <option value="{{ __('app.general_item') }}" @selected($itemType === __('app.general_item'))>{{ __('app.general_item') }}</option>
                    </select>
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
                        <th>{{ __('app.item_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.category') }}</th>
                        <th>{{ __('app.item_type') }}</th>
                        <th>{{ __('app.unit') }}</th>
                        <th>{{ __('app.current_quantity') }}</th>
                        <th>{{ __('app.minimum_quantity') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 200px;">{{ __('app.actions') }}</th>
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
                            <td>
                                {{ number_format((float) $item->current_quantity, 2) }}
                                @if((float) $item->current_quantity <= (float) $item->minimum_quantity)
                                    <span class="badge bg-danger ms-1">{{ __('app.low_stock') }}</span>
                                @endif
                            </td>
                            <td>{{ number_format((float) $item->minimum_quantity, 2) }}</td>
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">{{ __('app.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('inventory-items.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <form action="{{ route('inventory-items.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('app.no_data') }}</td>
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