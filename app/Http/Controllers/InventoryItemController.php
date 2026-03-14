<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryItemController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $category = trim((string) $request->get('category'));
        $itemType = trim((string) $request->get('item_type'));

        $items = InventoryItem::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('item_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('unit', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($query) => $query->where('category', $category))
            ->when($itemType, fn ($query) => $query->where('item_type', $itemType))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = InventoryItem::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('inventory-items.index', compact('items', 'search', 'category', 'itemType', 'categories'));
    }

    public function create(): View
    {
        return view('inventory-items.create', [
            'nextItemCode' => $this->generateItemCode(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:100', 'unique:inventory_items,item_code'],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:100'],
            'current_quantity' => ['required', 'numeric', 'min:0'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();

        InventoryItem::create($validated);

        return redirect()
            ->route('inventory-items.index')
            ->with('success', __('app.inventory_item_created'));
    }

    public function edit(InventoryItem $inventoryItem): View
    {
        return view('inventory-items.edit', compact('inventoryItem'));
    }

    public function update(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $validated = $request->validate([
            'item_code' => ['required', 'string', 'max:100', 'unique:inventory_items,item_code,' . $inventoryItem->id],
            'name' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'item_type' => ['required', 'string', 'max:100'],
            'unit' => ['required', 'string', 'max:100'],
            'current_quantity' => ['required', 'numeric', 'min:0'],
            'minimum_quantity' => ['required', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $inventoryItem->update($validated);

        return redirect()
            ->route('inventory-items.index')
            ->with('success', __('app.inventory_item_updated'));
    }

    public function destroy(InventoryItem $inventoryItem): RedirectResponse
    {
        if ($inventoryItem->stockMovements()->exists()) {
            return redirect()
                ->route('inventory-items.index')
                ->withErrors([
                    'delete' => __('app.inventory_item_has_movements'),
                ]);
        }

        $inventoryItem->delete();

        return redirect()
            ->route('inventory-items.index')
            ->with('success', __('app.inventory_item_deleted'));
    }

    protected function generateItemCode(): string
    {
        $last = InventoryItem::query()->latest('id')->first();
        $nextId = $last ? ($last->id + 1) : 1;

        return 'ITM-' . now()->format('Y') . '-' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);
    }
}