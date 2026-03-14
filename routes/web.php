<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AccountingReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\CashVoucherController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorPaymentController;
use App\Http\Controllers\DoctorStatementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FinancialStatementController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PayrollController;
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
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/locale/{locale}', [LocaleController::class, 'switch'])->name('locale.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::prefix('setup')->name('setup.')->group(function () {
        Route::get('/manager', [SetupController::class, 'createManager'])->name('manager');
        Route::post('/manager', [SetupController::class, 'storeManager'])->name('manager.store');
    });

    // Legacy and friendly aliases for first-time manager setup.
    Route::redirect('/create-system-manager', '/setup/manager', 302)->name('setup.manager.alias');
    Route::redirect('/register-manager', '/setup/manager', 302)->name('setup.manager.legacy');
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

    Route::middleware('permission:manage-employees')->group(function () {
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    Route::middleware('permission:manage-attendance')->group(function () {
        Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
        Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    });

    Route::middleware('permission:manage-leave-requests')->group(function () {
        Route::get('/leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
        Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])->name('leave-requests.create');
        Route::post('/leave-requests', [LeaveRequestController::class, 'store'])->name('leave-requests.store');
        Route::get('/leave-requests/{leaveRequest}/edit', [LeaveRequestController::class, 'edit'])->name('leave-requests.edit');
        Route::put('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'update'])->name('leave-requests.update');
        Route::delete('/leave-requests/{leaveRequest}', [LeaveRequestController::class, 'destroy'])->name('leave-requests.destroy');
    });

    Route::middleware('permission:view-attendance-reports')->group(function () {
        Route::get('/attendance-reports/monthly', [AttendanceReportController::class, 'monthly'])->name('attendance-reports.monthly');
        Route::get('/attendance-reports/summary', [AttendanceReportController::class, 'summary'])->name('attendance-reports.summary');
    });

    Route::middleware('permission:view-payroll')->group(function () {
        Route::get('/payrolls', [PayrollController::class, 'index'])->name('payrolls.index');
        Route::get('/payrolls/{payroll}', [PayrollController::class, 'show'])->name('payrolls.show');
        Route::get('/payroll-items/{payrollItem}/payslip', [PayrollController::class, 'payslip'])->name('payrolls.payslip');
    });

    Route::middleware('permission:manage-payroll')->group(function () {
        Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
        Route::post('/payrolls', [PayrollController::class, 'store'])->name('payrolls.store');
    });

    Route::middleware('permission:manage-accounts')->group(function () {
        Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::get('/accounts/create', [AccountController::class, 'create'])->name('accounts.create');
        Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::get('/accounts/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
        Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    });

    Route::middleware('permission:manage-journal-entries')->group(function () {
        Route::get('/journal-entries', [JournalEntryController::class, 'index'])->name('journal-entries.index');
        Route::get('/journal-entries/create', [JournalEntryController::class, 'create'])->name('journal-entries.create');
        Route::post('/journal-entries', [JournalEntryController::class, 'store'])->name('journal-entries.store');
        Route::get('/journal-entries/{journalEntry}', [JournalEntryController::class, 'show'])->name('journal-entries.show');
    });

    Route::middleware('permission:manage-cash-vouchers')->group(function () {
        Route::get('/cash-vouchers', [CashVoucherController::class, 'index'])->name('cash-vouchers.index');
        Route::get('/cash-vouchers/create', [CashVoucherController::class, 'create'])->name('cash-vouchers.create');
        Route::post('/cash-vouchers', [CashVoucherController::class, 'store'])->name('cash-vouchers.store');
    });

    Route::middleware('permission:manage-bank-transactions')->group(function () {
        Route::get('/bank-transactions', [BankTransactionController::class, 'index'])->name('bank-transactions.index');
        Route::get('/bank-transactions/create', [BankTransactionController::class, 'create'])->name('bank-transactions.create');
        Route::post('/bank-transactions', [BankTransactionController::class, 'store'])->name('bank-transactions.store');
    });

    Route::middleware('permission:view-accounting-reports')->group(function () {
        Route::get('/accounting-reports/ledger', [AccountingReportController::class, 'ledger'])->name('accounting-reports.ledger');
        Route::get('/accounting-reports/trial-balance', [AccountingReportController::class, 'trialBalance'])->name('accounting-reports.trial-balance');
        Route::get('/financial-statements/income-statement', [FinancialStatementController::class, 'incomeStatement'])->name('financial-statements.income-statement');
        Route::get('/financial-statements/balance-sheet', [FinancialStatementController::class, 'balanceSheet'])->name('financial-statements.balance-sheet');
        Route::get('/financial-statements/revenue-report', [FinancialStatementController::class, 'revenueReport'])->name('financial-statements.revenue-report');
        Route::get('/financial-statements/expense-report', [FinancialStatementController::class, 'expenseReport'])->name('financial-statements.expense-report');
        Route::get('/financial-statements/cash-movement', [FinancialStatementController::class, 'cashMovement'])->name('financial-statements.cash-movement');
        Route::get('/financial-statements/bank-movement', [FinancialStatementController::class, 'bankMovement'])->name('financial-statements.bank-movement');
    });

    Route::middleware('role:manager')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });
});