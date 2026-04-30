@extends('layouts.contentNavbarLayout')

@section('title', 'Service Call')

@section('content')
  @php
    $address = collect([$serviceCall->address_1, $serviceCall->city, $serviceCall->state, $serviceCall->postal_code])
        ->filter()
        ->implode(', ');

    $phoneDigits = preg_replace('/\D+/', '', $serviceCall->customer_mobile_phone ?? '');
    $hasPhone = strlen($phoneDigits) >= 10;

    $currentStatusCode = $serviceCall->status?->code;
    $currentStatusName = $serviceCall->status?->name ?? 'Unknown';

    $statusBadgeClass = match ($currentStatusCode) {
        'new' => 'bg-label-secondary',
        'scheduled' => 'bg-label-info',
        'dispatched' => 'bg-label-primary',
        'en_route' => 'bg-label-warning',
        'on_scene' => 'bg-label-dark',
        'completed' => 'bg-label-success',
        'goa' => 'bg-label-danger',
        'cancelled' => 'bg-label-danger',
        default => 'bg-label-secondary',
    };

    $nextStatusCode = match ($currentStatusCode) {
        'new' => 'dispatched',
        'scheduled' => 'dispatched',
        'dispatched' => 'en_route',
        'en_route' => 'on_scene',
        'on_scene' => 'completed',
        default => null,
    };

    $nextStatusLabel = match ($nextStatusCode) {
        'dispatched' => 'Mark Dispatched',
        'en_route' => 'Mark En Route',
        'on_scene' => 'Mark On Scene',
        'completed' => 'Mark Completed',
        default => null,
    };

    $hasCoordinates = !empty($serviceCall->latitude) && !empty($serviceCall->longitude);
    $destination = $hasCoordinates ? $serviceCall->latitude . ',' . $serviceCall->longitude : $address;

    $encodedDestination = urlencode($destination);

    $appleMapsUrl = 'https://maps.apple.com/?daddr=' . $encodedDestination;
    $googleMapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . $encodedDestination;

    $wazeUrl = $hasCoordinates
        ? 'https://waze.com/ul?ll=' . $encodedDestination . '&navigate=yes'
        : 'https://waze.com/ul?q=' . $encodedDestination . '&navigate=yes';

    $showAddPhotos = in_array($currentStatusCode, ['on_scene', 'completed', 'goa'], true);
  @endphp

  <div class="card mb-3">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
        <div>
          <h4 class="mb-1">{{ $serviceCall->customer_name ?? 'Unknown Customer' }}</h4>
          <div class="text-muted">{{ $serviceCall->serviceType?->name ?? 'No service type' }}</div>
        </div>

        <span class="badge {{ $statusBadgeClass }}">
          {{ $currentStatusName }}
        </span>
      </div>

      <div class="d-grid gap-2 mb-3">
        @if ($hasPhone)
          <a href="tel:{{ $phoneDigits }}" class="btn btn-primary btn-lg">
            <i class="bx bx-phone-call me-1"></i> Call Customer
          </a>
        @else
          <button type="button" class="btn btn-outline-secondary btn-lg" disabled>
            <i class="bx bx-phone-call me-1"></i> No Phone
          </button>
        @endif

        @if ($destination)
          <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal"
            data-bs-target="#navigationChoiceModal">
            <i class="bx bx-map me-1"></i> Navigate
          </button>
        @else
          <button type="button" class="btn btn-outline-secondary btn-lg" disabled>
            <i class="bx bx-map me-1"></i> No Address
          </button>
        @endif

        @if ($nextStatusCode)
          <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
            @csrf
            @method('PATCH')

            <input type="hidden" name="status_code" value="{{ $nextStatusCode }}">

            <button type="submit" class="btn btn-success btn-lg w-100">
              <i class="bx bx-chevron-right-circle me-1"></i>
              {{ $nextStatusLabel }}
            </button>
          </form>
        @endif

        @if ($showAddPhotos)
          <button type="button" class="btn btn-outline-secondary btn-lg" disabled>
            <i class="bx bx-camera me-1"></i> Add Photos
          </button>
        @endif
      </div>
    </div>
  </div>

  <div class="card mb-3">
    <div class="card-header">
      <h5 class="mb-0">Call Details</h5>
    </div>

    <div class="card-body">
      <div class="mb-3">
        <small class="text-muted d-block">Address</small>
        <div>{{ $address ?: '—' }}</div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Vehicle</small>
        <div>{{ $serviceCall->vehicle_label ?? '—' }}</div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Driver</small>
        <div>{{ $serviceCall->assignedUser?->name ?? 'Unassigned' }}</div>
      </div>

      <div class="mb-3">
        <small class="text-muted d-block">Truck</small>
        <div>
          {{ $serviceCall->companyVehicle?->description ?? 'Unassigned' }}
          @if ($serviceCall->companyVehicle?->plate_number)
            - {{ $serviceCall->companyVehicle->plate_number }}
          @endif
        </div>
      </div>

      <div>
        <small class="text-muted d-block">Notes</small>
        <div>{{ $serviceCall->notes ?? '—' }}</div>
      </div>
    </div>
  </div>

  <div class="d-grid gap-2">
    <a href="{{ route('dispatch-board.index') }}" class="btn btn-outline-secondary">
      Back to Dispatch Board
    </a>
  </div>

  {{-- Navigation Choice Modal --}}
  <div class="modal fade" id="navigationChoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Choose Navigation App</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <p class="text-muted small mb-3">
            {{ $destination ?: 'No destination found' }}
          </p>

          <div class="d-grid gap-2">
            <a href="{{ $appleMapsUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
              Apple Maps
            </a>

            <a href="{{ $googleMapsUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
              Google Maps
            </a>

            <a href="{{ $wazeUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary">
              Waze
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
