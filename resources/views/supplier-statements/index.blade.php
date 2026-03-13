@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.supplier_statements') }}</h1>
        <p class="text-muted mb-0">{{ __('app.supplier_statements_description') }}</p>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('supplier-statements.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="supplier_id" class="form-select" required>
                        <option value="">{{ __('app.select_option') }}</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected((string) $supplierId === (string) $supplier->id)>{{ $supplier->name }}</option>
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

    @if($selectedSupplier)
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_supplier_invoices') }}</div>
                    <h4 class="mb-0">{{ number_format($totalInvoices, 2) }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <div class="text-muted">{{ __('app.total_supplier_payments') }}</div>
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

        <div class="card table-card p-4 mb-4">
            <h4 class="mb-3">{{ __('app.supplier_invoices') }}</h4>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.invoice_number') }}</th>
                            <th>{{ __('app.invoice_date') }}</th>
                            <th>{{ __('app.amount') }}</th>
                            <th>{{ __('app.description') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplierInvoices as $item)
                            <tr>
                                <td>{{ $item->invoice_number }}</td>
                                <td>{{ $item->invoice_date?->format('Y-m-d') }}</td>
                                <td>{{ number_format((float) $item->amount, 2) }}</td>
                                <td>{{ $item->description ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">{{ __('app.no_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card table-card p-4">
            <h4 class="mb-3">{{ __('app.supplier_payments') }}</h4>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('app.payment_number') }}</th>
                            <th>{{ __('app.payment_date') }}</th>
                            <th>{{ __('app.amount') }}</th>
                            <th>{{ __('app.payment_method') }}</th>
                            <th>{{ __('app.notes') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplierPayments as $item)
                            <tr>
                                <td>{{ $item->payment_number }}</td>
                                <td>{{ $item->payment_date?->format('Y-m-d') }}</td>
                                <td>{{ number_format((float) $item->amount, 2) }}</td>
                                <td>{{ $item->payment_method }}</td>
                                <td>{{ $item->notes ?: '-' }}</td>
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