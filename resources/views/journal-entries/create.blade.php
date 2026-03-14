@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-1">{{ __('app.add_journal_entry') }}</h1>
        <p class="text-muted mb-0">{{ __('app.add_journal_entry_description') }}</p>
    </div>

    <div class="card content-card p-4">
        <form action="{{ route('journal-entries.store') }}" method="POST">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.entry_number') }}</label>
                    <input type="text" name="entry_number" class="form-control" value="{{ old('entry_number', $nextEntryNumber) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.entry_date') }}</label>
                    <input type="date" name="entry_date" class="form-control" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">{{ __('app.reference_type') }}</label>
                    <input type="text" name="reference_type" class="form-control" value="{{ old('reference_type') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.reference_number') }}</label>
                    <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">{{ __('app.description') }}</label>
                    <input type="text" name="description" class="form-control" value="{{ old('description') }}" required>
                </div>
            </div>

            @for($i = 0; $i < 4; $i++)
                <div class="row g-3 mb-2">
                    <div class="col-md-4">
                        <select name="lines[{{ $i }}][account_id]" class="form-select" {{ $i < 2 ? 'required' : '' }}>
                            <option value="">{{ __('app.select_option') }}</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" @selected(old("lines.$i.account_id") == $account->id)>
                                    {{ $account->account_code }} - {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="lines[{{ $i }}][description]" class="form-control" placeholder="{{ __('app.description') }}" value="{{ old("lines.$i.description") }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" min="0" name="lines[{{ $i }}][debit]" class="form-control" placeholder="{{ __('app.debit') }}" value="{{ old("lines.$i.debit") }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" min="0" name="lines[{{ $i }}][credit]" class="form-control" placeholder="{{ __('app.credit') }}" value="{{ old("lines.$i.credit") }}">
                    </div>
                </div>
            @endfor

            <div class="mt-4">
                <label class="form-label">{{ __('app.notes') }}</label>
                <textarea name="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-danger">{{ __('app.save') }}</button>
                <a href="{{ route('journal-entries.index') }}" class="btn btn-secondary">{{ __('app.back') }}</a>
            </div>
        </form>
    </div>
@endsection