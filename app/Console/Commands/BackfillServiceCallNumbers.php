<?php

namespace App\Console\Commands;

use App\Models\ServiceCall;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillServiceCallNumbers extends Command
{
    protected $signature = 'service-calls:backfill-numbers';

    protected $description = 'Backfill service call numbers using created_at date only';

    public function handle(): int
    {
        DB::transaction(function () {
            $serviceCalls = ServiceCall::query()
                ->whereNull('service_call_number')
                ->whereNotNull('created_at')
                ->orderBy('created_at')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $dailySequences = [];

            foreach ($serviceCalls as $serviceCall) {
                $date = $serviceCall->created_at->format('Ymd');

                $dailySequences[$date] = ($dailySequences[$date] ?? 0) + 1;

                $sequence = str_pad($dailySequences[$date], 3, '0', STR_PAD_LEFT);

                $serviceCall->update([
                    'service_call_number' => "SC-{$date}-{$sequence}",
                ]);
            }
        });

        $this->info('Service call numbers backfilled successfully.');

        return self::SUCCESS;
    }
}
