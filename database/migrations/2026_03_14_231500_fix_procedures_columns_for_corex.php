<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('procedures')) {
            Schema::create('procedures', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->nullable();
                $table->decimal('price', 12, 2)->default(0);
                $table->unsignedInteger('duration_minutes')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            return;
        }

        Schema::table('procedures', function (Blueprint $table) {
            if (! Schema::hasColumn('procedures', 'name') && ! Schema::hasColumn('procedures', 'title')) {
                $table->string('name')->nullable()->after('id');
            }

            if (! Schema::hasColumn('procedures', 'code')) {
                $table->string('code')->nullable();
            }

            if (! Schema::hasColumn('procedures', 'price')) {
                $table->decimal('price', 12, 2)->default(0);
            }

            if (! Schema::hasColumn('procedures', 'duration_minutes')) {
                $table->unsignedInteger('duration_minutes')->nullable();
            }

            if (! Schema::hasColumn('procedures', 'description')) {
                $table->text('description')->nullable();
            }

            if (! Schema::hasColumn('procedures', 'is_active') && ! Schema::hasColumn('procedures', 'status')) {
                $table->boolean('is_active')->default(true);
            }

            if (! Schema::hasColumn('procedures', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }

            if (! Schema::hasColumn('procedures', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        if (Schema::hasColumn('procedures', 'title') && Schema::hasColumn('procedures', 'name')) {
            DB::table('procedures')
                ->whereNull('name')
                ->orWhere('name', '')
                ->update([
                    'name' => DB::raw('title'),
                ]);
        }
    }

    public function down(): void
    {
        //
    }
};
