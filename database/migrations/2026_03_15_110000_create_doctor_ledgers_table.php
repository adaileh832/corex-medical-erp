<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('doctor_ledgers')) {
            return;
        }

        Schema::create('doctor_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id');
            $table->string('reference')->nullable();
            $table->string('description')->nullable();
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->date('entry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('doctor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_ledgers');
    }
};