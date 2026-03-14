@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_account') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_account_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('accounts.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.parent_account') }}</label>
                    <select name="parent_id" class="form-select">
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>
                                {{ $parent->account_code }} - {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.account_code') }}</label>
                    <input type="text" name="account_code" class="form-control" value="{{ old('account_code') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name') }}</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.name_en') }}</label>
                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.account_type') }}</label>
                    <select name="account_type" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        <option value="asset" @selected(old('account_type') === 'asset')>{{ __('app.asset') }}</option>
                        <option value="liability" @selected(old('account_type') === 'liability')>{{ __('app.liability') }}</option>
                        <option value="equity" @selected(old('account_type') === 'equity')>{{ __('app.equity') }}</option>
                        <option value="revenue" @selected(old('account_type') === 'revenue')>{{ __('app.revenue') }}</option>
                        <option value="expense" @selected(old('account_type') === 'expense')>{{ __('app.expense') }}</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.normal_balance') }}</label>
                    <select name="normal_balance" class="form-select" required>
                        <option value="debit" @selected(old('normal_balance', 'debit') === 'debit')>{{ __('app.debit') }}</option>
                        <option value="credit" @selected(old('normal_balance') === 'credit')>{{ __('app.credit') }}</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
                        <label class="form-check-label" for="is_active">{{ __('app.active') }}</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-danger">{{ __('app.save') }}</button>
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection