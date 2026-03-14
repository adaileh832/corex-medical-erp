@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.edit_inventory_item') }}</h1>
        <p class="text-muted mb-0">{{ __('app.edit_inventory_item_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('inventory-items.update', $inventoryItem) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.item_code') }}</label>
                    <input type="text" name="item_code" class="form-control" value="{{ old('item_code', $inventoryItem->item_code) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $inventoryItem->name) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $inventoryItem->name_en) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.category') }}</label>
                    <input type="text" name="category" class="form-control" value="{{ old('category', $inventoryItem->category) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.item_type') }}</label>
                    <select name="item_type" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="{{ __('app.medicine') }}" @selected(old('item_type', $inventoryItem->item_type) === __('app.medicine'))>{{ __('app.medicine') }}</option>
                        <option value="{{ __('app.medical_consumable') }}" @selected(old('item_type', $inventoryItem->item_type) === __('app.medical_consumable'))>{{ __('app.medical_consumable') }}</option>
                        <option value="{{ __('app.general_item') }}" @selected(old('item_type', $inventoryItem->item_type) === __('app.general_item'))>{{ __('app.general_item') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.unit') }}</label>
                    <input type="text" name="unit" class="form-control" value="{{ old('unit', $inventoryItem->unit) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.current_quantity') }}</label>
                    <input type="number" step="0.01" min="0" name="current_quantity" class="form-control" value="{{ old('current_quantity', $inventoryItem->current_quantity) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.minimum_quantity') }}</label>
                    <input type="number" step="0.01" min="0" name="minimum_quantity" class="form-control" value="{{ old('minimum_quantity', $inventoryItem->minimum_quantity) }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.cost_price') }}</label>
                    <input type="number" step="0.01" min="0" name="cost_price" class="form-control" value="{{ old('cost_price', $inventoryItem->cost_price) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">{{ __('app.sale_price') }}</label>
                    <input type="number" step="0.01" min="0" name="sale_price" class="form-control" value="{{ old('sale_price', $inventoryItem->sale_price) }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" @checked(old('is_active', $inventoryItem->is_active))>
                        <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $inventoryItem->notes) }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-success">{{ __('app.update') }}</button>
                <a href="{{ route('inventory-items.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection