<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('doctor_payments')) {
            Schema::create('doctor_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('doctor_id');
                $table->decimal('amount', 12, 2)->default(0);
                $table->string('payment_method')->nullable();
                $table->dateTime('payment_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('doctor_id');
            });

            return;
        }

        Schema::table('doctor_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('doctor_payments', 'doctor_id')) {
                $table->unsignedBigInteger('doctor_id')->nullable()->after('id');
            }

            if (! Schema::hasColumn('doctor_payments', 'amount')) {
                $table->decimal('amount', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('doctor_payments', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (! Schema::hasColumn('doctor_payments', 'payment_date')) {
                $table->dateTime('payment_date')->nullable();
            }

            if (! Schema::hasColumn('doctor_payments', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (! Schema::hasColumn('doctor_payments', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('doctor_payments', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        //
    }
};