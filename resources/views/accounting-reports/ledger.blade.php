@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.ledger_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.ledger_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('accounting-reports.ledger') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="account_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" @selected((string) $accountId === (string) $account->id)>
                                {{ $account->account_code }} - {{ $account->name }}
                            </option>
                        @endforeach
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

    @if($selectedAccount)
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_debit') }}</div>
                    <h4 class="mb-0">{{ number_format($totalDebit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_credit') }}</div>
                    <h4 class="mb-0">{{ number_format($totalCredit, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.balance') }}</div>
                    <h4 class="mb-0">{{ number_format($balance, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="card table-card p-4">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.entry_date') }}</th>
                            <th>{{ __('app.entry_number') }}</th>
                            <th>{{ __('app.description') }}</th>
                            <th>{{ __('app.debit') }}</th>
                            <th>{{ __('app.credit') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lines as $line)
                            <tr>
                                <td>{{ $line->journalEntry?->entry_date?->format('Y-m-d') }}</td>
                                <td>{{ $line->journalEntry?->entry_number }}</td>
                                <td>{{ $line->description ?: ($line->journalEntry?->description ?? '-') }}</td>
                                <td>{{ number_format((float) $line->debit, 2) }}</td>
                                <td>{{ number_format((float) $line->credit, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('app.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection