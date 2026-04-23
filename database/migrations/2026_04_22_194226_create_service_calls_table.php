<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();

            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('assigned_company_vehicle_id')
                ->nullable()
                ->constrained('company_vehicles')
                ->nullOnDelete();

            $table->foreignId('service_type_id')
                ->nullable()
                ->constrained('lookup_values')
                ->nullOnDelete();

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('lookup_values')
                ->nullOnDelete();

            $table->string('po_number')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_mobile_phone')->nullable();

            $table->string('address_1')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('vehicle_label')->nullable();

            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('dispatched_at')->nullable();
            $table->timestamp('enroute_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_calls');
    }
};