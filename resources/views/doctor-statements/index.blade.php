@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.doctor_statements') }}</h1>
        <p class="text-muted mb-0">{{ __('app.doctor_statements_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('doctor-statements.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="doctor_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" @selected((string) $doctorId === (string) $doctor->id)>{{ $doctor->name }}</option>
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

    @if($selectedDoctor)
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_entitlements') }}</div>
                    <h4 class="mb-0">{{ number_format($totalTransactions, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_payments') }}</div>
                    <h4 class="mb-0">{{ number_format($totalPayments, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.balance') }}</div>
                    <h4 class="mb-0">{{ number_format($balance, 2) }}</h4>
                </div>
            </div>
        </div>

        <div class="mb-4">
            @if(auth()->user()->hasPermission('manage-doctor-payments'))
                <a href="{{ route('doctor-payments.create', $selectedDoctor) }}" class="btn btn-success">
                    {{ __('app.add_doctor_payment') }}
                </a>
            @endif
        </div>

        <div class="card table-card p-4 mb-4">
            <h4 class="mb-3">{{ __('app.doctor_transactions') }}</h4>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.transaction_date') }}</th>
                            <th>{{ __('app.patient') }}</th>
                            <th>{{ __('app.procedure') }}</th>
                            <th>{{ __('app.description') }}</th>
                            <th>{{ __('app.amount') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $item)
                            <tr>
                                <td>{{ $item->transaction_date?->format('Y-m-d') }}</td>
                                <td>{{ $item->patient?->name ?? '-' }}</td>
                                <td>{{ $item->procedure?->name ?? '-' }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ number_format((float) $item->amount, 2) }}</td>
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

        <div class="card table-card p-4">
            <h4 class="mb-3">{{ __('app.doctor_payments') }}</h4>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.payment_number') }}</th>
                            <th>{{ __('app.payment_date') }}</th>
                            <th>{{ __('app.amount') }}</th>
                            <th>{{ __('app.notes') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_number }}</td>
                                <td>{{ $payment->payment_date?->format('Y-m-d') }}</td>
                                <td>{{ number_format((float) $payment->amount, 2) }}</td>
                                <td>{{ $payment->notes ?: '-' }}</td>
                                <td>
                                    @if(auth()->user()->hasPermission('manage-doctor-payments'))
                                        <form action="{{ route('doctor-payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                        </form>
                                    @endif
                                </td>
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