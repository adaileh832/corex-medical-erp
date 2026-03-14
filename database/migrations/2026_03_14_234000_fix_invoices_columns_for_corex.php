<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('discount', 12, 2)->default(0);
                $table->decimal('tax', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->string('status')->default('unpaid');
                $table->string('payment_method')->nullable();
                $table->string('currency_code')->default('JOD');
                $table->text('notes')->nullable();
                $table->timestamps();

                return;
            });
        }

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'invoice_number')) {
                $table->string('invoice_number')->nullable()->after('id');
            }

            if (! Schema::hasColumn('invoices', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->after('invoice_number')->constrained('patients')->nullOnDelete();
            }

            if (! Schema::hasColumn('invoices', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'discount')) {
                $table->decimal('discount', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'tax')) {
                $table->decimal('tax', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'total')) {
                $table->decimal('total', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('invoices', 'status')) {
                $table->string('status')->default('unpaid');
            }

            if (! Schema::hasColumn('invoices', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (! Schema::hasColumn('invoices', 'currency_code')) {
                $table->string('currency_code')->default('JOD');
            }

            if (! Schema::hasColumn('invoices', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (! Schema::hasColumn('invoices', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('invoices', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};
