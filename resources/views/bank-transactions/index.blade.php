@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.bank_transactions') }}</h1>
            <p class="text-muted mb-0">{{ __('app.bank_transactions_description') }}</p>
        </div>

        <a href="{{ route('bank-transactions.create') }}" class="btn btn-primary">{{ __('app.add_bank_transaction') }}</a>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('bank-transactions.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="transaction_type" class="form-select">
                        <option value="">{{ __('app.all_transaction_types') }}</option>
                        <option value="deposit" @selected($transactionType === 'deposit')>{{ __('app.deposit') }}</option>
                        <option value="withdraw" @selected($transactionType === 'withdraw')>{{ __('app.withdraw') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
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
                        <th>{{ __('app.transaction_number') }}</th>
                        <th>{{ __('app.transaction_date') }}</th>
                        <th>{{ __('app.transaction_type') }}</th>
                        <th>{{ __('app.account') }}</th>
                        <th>{{ __('app.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bankTransactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_number }}</td>
                            <td>{{ $transaction->transaction_date?->format('Y-m-d') }}</td>
                            <td>{{ $transaction->transaction_type === 'deposit' ? __('app.deposit') : __('app.withdraw') }}</td>
                            <td>{{ $transaction->account?->name ?? '-' }}</td>
                            <td>{{ number_format((float) $transaction->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $bankTransactions->links() }}
        </div>
    </div>
@endsection