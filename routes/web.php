<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorPaymentController;
use App\Http\Controllers\DoctorStatementController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:manage-patients')->group(function () {
        Route::get('/patients', [PatientController::class, 'index'])->name('patients.index');
        Route::get('/patients/create', [PatientController::class, 'create'])->name('patients.create');
        Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
        Route::get('/patients/{patient}/edit', [PatientController::class, 'edit'])->name('patients.edit');
        Route::put('/patients/{patient}', [PatientController::class, 'update'])->name('patients.update');
        Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');
    });

    Route::middleware('permission:manage-doctors')->group(function () {
        Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
        Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
        Route::post('/doctors', [DoctorController::class, 'store'])->name('doctors.store');
        Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::put('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
        Route::delete('/doctors/{doctor}', [DoctorController::class, 'destroy'])->name('doctors.destroy');
    });

    Route::middleware('permission:manage-procedures')->group(function () {
        Route::get('/procedures', [ProcedureController::class, 'index'])->name('procedures.index');
        Route::get('/procedures/create', [ProcedureController::class, 'create'])->name('procedures.create');
        Route::post('/procedures', [ProcedureController::class, 'store'])->name('procedures.store');
        Route::get('/procedures/{procedure}/edit', [ProcedureController::class, 'edit'])->name('procedures.edit');
        Route::put('/procedures/{procedure}', [ProcedureController::class, 'update'])->name('procedures.update');
        Route::delete('/procedures/{procedure}', [ProcedureController::class, 'destroy'])->name('procedures.destroy');
    });

    Route::middleware('permission:manage-invoices')->group(function () {
        Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('/invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('/invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
        Route::put('/invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
        Route::delete('/invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    });

    Route::middleware('permission:manage-payments')->group(function () {
        Route::get('/invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
        Route::delete('/invoices/{invoice}/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    });

    Route::middleware('permission:manage-operations')->group(function () {
        Route::get('/operations', [OperationController::class, 'index'])->name('operations.index');
        Route::get('/operations/create', [OperationController::class, 'create'])->name('operations.create');
        Route::post('/operations', [OperationController::class, 'store'])->name('operations.store');
        Route::get('/operations/{operation}', [OperationController::class, 'show'])->name('operations.show');
        Route::delete('/operations/{operation}', [OperationController::class, 'destroy'])->name('operations.destroy');
    });

    Route::middleware('permission:view-doctor-statements')->group(function () {
        Route::get('/doctor-statements', [DoctorStatementController::class, 'index'])->name('doctor-statements.index');
    });

    Route::middleware('permission:manage-doctor-payments')->group(function () {
        Route::get('/doctors/{doctor}/payments/create', [DoctorPaymentController::class, 'create'])->name('doctor-payments.create');
        Route::post('/doctors/{doctor}/payments', [DoctorPaymentController::class, 'store'])->name('doctor-payments.store');
        Route::delete('/doctor-payments/{doctorPayment}', [DoctorPaymentController::class, 'destroy'])->name('doctor-payments.destroy');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});