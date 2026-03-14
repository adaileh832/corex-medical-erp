@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.balance_sheet') }}</h1>
        <p class="text-muted mb-0">{{ __('app.balance_sheet_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('financial-statements.balance-sheet') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100">{{ __('app.search') }}</button>
                </div>
            </div>
        </form>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card table-card p-4 h-100">
                <h4 class="mb-3">{{ __('app.assets') }}</h4>
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
                            @forelse($assets as $row)
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
                                <th colspan="2">{{ __('app.total_assets') }}</th>
                                <th>{{ number_format($totalAssets, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card table-card p-4 h-100">
                <h4 class="mb-3">{{ __('app.liabilities_and_equity') }}</h4>

                <h5 class="mb-2">{{ __('app.liabilities') }}</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('app.account_code') }}</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liabilities as $row)
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
                                <th colspan="2">{{ __('app.total_liabilities') }}</th>
                                <th>{{ number_format($totalLiabilities, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <h5 class="mb-2">{{ __('app.equity') }}</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('app.account_code') }}</th>
                                <th>{{ __('app.name') }}</th>
                                <th>{{ __('app.balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($equity as $row)
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
                            <tr>
                                <td>-</td>
                                <td>{{ __('app.retained_earnings') }}</td>
                                <td>{{ number_format($retainedEarnings, 2) }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">{{ __('app.total_equity') }}</th>
                                <th>{{ number_format($totalEquity, 2) }}</th>
                            </tr>
                            <tr>
                                <th colspan="2">{{ __('app.total_liabilities_and_equity') }}</th>
                                <th>{{ number_format($totalLiabilitiesAndEquity, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection