<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProcedureController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $procedures = Procedure::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('procedures.index', compact('procedures', 'search'));
    }

    public function create(): View
    {
        return view('procedures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'gynecologist_fee' => ['required', 'numeric', 'min:0'],
            'anesthetist_fee' => ['required', 'numeric', 'min:0'],
            'pediatrician_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Procedure::create($validated);

        return redirect()
            ->route('procedures.index')
            ->with('success', __('app.procedure_created'));
    }

    public function edit(Procedure $procedure): View
    {
        return view('procedures.edit', compact('procedure'));
    }

    public function update(Request $request, Procedure $procedure): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'gynecologist_fee' => ['required', 'numeric', 'min:0'],
            'anesthetist_fee' => ['required', 'numeric', 'min:0'],
            'pediatrician_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $procedure->update($validated);

        return redirect()
            ->route('procedures.index')
            ->with('success', __('app.procedure_updated'));
    }

    public function destroy(Procedure $procedure): RedirectResponse
    {
        $procedure->delete();

        return redirect()
            ->route('procedures.index')
            ->with('success', __('app.procedure_deleted'));
    }
}