<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $accountType = trim((string) $request->get('account_type'));

        $accounts = Account::query()
            ->with('parent')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('account_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%");
                });
            })
            ->when($accountType, fn ($query) => $query->where('account_type', $accountType))
            ->orderBy('account_code')
            ->paginate(20)
            ->withQueryString();

        return view('accounts.index', compact('accounts', 'search', 'accountType'));
    }

    public function create(): View
    {
        return view('accounts.create', [
            'parents' => Account::query()->orderBy('account_code')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:accounts,id'],
            'account_code' => ['required', 'string', 'max:100', 'unique:accounts,account_code'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'account_type' => ['required', 'in:asset,liability,equity,revenue,expense'],
            'normal_balance' => ['required', 'in:debit,credit'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();

        Account::create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', __('app.account_created'));
    }

    public function edit(Account $account): View
    {
        return view('accounts.edit', [
            'account' => $account,
            'parents' => Account::query()
                ->where('id', '!=', $account->id)
                ->orderBy('account_code')
                ->get(),
        ]);
    }

    public function update(Request $request, Account $account): RedirectResponse
    {
        $validated = $request->validate([
            'parent_id' => ['nullable', 'exists:accounts,id'],
            'account_code' => ['required', 'string', 'max:100', 'unique:accounts,account_code,' . $account->id],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'account_type' => ['required', 'in:asset,liability,equity,revenue,expense'],
            'normal_balance' => ['required', 'in:debit,credit'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', __('app.account_updated'));
    }

    public function destroy(Account $account): RedirectResponse
    {
        if ($account->children()->exists() || $account->journalEntryLines()->exists()) {
            return redirect()
                ->route('accounts.index')
                ->withErrors([
                    'delete' => __('app.account_has_transactions'),
                ]);
        }

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', __('app.account_deleted'));
    }
}