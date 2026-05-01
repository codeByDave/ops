<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_calls', function (Blueprint $table) {
            $table->string('service_call_number')
                ->nullable()
                ->unique()
                ->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('service_calls', function (Blueprint $table) {
            $table->dropUnique(['service_call_number']);
            $table->dropColumn('service_call_number');
        });
    }
};
