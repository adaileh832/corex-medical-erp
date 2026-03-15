<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('invoice_items')) {
            return;
        }

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('procedure_id')->nullable();

            $table->string('procedure_name')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('line_total', 12, 2)->default(0);

            $table->timestamps();

            $table->index('invoice_id');
            $table->index('procedure_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};