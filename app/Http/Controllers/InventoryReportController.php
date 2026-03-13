<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryReportController extends Controller
{
    public function stockSummary(Request $request): View
    {
        $search = trim((string) $request->get('search'));

        $items = InventoryItem::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('item_code', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('inventory-reports.stock-summary', compact('items', 'search'));
    }

    public function lowStock(): View
    {
        $items = InventoryItem::query()
            ->whereColumn('current_quantity', '<=', 'minimum_quantity')
            ->orderBy('name')
            ->paginate(20);

        return view('inventory-reports.low-stock', compact('items'));
    }

    public function movementReport(Request $request): View
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $movementType = trim((string) $request->get('movement_type'));

        $movements = StockMovement::query()
            ->with('item')
            ->when($dateFrom, fn ($query) => $query->whereDate('movement_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('movement_date', '<=', $dateTo))
            ->when($movementType, fn ($query) => $query->where('movement_type', $movementType))
            ->latest('movement_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('inventory-reports.movement-report', compact('movements', 'dateFrom', 'dateTo', 'movementType'));
    }
}