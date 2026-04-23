<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = DB::table('companies')->value('id');

        $serviceCallStatusTypeId = DB::table('lookup_types')
            ->where('code', 'service_call_status')
            ->value('id');

        $serviceTypeTypeId = DB::table('lookup_types')
            ->where('code', 'service_type')
            ->value('id');

        $customerTypeTypeId = DB::table('lookup_types')
            ->where('code', 'customer_type')
            ->value('id');

        DB::table('lookup_values')->insert([
            // Service Call Statuses
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'New',
                'code' => 'new',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'Scheduled',
                'code' => 'scheduled',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'Dispatched',
                'code' => 'dispatched',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'En Route',
                'code' => 'en_route',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'On Scene',
                'code' => 'on_scene',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'Completed',
                'code' => 'completed',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'GOA',
                'code' => 'goa',
                'sort_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceCallStatusTypeId,
                'parent_id' => null,
                'name' => 'Cancelled',
                'code' => 'cancelled',
                'sort_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Service Types
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Jump Start',
                'code' => 'jump_start',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Battery Replacement',
                'code' => 'battery_replacement',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Flat Tire',
                'code' => 'flat_tire',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Fuel Delivery',
                'code' => 'fuel_delivery',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Lockout',
                'code' => 'lockout',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $serviceTypeTypeId,
                'parent_id' => null,
                'name' => 'Mobile EV Charging',
                'code' => 'mobile_ev_charging',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Customer Types
            [
                'company_id' => $companyId,
                'lookup_type_id' => $customerTypeTypeId,
                'parent_id' => null,
                'name' => 'Consumer',
                'code' => 'consumer',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $customerTypeTypeId,
                'parent_id' => null,
                'name' => 'Business',
                'code' => 'business',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $customerTypeTypeId,
                'parent_id' => null,
                'name' => 'Motor Club',
                'code' => 'motor_club',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $companyId,
                'lookup_type_id' => $customerTypeTypeId,
                'parent_id' => null,
                'name' => 'Insurance',
                'code' => 'insurance',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}