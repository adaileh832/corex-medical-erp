@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="mb-1">{{ __('app.journal_entries') }}</h1>
            <p class="text-muted mb-0">{{ __('app.journal_entries_description') }}</p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('journal-entries.create') }}" class="btn btn-danger">{{ __('app.add_journal_entry') }}</a>
        </div>
    </div>

    <div class="card table-card p-4 mb-4">
        <form method="GET" action="{{ route('journal-entries.index') }}">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('app.search_journal_entries') }}" value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                </div>
                <div class="col-md-2">
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
                        <th>{{ __('app.entry_number') }}</th>
                        <th>{{ __('app.entry_date') }}</th>
                        <th>{{ __('app.description') }}</th>
                        <th>{{ __('app.lines_count') }}</th>
                        <th>{{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($journalEntries as $entry)
                        <tr>
                            <td>{{ $entry->entry_number }}</td>
                            <td>{{ $entry->entry_date?->format('Y-m-d') }}</td>
                            <td>{{ $entry->description }}</td>
                            <td>{{ $entry->lines_count }}</td>
                            <td>
                                <a href="{{ route('journal-entries.show', $entry) }}" class="btn btn-sm btn-info text-white">{{ __('app.view') }}</a>
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

        <div class="mt-3">
            {{ $journalEntries->links() }}
        </div>
    </div>
@endsection