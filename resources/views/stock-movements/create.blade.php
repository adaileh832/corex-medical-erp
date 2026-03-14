@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_stock_movement') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_stock_movement_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('stock-movements.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.movement_date') }}</label>
                    <input type="date" name="movement_date" class="form-control" value="{{ old('movement_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.inventory_item') }}</label>
                    <select name="inventory_item_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" @selected(old('inventory_item_id') == $item->id)>
                                {{ $item->item_code }} - {{ $item->name }} ({{ number_format((float) $item->current_quantity, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.movement_type') }}</label>
                    <select name="movement_type" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="in" @selected(old('movement_type') === 'in')>{{ __('app.stock_in') }}</option>
                        <option value="out" @selected(old('movement_type') === 'out')>{{ __('app.stock_out') }}</option>
                        <option value="adjustment" @selected(old('movement_type') === 'adjustment')>{{ __('app.stock_adjustment') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.quantity') }}</label>
                    <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.reference_type') }}</label>
                    <input type="text" name="reference_type" class="form-control" value="{{ old('reference_type') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.reference_number') }}</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}">
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.reason') }}</label>
                    <input type="text" name="reason" class="form-control" value="{{ old('reason') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success">{{ __('app.save') }}</button>
                <a href="{{ route('stock-movements.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection