@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.cash_vouchers') }}</h1>
            <p class="text-muted mb-0">{{ __('app.cash_vouchers_description') }}</p>
        </div>

        <a href="{{ route('cash-vouchers.create') }}" class="btn btn-secondary">{{ __('app.add_cash_voucher') }}</a>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('cash-vouchers.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <select name="voucher_type" class="form-select">
                        <option value="">{{ __('app.all_voucher_types') }}</option>
                        <option value="receipt" @selected($voucherType === 'receipt')>{{ __('app.receipt_voucher') }}</option>
                        <option value="payment" @selected($voucherType === 'payment')>{{ __('app.payment_voucher') }}</option>
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

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.voucher_number') }}</th>
                        <th>{{ __('app.voucher_date') }}</th>
                        <th>{{ __('app.voucher_type') }}</th>
                        <th>{{ __('app.account') }}</th>
                        <th>{{ __('app.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashVouchers as $voucher)
                        <tr>
                            <td>{{ $voucher->voucher_number }}</td>
                            <td>{{ $voucher->voucher_date?->format('Y-m-d') }}</td>
                            <td>{{ $voucher->voucher_type === 'receipt' ? __('app.receipt_voucher') : __('app.payment_voucher') }}</td>
                            <td>{{ $voucher->account?->name ?? '-' }}</td>
                            <td>{{ number_format((float) $voucher->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">{{ __('app.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cashVouchers->links() }}
        </div>
    </div>
@endsection