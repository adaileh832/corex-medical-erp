@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.supplier_invoices') }}</h1>
            <p class="text-muted mb-0">{{ __('app.supplier_invoices_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('supplier-invoices.create') }}" class="btn btn-primary">{{ __('app.add_supplier_invoice') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('supplier-invoices.index') }}">
            <div class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_supplier_invoices') }}" value="{{ $search }}">
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
                        <th>{{ __('app.supplier') }}</th>
                        <th>{{ __('app.amount') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th style="width: 180px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplierInvoices as $item)
                        <tr>
                            <td>{{ $item->invoice_number }}</td>
                            <td>{{ $item->invoice_date?->format('Y-m-d') }}</td>
                            <td>{{ $item->supplier?->name ?? '-' }}</td>
                            <td>{{ number_format((float) $item->amount, 2) }}</td>
                            <td>{{ $item->description ?: '-' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('supplier-invoices.edit', $item) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <form action="{{ route('supplier-invoices.destroy', $item) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">{{ __('app.delete') }}</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $supplierInvoices->links() }}
        </div>
    </div>
@endsection