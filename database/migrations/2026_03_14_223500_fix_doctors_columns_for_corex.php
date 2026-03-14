<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('doctors')) {
            Schema::create('doctors', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('full_name')->nullable();
                $table->string('national_id', 10)->nullable();
                $table->string('specialty')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('license_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('doctors', function (Blueprint $table) {
            if (! Schema::hasColumn('doctors', 'full_name')) {
                $table->string('full_name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('doctors', 'national_id')) {
                $table->string('national_id', 10)->nullable()->after('full_name');
            }

            if (! Schema::hasColumn('doctors', 'specialty')) {
                $table->string('specialty')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'phone')) {
                $table->string('phone')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'email')) {
                $table->string('email')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'license_number')) {
                $table->string('license_number')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('doctors', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (Schema::hasColumn('doctors', 'name') && Schema::hasColumn('doctors', 'full_name')) {
            DB::table('doctors')
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