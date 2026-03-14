<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Employee;
use App\Models\InventoryItem;
use App\Models\Invoice;
use App\Models\Operation;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            [
                'label' => __('app.total_patients'),
                'value' => Patient::count(),
                'icon' => '🧑‍⚕️',
                'tone' => 'primary',
                'description' => __('app.patients'),
            ],
            [
                'label' => __('app.total_doctors'),
                'value' => Doctor::count(),
                'icon' => '👨‍⚕️',
                'tone' => 'success',
                'description' => __('app.doctors'),
            ],
            [
                'label' => __('app.total_invoices'),
                'value' => Invoice::count(),
                'icon' => '🧾',
                'tone' => 'warning',
                'description' => __('app.invoices'),
            ],
            [
                'label' => __('app.total_operations'),
                'value' => Operation::count(),
                'icon' => '🏥',
                'tone' => 'danger',
                'description' => __('app.operations'),
            ],
            [
                'label' => __('app.total_employees'),
                'value' => Employee::count(),
                'icon' => '👥',
                'tone' => 'info',
                'description' => __('app.employees'),
            ],
            [
                'label' => __('app.total_inventory_items'),
                'value' => InventoryItem::count(),
                'icon' => '📦',
                'tone' => 'secondary',
                'description' => __('app.inventory'),
            ],
        ];

        $systemOverview = [
            [
                'label' => __('app.total_users'),
                'value' => User::count(),
            ],
            [
                'label' => __('app.total_roles'),
                'value' => Role::count(),
            ],
            [
                'label' => __('app.total_permissions'),
                'value' => Permission::count(),
            ],
            [
                'label' => __('app.total_suppliers'),
                'value' => Supplier::count(),
            ],
        ];

        $quickActions = [
            [
                'label' => __('app.add_patient'),
                'route' => route('patients.create'),
                'permission' => 'manage-patients',
            ],
            [
                'label' => __('app.add_doctor'),
                'route' => route('doctors.create'),
                'permission' => 'manage-doctors',
            ],
            [
                'label' => __('app.create_invoice'),
                'route' => route('invoices.create'),
                'permission' => 'manage-invoices',
            ],
            [
                'label' => __('app.schedule_operation'),
                'route' => route('operations.create'),
                'permission' => 'manage-operations',
            ],
            [
                'label' => __('app.add_supplier'),
                'route' => route('suppliers.create'),
                'permission' => 'manage-suppliers',
            ],
            [
                'label' => __('app.add_inventory_item'),
                'route' => route('inventory-items.create'),
                'permission' => 'manage-inventory-items',
            ],
        ];

        $modules = [
            [
                'title' => __('app.patient_management'),
                'description' => __('app.patients') . ' / ' . __('app.doctors') . ' / ' . __('app.operations'),
            ],
            [
                'title' => __('app.billing_and_finance'),
                'description' => __('app.invoices') . ' / ' . __('app.payments') . ' / ' . __('app.accounting'),
            ],
            [
                'title' => __('app.hr_management'),
                'description' => __('app.employees') . ' / ' . __('app.attendance') . ' / ' . __('app.payroll'),
            ],
            [
                'title' => __('app.inventory_management'),
                'description' => __('app.inventory') . ' / ' . __('app.suppliers'),
            ],
        ];

        return view('dashboard.index', [
            'stats' => $stats,
            'systemOverview' => $systemOverview,
            'quickActions' => $quickActions,
            'modules' => $modules,
        ]);
    }
}
