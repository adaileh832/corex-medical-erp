@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_bank_transaction') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_bank_transaction_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('bank-transactions.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.transaction_number') }}</label>
                    <input type="text" name="transaction_number" class="form-control" value="{{ old('transaction_number', $nextTransactionNumber) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.transaction_date') }}</label>
                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.transaction_type') }}</label>
                    <select name="transaction_type" class="form-select" required>
                        <option value="deposit" @selected(old('transaction_type', 'deposit') === 'deposit')>{{ __('app.deposit') }}</option>
                        <option value="withdraw" @selected(old('transaction_type') === 'withdraw')>{{ __('app.withdraw') }}</option>
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
                <button class="btn btn-primary">{{ __('app.save') }}</button>
                <a href="{{ route('bank-transactions.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection