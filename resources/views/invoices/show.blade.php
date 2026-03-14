@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.invoice_details') }}</h1>
            <p class="text-muted mb-0">{{ $invoice->invoice_number }}</p>
        </div>

        <div class="mt-3 mt-md-0 d-flex gap-2">
            @if(auth()->user()->hasPermission('manage-payments'))
                <a href="{{ route('payments.create', $invoice) }}" class="btn btn-success">{{ __('app.add_payment') }}</a>
            @endif
            <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning text-dark">{{ __('app.edit') }}</a>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.amount') }}</div>
                <h4 class="mb-0">{{ number_format((float) $invoice->amount, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.paid_amount') }}</div>
                <h4 class="mb-0">{{ number_format((float) $invoice->paid_amount, 2) }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-box">
                <div class="text-muted">{{ __('app.remaining_amount') }}</div>
                <h4 class="mb-0">{{ number_format((float) $invoice->remaining_amount, 2) }}</h4>
            </div>
        </div>
    </div>

    <div class="card content-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>{{ __('app.invoice_number') }}</strong>
                <div>{{ $invoice->invoice_number }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.invoice_date') }}</strong>
                <div>{{ $invoice->invoice_date?->format('Y-m-d') }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.status') }}</strong>
                <div>
                    @if($invoice->status === 'paid')
                        <span class="badge bg-success">{{ __('app.paid') }}</span>
                    @elseif($invoice->status === 'partially_paid')
                        <span class="badge bg-warning text-dark">{{ __('app.partially_paid') }}</span>
                    @else
                        <span class="badge bg-danger">{{ __('app.unpaid') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-4">
                <strong>{{ __('app.patient') }}</strong>
                <div>{{ $invoice->patient?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.doctor') }}</strong>
                <div>{{ $invoice->doctor?->name ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.procedure') }}</strong>
                <div>{{ $invoice->procedure?->name ?? '-' }}</div>
            </div>

            <div class="col-md-6">
                <strong>{{ __('app.service_name') }}</strong>
                <div>{{ $invoice->service_name }}</div>
            </div>
            <div class="col-md-6">
                <strong>{{ __('app.created_by') }}</strong>
                <div>{{ $invoice->creator?->name ?? '-' }}</div>
            </div>

            <div class="col-12">
                <strong>{{ __('app.notes') }}</strong>
                <div>{{ $invoice->notes ?: '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ __('app.payments') }}</h4>
            @if(auth()->user()->hasPermission('manage-payments'))
                <a href="{{ route('payments.create', $invoice) }}" class="btn btn-success btn-sm">{{ __('app.add_payment') }}</a>
            @endif
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.receipt_number') }}</th>
                        <th>{{ __('app.payment_date') }}</th>
                        <th>{{ __('app.payment_method') }}</th>
                        <th>{{ __('app.amount') }}</th>
                        <th>{{ __('app.created_by') }}</th>
                        <th>{{ __('app.notes') }}</th>
                        <th style="width: 120px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td>{{ $payment->receipt_number }}</td>
                            <td>{{ $payment->payment_date?->format('Y-m-d') }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>{{ number_format((float) $payment->amount, 2) }}</td>
                            <td>{{ $payment->creator?->name ?? '-' }}</td>
                            <td>{{ $payment->notes ?: '-' }}</td>
                            <td>
                                <form action="{{ route('payments.destroy', [$invoice, $payment]) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                </form>
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
    </div>
@endsection