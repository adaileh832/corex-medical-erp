@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.trial_balance') }}</h1>
        <p class="text-muted mb-0">{{ __('app.trial_balance_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('accounting-reports.trial-balance') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-5">
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
                        <th>{{ __('app.account_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.debit') }}</th>
                        <th>{{ __('app.credit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['account']->account_code }}</td>
                            <td>{{ $row['account']->name }}</td>
                            <td>{{ number_format((float) $row['debit'], 2) }}</td>
                            <td>{{ number_format((float) $row['credit'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">{{ __('app.total') }}</th>
                        <th>{{ number_format($totalDebit, 2) }}</th>
                        <th>{{ number_format($totalCredit, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection