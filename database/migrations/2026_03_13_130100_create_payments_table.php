<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            return;
        }

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('invoice_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};