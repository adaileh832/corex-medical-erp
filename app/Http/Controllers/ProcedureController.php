<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProcedureController extends Controller
{
    public function index(): View
    {
        $procedures = Procedure::query()
            ->latest()
            ->paginate(10);

        return view('procedures.index', [
            'procedures' => $procedures,
        ]);
    }

    public function create(): View
    {
        return view('procedures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'اسم الإجراء مطلوب / Procedure name is required.',
            'price.required' => 'السعر مطلوب / Price is required.',
        ]);

        $data = $this->buildProcedureData($validated, $request);

        Procedure::create($data);

        return redirect()
            ->route('procedures.index')
            ->with('success', 'تم إنشاء الإجراء بنجاح / Procedure created successfully.');
    }

    public function show(Procedure $procedure): View
    {
        return view('procedures.show', [
            'procedure' => $procedure,
        ]);
    }

    public function edit(Procedure $procedure): View
    {
        return view('procedures.edit', [
            'procedure' => $procedure,
        ]);
    }

    public function update(Request $request, Procedure $procedure): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'اسم الإجراء مطلوب / Procedure name is required.',
            'price.required' => 'السعر مطلوب / Price is required.',
        ]);

        $data = $this->buildProcedureData($validated, $request);

        $procedure->update($data);

        return redirect()
            ->route('procedures.index')
            ->with('success', 'تم تحديث الإجراء بنجاح / Procedure updated successfully.');
    }

    public function destroy(Procedure $procedure): RedirectResponse
    {
        $procedure->delete();

        return redirect()
            ->route('procedures.index')
            ->with('success', 'تم حذف الإجراء بنجاح / Procedure deleted successfully.');
    }

    private function buildProcedureData(array $validated, Request $request): array
    {
        $data = [
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if (Schema::hasColumn('procedures', 'title') && !Schema::hasColumn('procedures', 'name')) {
            $data['title'] = $validated['name'];
            unset($data['name']);
        }

        if (Schema::hasColumn('procedures', 'status') && !Schema::hasColumn('procedures', 'is_active')) {
            $data['status'] = $request->boolean('is_active') ? 'active' : 'inactive';
            unset($data['is_active']);
        }

        return $data;
    }
}
