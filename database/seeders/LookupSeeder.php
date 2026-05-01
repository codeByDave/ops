<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = 1;
        $now = now();

        DB::table('companies')->updateOrInsert(
            ['id' => $companyId],
            [
                'name' => 'Roadside Responder',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        $types = [
            'service_call_status' => 'Service Call Status',
            'service_type' => 'Service Type',
            'customer_type' => 'Customer Type',
        ];

        foreach ($types as $code => $name) {
            DB::table('lookup_types')->updateOrInsert(
                [
                    'company_id' => $companyId,
                    'code' => $code,
                ],
                [
                    'name' => $name,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }

        $typeIds = DB::table('lookup_types')
            ->where('company_id', $companyId)
            ->pluck('id', 'code');

        $values = [
            'service_call_status' => [
                ['New', 'new', 10],
                ['Scheduled', 'scheduled', 20],
                ['Dispatched', 'dispatched', 30],
                ['En Route', 'en_route', 40],
                ['On Scene', 'on_scene', 50],
                ['Completed', 'completed', 60],
                ['GOA', 'goa', 70],
                ['Cancelled', 'cancelled', 80],
            ],

            'service_type' => [
                ['Jump Start', 'jump_start', 10],
                ['Battery Replacement', 'battery_replacement', 20],
                ['Flat Tire', 'flat_tire', 30],
                ['Fuel Delivery', 'fuel_delivery', 40],
                ['Lockout', 'lockout', 50],
                ['Mobile EV Charging', 'mobile_ev_charging', 60],
            ],

            'customer_type' => [
                ['Consumer', 'consumer', 10],
                ['Business', 'business', 20],
                ['Motor Club', 'motor_club', 30],
                ['Insurance', 'insurance', 40],
            ],
        ];

        foreach ($values as $typeCode => $items) {
            foreach ($items as [$name, $code, $sortOrder]) {
                DB::table('lookup_values')->updateOrInsert(
                    [
                        'lookup_type_id' => $typeIds[$typeCode],
                        'code' => $code,
                    ],
                    [
                        'company_id' => $companyId,
                        'name' => $name,
                        'sort_order' => $sortOrder,
                        'is_active' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }
}
