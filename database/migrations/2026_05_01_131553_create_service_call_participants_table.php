<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_call_participants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('service_call_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('role')->default('service_customer');
            // examples:
            // service_customer
            // driver
            // passenger

            $table->timestamps();

            $table->unique([
                'service_call_id',
                'customer_id',
                'role'
            ], 'scp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_call_participants');
    }
};
