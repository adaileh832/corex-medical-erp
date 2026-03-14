<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->string('job_title');
            $table->string('employment_type', 50);
            $table->decimal('monthly_salary', 12, 2)->default(0);
            $table->decimal('daily_wage', 12, 2)->default(0);
            $table->date('hire_date');
            $table->string('phone')->nullable();
            $table->string('identity_number')->nullable();
            $table->string('address', 500)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};