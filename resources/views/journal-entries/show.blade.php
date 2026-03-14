@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.journal_entry_details') }}</h1>
            <p class="text-muted mb-0">{{ $journalEntry->entry_number }}</p>
        </div>

        <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
    </div>

    <div class="card content-card p-4 mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <strong>{{ __('app.entry_date') }}</strong>
                <div>{{ $journalEntry->entry_date?->format('Y-m-d') }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.reference_type') }}</strong>
                <div>{{ $journalEntry->reference_type ?: '-' }}</div>
            </div>
            <div class="col-md-4">
                <strong>{{ __('app.reference_number') }}</strong>
                <div>{{ $journalEntry->reference_number ?: '-' }}</div>
            </div>
            <div class="col-12">
                <strong>{{ __('app.description') }}</strong>
                <div>{{ $journalEntry->description }}</div>
            </div>
        </div>
    </div>

    <div class="card table-card p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.account_code') }}</th>
                        <th>{{ __('app.name') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th>{{ __('app.debit') }}</th>
                        <th>{{ __('app.credit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journalEntry->lines as $line)
                        <tr>
                            <td>{{ $line->account?->account_code ?? '-' }}</td>
                            <td>{{ $line->account?->name ?? '-' }}</td>
                            <td>{{ $line->description ?: '-' }}</td>
                            <td>{{ number_format((float) $line->debit, 2) }}</td>
                            <td>{{ number_format((float) $line->credit, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">{{ __('app.total') }}</th>
                        <th>{{ number_format((float) $journalEntry->total_debit, 2) }}</th>
                        <th>{{ number_format((float) $journalEntry->total_credit, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection