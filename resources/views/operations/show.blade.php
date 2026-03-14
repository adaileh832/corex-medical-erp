@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.operation_details') }}</h1>
            <p class="text-muted mb-0">{{ $operation->operation_date?->format('Y-m-d') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('operations.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
        </div>
    </div>

    <div class="card content-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>{{ __('app.operation_date') }}</strong>
                <div>{{ $operation->operation_date?->format('Y-m-d') }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.patient') }}</strong>
                <div>{{ $operation->patient?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.procedure') }}</strong>
                <div>{{ $operation->procedure?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.gynecologist') }}</strong>
                <div>{{ $operation->gynecologist?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.anesthetist') }}</strong>
                <div>{{ $operation->anesthetist?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.pediatrician') }}</strong>
                <div>{{ $operation->pediatrician?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.invoice_number') }}</strong>
                <div>{{ $operation->invoice?->invoice_number ?? '-' }}</div>
            </div>
            <div class="col-12">
                <strong>{{ __('app.notes') }}</strong>
                <div>{{ $operation->notes ?: '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card p-4">
        <h4 class="mb-3">{{ __('app.doctor_transactions') }}</h4>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.doctor') }}</th>
                        <th>{{ __('app.doctor_role') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th>{{ __('app.amount') }}</th>
                        <th>{{ __('app.transaction_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($operation->doctorTransactions as $item)
                        <tr>
                            <td>{{ $item->doctor?->name ?? '-' }}</td>
                            <td>{{ __('app.' . $item->doctor_role) }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ number_format((float) $item->amount, 2) }}</td>
                            <td>{{ $item->transaction_date?->format('Y-m-d') }}</td>
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
@endsection