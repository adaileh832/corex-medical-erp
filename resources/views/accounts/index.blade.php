@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.chart_of_accounts') }}</h1>
            <p class="text-muted mb-0">{{ __('app.chart_of_accounts_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="{{ route('accounts.create') }}" class="btn btn-danger">{{ __('app.add_account') }}</a>
            <a href="{{ route('journal-entries.index') }}" class="btn btn-outline-danger">{{ __('app.journal_entries') }}</a>
            <a href="{{ route('cash-vouchers.index') }}" class="btn btn-outline-secondary">{{ __('app.cash_vouchers') }}</a>
            <a href="{{ route('bank-transactions.index') }}" class="btn btn-outline-primary">{{ __('app.bank_transactions') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('accounts.index') }}">
            <div class="row g-3">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_accounts') }}" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <select name="account_type" class="form-select">
                        <option value="">{{ __('app.all_account_types') }}</option>
                        <option value="asset" @selected($accountType === 'asset')>{{ __('app.asset') }}</option>
                        <option value="liability" @selected($accountType === 'liability')>{{ __('app.liability') }}</option>
                        <option value="equity" @selected($accountType === 'equity')>{{ __('app.equity') }}</option>
                        <option value="revenue" @selected($accountType === 'revenue')>{{ __('app.revenue') }}</option>
                        <option value="expense" @selected($accountType === 'expense')>{{ __('app.expense') }}</option>
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
                        <th>{{ __('app.account_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.parent_account') }}</th>
                        <th>{{ __('app.account_type') }}</th>
                        <th>{{ __('app.normal_balance') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 190px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                        <tr>
                            <td>{{ $account->account_code }}</td>
                            <td>{{ $account->name }}</td>
                            <td>{{ $account->parent?->name ?? '-' }}</td>
                            <td>{{ __('app.' . $account->account_type) }}</td>
                            <td>{{ __('app.' . $account->normal_balance) }}</td>
                            <td>
                                @if($account->is_active)
                                    <span class="badge bg-success">{{ __('app.active') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('app.inactive') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <form action="{{ route('accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
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
            {{ $accounts->links() }}
        </div>
    </div>
@endsection