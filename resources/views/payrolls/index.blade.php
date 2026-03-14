@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.payrolls') }}</h1>
            <p class="text-muted mb-0">{{ __('app.payrolls_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            @if(auth()->user()->hasPermission('manage-payroll'))
                <a href="{{ route('payrolls.create') }}" class="btn btn-primary">{{ __('app.create_payroll') }}</a>
            @endif
        </div>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.payroll_number') }}</th>
                        <th>{{ __('app.period_year') }}</th>
                        <th>{{ __('app.period_month') }}</th>
                        <th>{{ __('app.period_start') }}</th>
                        <th>{{ __('app.period_end') }}</th>
                        <th>{{ __('app.employees') }}</th>
                        <th style="width: 120px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $payroll)
                        <tr>
                            <td>{{ $payroll->payroll_number }}</td>
                            <td>{{ $payroll->period_year }}</td>
                            <td>{{ $payroll->period_month }}</td>
                            <td>{{ $payroll->period_start?->format('Y-m-d') }}</td>
                            <td>{{ $payroll->period_end?->format('Y-m-d') }}</td>
                            <td>{{ $payroll->items_count }}</td>
                            <td>
                                <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-info text-white">{{ __('app.view') }}</a>
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
            {{ $payrolls->links() }}
        </div>
    </div>
@endsection