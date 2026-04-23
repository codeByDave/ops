<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = DB::table('companies')->value('id');

        DB::table('lookup_types')->insert([
            [
                'company_id' => $companyId,
                'name' => 'Service Call Status',
                'code' => 'service_call_status',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'name' => 'Service Type',
                'code' => 'service_type',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}