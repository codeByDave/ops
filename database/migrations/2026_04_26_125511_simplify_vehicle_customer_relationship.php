<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->after('company_id')
                    ->constrained('customers')
                    ->restrictOnDelete();
            }
        });

        if (Schema::hasTable('customer_vehicle')) {
            $rows = DB::table('customer_vehicle')->get();

            foreach ($rows as $row) {
                DB::table('vehicles')
                    ->where('id', $row->vehicle_id)
                    ->whereNull('customer_id')
                    ->update([
                        'customer_id' => $row->customer_id,
                    ]);
            }

            Schema::dropIfExists('customer_vehicle');
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('notes');
            }
        });

        if (!Schema::hasTable('customer_vehicle')) {
            Schema::create('customer_vehicle', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->constrained()->cascadeOnDelete();
                $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
            });
        }

        Schema::table('vehicles', function (Blueprint $table) {
            if (Schema::hasColumn('vehicles', 'customer_id')) {
                $table->dropConstrainedForeignId('customer_id');
            }
        });
    }
};