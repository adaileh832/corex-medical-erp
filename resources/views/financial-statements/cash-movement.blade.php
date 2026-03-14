@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.cash_movement_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.cash_movement_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('financial-statements.cash-movement') }}">
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

    @if(!$cashAccount)
        <div class="alert alert-warning">{{ __('app.cash_account_missing') }}</div>
    @else
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_in') }}</div>
                    <h4 class="mb-0">{{ number_format($totalIn, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_out') }}</div>
                    <h4 class="mb-0">{{ number_format($totalOut, 2) }}</h4>
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
                            <th>{{ __('app.in') }}</th>
                            <th>{{ __('app.out') }}</th>
                            <th>{{ __('app.running_balance') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row['entry_date']?->format('Y-m-d') }}</td>
                                <td>{{ $row['entry_number'] }}</td>
                                <td>{{ $row['description'] }}</td>
                                <td>{{ number_format((float) $row['in_amount'], 2) }}</td>
                                <td>{{ number_format((float) $row['out_amount'], 2) }}</td>
                                <td>{{ number_format((float) $row['running_balance'], 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ __('app.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection