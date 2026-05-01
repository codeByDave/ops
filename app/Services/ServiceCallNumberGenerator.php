<?php

namespace App\Services;

use App\Models\ServiceCall;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class ServiceCallNumberGenerator
{
  public function create(array $data): ServiceCall
  {
    $attempts = 0;

    while ($attempts < 3) {
      try {
        return DB::transaction(function () use ($data) {
          $data['service_call_number'] = $this->generateNumber();

          return ServiceCall::create($data);
        });
      } catch (QueryException $e) {
        $attempts++;

        if (! str_contains($e->getMessage(), 'Duplicate entry')) {
          throw $e;
        }
      }
    }

    throw new \RuntimeException('Unable to generate a unique service call number.');
  }

  private function generateNumber(): string
  {
    $today = now()->toDateString();
    $date = now()->format('Ymd');

    $lastServiceCall = ServiceCall::query()
      ->whereDate('created_at', $today)
      ->whereNotNull('service_call_number')
      ->lockForUpdate()
      ->orderByDesc('service_call_number')
      ->first();

    $nextSequence = 1;

    if ($lastServiceCall) {
      $nextSequence = ((int) substr($lastServiceCall->service_call_number, -3)) + 1;
    }

    $sequence = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

    return "SC-{$date}-{$sequence}";
  }
}
