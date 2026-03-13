@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.suppliers') }}</h1>
            <p class="text-muted mb-0">{{ __('app.suppliers_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0 d-flex gap-2 flex-wrap">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">{{ __('app.add_supplier') }}</a>
            <a href="{{ route('supplier-invoices.index') }}" class="btn btn-outline-primary">{{ __('app.supplier_invoices') }}</a>
            <a href="{{ route('supplier-payments.index') }}" class="btn btn-outline-success">{{ __('app.supplier_payments') }}</a>
            <a href="{{ route('supplier-statements.index') }}" class="btn btn-outline-dark">{{ __('app.supplier_statements') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('suppliers.index') }}">
            <div class="row g-3">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_suppliers') }}" value="{{ $search }}">
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
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.phone') }}</th>
                        <th>{{ __('app.address') }}</th>
                        <th>{{ __('app.invoices') }}</th>
                        <th>{{ __('app.payments') }}</th>
                        <th style="width: 220px;">{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->phone ?: '-' }}</td>
                            <td>{{ $supplier->address ?: '-' }}</td>
                            <td>{{ $supplier->invoices_count }}</td>
                            <td>{{ $supplier->payments_count }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-sm btn-warning">{{ __('app.edit') }}</a>
                                    <a href="{{ route('supplier-statements.index', ['supplier_id' => $supplier->id]) }}" class="btn btn-sm btn-dark">{{ __('app.statement') }}</a>

                                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete') }}')">
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
            {{ $suppliers->links() }}
        </div>
    </div>
@endsection