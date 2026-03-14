@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.invoices') }}</h1>
            <p class="text-muted mb-0">{{ __('app.invoices_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('invoices.create') }}" class="btn btn-warning text-dark">
                {{ __('app.add_invoice') }}
            </a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('invoices.index') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_invoices') }}" value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
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
                        <th>{{ __('app.invoice_number') }}</th>
                        <th>{{ __('app.invoice_date') }}</th>
                        <th>{{ __('app.patient') }}</th>
                        <th>{{ __('app.doctor') }}</th>
                        <th>{{ __('app.service_name') }}</th>
                        <th>{{ __('app.amount') }}</th>
                        <th>{{ __('app.paid_amount') }}</th>
                        <th>{{ __('app.remaining_amount') }}</th>
                        <th>{{ __('app.status') }}</th>
                        <th style="width: 220px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->invoice_date?->format('Y-m-d') }}</td>
                            <td>{{ $invoice->patient?->name ?? '-' }}</td>
                            <td>{{ $invoice->doctor?->name ?? '-' }}</td>
                            <td>{{ $invoice->service_name }}</td>
                            <td>{{ number_format((float) $invoice->amount, 2) }}</td>
                            <td>{{ number_format((float) $invoice->paid_amount, 2) }}</td>
                            <td>{{ number_format((float) $invoice->remaining_amount, 2) }}</td>
                            <td>
                                @if($invoice->status === 'paid')
                                    <span class="badge bg-success">{{ __('app.paid') }}</span>
                                @elseif($invoice->status === 'partially_paid')
                                    <span class="badge bg-warning text-dark">{{ __('app.partially_paid') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('app.unpaid') }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info text-white">{{ __('app.view') }}</a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning text-dark">{{ __('app.edit') }}</a>

                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $invoices->links() }}
        </div>
    </div>
@endsection