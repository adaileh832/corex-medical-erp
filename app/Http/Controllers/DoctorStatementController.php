<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorPayment;
use App\Models\DoctorTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorStatementController extends Controller
{
    public function index(Request $request): View
    {
        $doctorId = $request->get('doctor_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $doctors = Doctor::query()->orderBy('name')->get();

        $selectedDoctor = null;
        $transactions = collect();
        $payments = collect();
        $totalTransactions = 0;
        $totalPayments = 0;
        $balance = 0;

        if ($doctorId) {
            $selectedDoctor = Doctor::query()->findOrFail($doctorId);

            $transactions = DoctorTransaction::query()
                ->with(['patient', 'procedure', 'operation'])
                ->where('doctor_id', $doctorId)
                ->when($dateFrom, fn ($query) => $query->whereDate('transaction_date', '>=', $dateFrom))
                ->when($dateTo, fn ($query) => $query->whereDate('transaction_date', '<=', $dateTo))
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->get();

            $payments = DoctorPayment::query()
                ->where('doctor_id', $doctorId)
                ->when($dateFrom, fn ($query) => $query->whereDate('payment_date', '>=', $dateFrom))
                ->when($dateTo, fn ($query) => $query->whereDate('payment_date', '<=', $dateTo))
                ->orderBy('payment_date')
                ->orderBy('id')
                ->get();

            $totalTransactions = (float) $transactions->sum('amount');
            $totalPayments = (float) $payments->sum('amount');
            $balance = $totalTransactions - $totalPayments;
        }

        return view('doctor-statements.index', compact(
            'doctors',
            'selectedDoctor',
            'transactions',
            'payments',
            'totalTransactions',
            'totalPayments',
            'balance',
            'doctorId',
            'dateFrom',
            'dateTo'
        ));
    }
}