<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorTransaction;
use App\Models\Invoice;
use App\Models\Operation;
use App\Models\Patient;
use App\Models\Procedure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OperationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $operations = Operation::query()
            ->with([
                'patient',
                'procedure',
                'invoice',
                'gynecologist',
                'anesthetist',
                'pediatrician',
                'doctorTransactions',
            ])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('patient', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('procedure', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('invoice', fn ($q) => $q->where('invoice_number', 'like', "%{$search}%"));
                });
            })
            ->when($dateFrom, fn ($query) => $query->whereDate('operation_date', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('operation_date', '<=', $dateTo))
            ->latest('operation_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('operations.index', compact('operations', 'search', 'dateFrom', 'dateTo'));
    }

    public function create(): View
    {
        return view('operations.create', [
            'patients' => Patient::query()->orderBy('name')->get(),
            'procedures' => Procedure::query()->where('is_active', true)->orderBy('name')->get(),
            'invoices' => Invoice::query()->orderByDesc('id')->get(),
            'gynecologists' => Doctor::query()->where('is_active', true)->where('doctor_type', __('app.gynecologist'))->orderBy('name')->get(),
            'anesthetists' => Doctor::query()->where('is_active', true)->where('doctor_type', __('app.anesthetist'))->orderBy('name')->get(),
            'pediatricians' => Doctor::query()->where('is_active', true)->where('doctor_type', __('app.pediatrician'))->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'procedure_id' => ['required', 'exists:procedures,id'],
            'invoice_id' => ['nullable', 'exists:invoices,id'],
            'operation_date' => ['required', 'date'],
            'gynecologist_id' => ['nullable', 'exists:doctors,id'],
            'anesthetist_id' => ['nullable', 'exists:doctors,id'],
            'pediatrician_id' => ['nullable', 'exists:doctors,id'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated) {
            $validated['created_by'] = auth()->id();

            $operation = Operation::create($validated);

            $this->createDoctorTransactions($operation);
        });

        return redirect()
            ->route('operations.index')
            ->with('success', __('app.operation_created'));
    }

    public function show(Operation $operation): View
    {
        $operation->load([
            'patient',
            'procedure',
            'invoice',
            'gynecologist',
            'anesthetist',
            'pediatrician',
            'doctorTransactions.doctor',
        ]);

        return view('operations.show', compact('operation'));
    }

    public function destroy(Operation $operation): RedirectResponse
    {
        DB::transaction(function () use ($operation) {
            $operation->doctorTransactions()->delete();
            $operation->delete();
        });

        return redirect()
            ->route('operations.index')
            ->with('success', __('app.operation_deleted'));
    }

    protected function createDoctorTransactions(Operation $operation): void
    {
        $operation->load(['procedure']);

        $procedure = $operation->procedure;

        if (! $procedure) {
            return;
        }

        $entries = [
            [
                'doctor_id' => $operation->gynecologist_id,
                'doctor_role' => 'gynecologist',
                'description' => __('app.gynecologist_fee') . ' - ' . $procedure->name,
                'amount' => (float) $procedure->gynecologist_fee,
            ],
            [
                'doctor_id' => $operation->anesthetist_id,
                'doctor_role' => 'anesthetist',
                'description' => __('app.anesthetist_fee') . ' - ' . $procedure->name,
                'amount' => (float) $procedure->anesthetist_fee,
            ],
            [
                'doctor_id' => $operation->pediatrician_id,
                'doctor_role' => 'pediatrician',
                'description' => __('app.pediatrician_fee') . ' - ' . $procedure->name,
                'amount' => (float) $procedure->pediatrician_fee,
            ],
        ];

        foreach ($entries as $entry) {
            if (! $entry['doctor_id'] || $entry['amount'] <= 0) {
                continue;
            }

            DoctorTransaction::create([
                'doctor_id' => $entry['doctor_id'],
                'operation_id' => $operation->id,
                'invoice_id' => $operation->invoice_id,
                'procedure_id' => $operation->procedure_id,
                'patient_id' => $operation->patient_id,
                'transaction_date' => $operation->operation_date,
                'doctor_role' => $entry['doctor_role'],
                'description' => $entry['description'],
                'amount' => $entry['amount'],
                'created_by' => auth()->id(),
            ]);
        }
    }
}