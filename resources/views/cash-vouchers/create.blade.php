@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_cash_voucher') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_cash_voucher_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('cash-vouchers.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.voucher_number') }}</label>
                    <input type="text" name="voucher_number" class="form-control" value="{{ old('voucher_number', $nextVoucherNumber) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.voucher_date') }}</label>
                    <input type="date" name="voucher_date" class="form-control" value="{{ old('voucher_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.voucher_type') }}</label>
                    <select name="voucher_type" class="form-select" required>
                        <option value="receipt" @selected(old('voucher_type', 'receipt') === 'receipt')>{{ __('app.receipt_voucher') }}</option>
                        <option value="payment" @selected(old('voucher_type') === 'payment')>{{ __('app.payment_voucher') }}</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.account') }}</label>
                    <select name="account_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected(old('account_id') == $account->id)>
                                {{ $account->account_code }} - {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.amount') }}</label>
                    <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.description') }}</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label">{{ __('app.notes') }}</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-secondary">{{ __('app.save') }}</button>
                <a href="{{ route('cash-vouchers.index') }}" class="btn btn-outline-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection