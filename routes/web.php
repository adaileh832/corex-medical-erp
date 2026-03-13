<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorPaymentController;
use App\Http\Controllers\DoctorStatementController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierInvoiceController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\SupplierStatementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/setup/manager', [SetupController::class, 'createManager'])->name('setup.manager');
    Route::post('/setup/manager', [SetupController::class, 'storeManager'])->name('setup.manager.store');
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

    Route::middleware('permission:manage-suppliers')->group(function () {
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');
    });

    Route::middleware('permission:manage-supplier-invoices')->group(function () {
        Route::get('/supplier-invoices', [SupplierInvoiceController::class, 'index'])->name('supplier-invoices.index');
        Route::get('/supplier-invoices/create', [SupplierInvoiceController::class, 'create'])->name('supplier-invoices.create');
        Route::post('/supplier-invoices', [SupplierInvoiceController::class, 'store'])->name('supplier-invoices.store');
        Route::get('/supplier-invoices/{supplierInvoice}/edit', [SupplierInvoiceController::class, 'edit'])->name('supplier-invoices.edit');
        Route::put('/supplier-invoices/{supplierInvoice}', [SupplierInvoiceController::class, 'update'])->name('supplier-invoices.update');
        Route::delete('/supplier-invoices/{supplierInvoice}', [SupplierInvoiceController::class, 'destroy'])->name('supplier-invoices.destroy');
    });

    Route::middleware('permission:manage-supplier-payments')->group(function () {
        Route::get('/supplier-payments', [SupplierPaymentController::class, 'index'])->name('supplier-payments.index');
        Route::get('/supplier-payments/create', [SupplierPaymentController::class, 'create'])->name('supplier-payments.create');
        Route::post('/supplier-payments', [SupplierPaymentController::class, 'store'])->name('supplier-payments.store');
        Route::get('/supplier-payments/{supplierPayment}/edit', [SupplierPaymentController::class, 'edit'])->name('supplier-payments.edit');
        Route::put('/supplier-payments/{supplierPayment}', [SupplierPaymentController::class, 'update'])->name('supplier-payments.update');
        Route::delete('/supplier-payments/{supplierPayment}', [SupplierPaymentController::class, 'destroy'])->name('supplier-payments.destroy');
    });

    Route::middleware('permission:view-supplier-statements')->group(function () {
        Route::get('/supplier-statements', [SupplierStatementController::class, 'index'])->name('supplier-statements.index');
    });

    Route::middleware('permission:manage-inventory-items')->group(function () {
        Route::get('/inventory-items', [InventoryItemController::class, 'index'])->name('inventory-items.index');
        Route::get('/inventory-items/create', [InventoryItemController::class, 'create'])->name('inventory-items.create');
        Route::post('/inventory-items', [InventoryItemController::class, 'store'])->name('inventory-items.store');
        Route::get('/inventory-items/{inventoryItem}/edit', [InventoryItemController::class, 'edit'])->name('inventory-items.edit');
        Route::put('/inventory-items/{inventoryItem}', [InventoryItemController::class, 'update'])->name('inventory-items.update');
        Route::delete('/inventory-items/{inventoryItem}', [InventoryItemController::class, 'destroy'])->name('inventory-items.destroy');
    });

    Route::middleware('permission:manage-stock-movements')->group(function () {
        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
        Route::get('/stock-movements/create', [StockMovementController::class, 'create'])->name('stock-movements.create');
        Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
    });

    Route::middleware('permission:view-inventory-reports')->group(function () {
        Route::get('/inventory-reports/stock-summary', [InventoryReportController::class, 'stockSummary'])->name('inventory-reports.stock-summary');
        Route::get('/inventory-reports/low-stock', [InventoryReportController::class, 'lowStock'])->name('inventory-reports.low-stock');
        Route::get('/inventory-reports/movement-report', [InventoryReportController::class, 'movementReport'])->name('inventory-reports.movement-report');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});