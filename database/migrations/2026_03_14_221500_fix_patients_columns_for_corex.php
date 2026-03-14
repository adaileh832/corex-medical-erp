<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('patients')) {
            Schema::create('patients', function (Blueprint $table) {
                $table->id();
                $table->string('full_name');
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('gender')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('address')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('patients', function (Blueprint $table) {
            if (! Schema::hasColumn('patients', 'full_name')) {
                $table->string('full_name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('patients', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('patients', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('patients', 'gender')) {
                $table->string('gender')->nullable();
            }

            if (! Schema::hasColumn('patients', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }

            if (! Schema::hasColumn('patients', 'address')) {
                $table->string('address')->nullable();
            }

            if (! Schema::hasColumn('patients', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (! Schema::hasColumn('patients', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('patients', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (Schema::hasColumn('patients', 'name') && Schema::hasColumn('patients', 'full_name')) {
            DB::table('patients')
                ->whereNull('full_name')
                ->orWhere('full_name', '')
                ->update([
                    'full_name' => DB::raw('name'),
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};