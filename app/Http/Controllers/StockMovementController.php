<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockMovementController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $movementType = trim((string) $request->get('movement_type'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $movements = StockMovement::query()
            ->with(['item', 'creator'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('reason', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('item', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('item_code', 'like', "%{$search}%");
                        });
                });
            })
            ->when($movementType, fn ($query) => $query->where('movement_type', $movementType))
            ->when($dateFrom, fn ($query) => $query->whereDate('movement_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('movement_date', '<=', $dateTo))
            ->latest('movement_date')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('stock-movements.index', compact('movements', 'search', 'movementType', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('stock-movements.create', [
            'items' => InventoryItem::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'inventory_item_id' => ['required', 'exists:inventory_items,id'],
            'movement_date' => ['required', 'date'],
            'movement_type' => ['required', 'in:in,out,adjustment'],
            'quantity' => ['required', 'numeric', 'min:0.01'],
            'reference_type' => ['nullable', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'reason' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $item = InventoryItem::query()->lockForUpdate()->findOrFail($validated['inventory_item_id']);
            $quantity = (float) $validated['quantity'];
            $currentBalance = (float) $item->current_quantity;
            $newBalance = $currentBalance;

            if ($validated['movement_type'] === 'in') {
                $newBalance = $currentBalance + $quantity;
            } elseif ($validated['movement_type'] === 'out') {
                if ($quantity > $currentBalance) {
                    abort(422, __('app.insufficient_stock'));
                }

                $newBalance = $currentBalance - $quantity;
            } elseif ($validated['movement_type'] === 'adjustment') {
                $newBalance = $quantity;
            }

            $item->update([
                'current_quantity' => $newBalance,
            ]);

            StockMovement::create([
                'inventory_item_id' => $item->id,
                'movement_date' => $validated['movement_date'],
                'movement_type' => $validated['movement_type'],
                'quantity' => $quantity,
                'balance_after' => $newBalance,
                'reference_type' => $validated['reference_type'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('stock-movements.index')
            ->with('success', __('app.stock_movement_created'));
    }
}