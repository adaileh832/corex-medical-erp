@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.revenue_report') }}</h1>
        <p class="text-muted mb-0">{{ __('app.revenue_report_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('financial-statements.revenue-report') }}">
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
                        <th>{{ __('app.balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['account']->account_code }}</td>
                            <td>{{ $row['account']->name }}</td>
                            <td>{{ number_format((float) $row['balance'], 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">{{ __('app.total_revenue') }}</th>
                        <th>{{ number_format($totalRevenue, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection