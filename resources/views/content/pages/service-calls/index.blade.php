@extends('layouts.contentNavbarLayout')

@section('title', 'Dispatch Board')

@section('content')

  <style>
    .dispatch-filter-bar {
      position: sticky;
      top: 0;
      z-index: 20;
      background: #fff;
      padding: .75rem 0;
    }

    .dispatch-metric-card {
      border: 1px solid #e5e7eb;
      border-radius: .75rem;
    }

    .dispatch-sortable {
      cursor: pointer;
      user-select: none;
    }

    .dispatch-mobile-card {
      border: 1px solid #e5e7eb;
      border-radius: 1rem;
      padding: 1rem;
      margin-bottom: .85rem;
      background: #fff;
      box-shadow: 0 4px 14px rgba(0, 0, 0, .04);
    }

    .dispatch-mobile-card h5 {
      font-size: 1.05rem;
      font-weight: 600;
    }

    .dispatch-mobile-meta {
      font-size: .875rem;
      color: #6b7280;
      line-height: 1.35;
    }

    .dispatch-mobile-actions .btn {
      min-height: 42px;
      border-radius: .75rem;
      font-weight: 500;
    }

    .dispatch-mobile-actions .btn-primary {
      box-shadow: 0 4px 10px rgba(105, 108, 255, .25);
    }

    .dispatch-mobile-secondary-actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: .9rem;
      padding-top: .6rem;
      margin-top: .25rem;
      border-top: 1px solid #eef0f4;
      font-size: .875rem;
    }

    .dispatch-mobile-secondary-actions a,
    .dispatch-mobile-secondary-actions button {
      border: 0;
      background: transparent;
      padding: 0;
      color: #6b7280;
      text-decoration: none;
      font-size: .875rem;
    }

    .dispatch-mobile-secondary-actions a:hover,
    .dispatch-mobile-secondary-actions button:hover {
      color: var(--bs-primary);
    }

    .dispatch-mobile-secondary-actions .text-danger {
      color: #ff3e1d !important;
    }

    .dispatch-mobile-secondary-actions .text-warning {
      color: #ffab00 !important;
    }

    @media (max-width: 767.98px) {
      .dispatch-filter-bar {
        padding-top: .25rem;
        padding-bottom: .5rem;
      }

      .dispatch-filter-bar .form-label {
        display: none;
      }

      .dispatch-filter-bar .row {
        --bs-gutter-y: .5rem;
      }

      .dispatch-filter-bar .col-md-4,
      .dispatch-filter-bar .col-md-3,
      .dispatch-filter-bar .col-md-2 {
        width: 50%;
      }

      .dispatch-filter-bar .col-md-4 {
        width: 100%;
      }
    }

    @media (max-width: 991.98px) {
      .dispatch-mobile-bottom-padding {
        padding-bottom: 92px;
      }

      .dispatch-mobile-bottom-nav {
        position: fixed;
        left: 12px;
        right: 12px;
        bottom: 12px;
        z-index: 1030;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 1.25rem;
        box-shadow: 0 8px 28px rgba(0, 0, 0, .16);
        overflow: hidden;
      }

      .dispatch-mobile-bottom-nav .btn {
        border: 0;
        border-radius: 0;
        padding: .7rem .25rem;
        font-size: .72rem;
        color: #6b7280;
        background: #fff;
      }

      .dispatch-mobile-bottom-nav i {
        display: block;
        font-size: 1.4rem;
        margin-bottom: .15rem;
      }

      .dispatch-mobile-bottom-nav .active {
        color: var(--bs-primary);
        font-weight: 700;
        background: #f4f5ff;
      }
    }
  </style>

  <div class="card">

    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Dispatch Board</h5>

      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServiceCallModal">
        New Service Call
      </button>
    </div>

    <div class="card-body">

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->has('assignment'))
        <div class="alert alert-danger">
          {{ $errors->first('assignment') }}
        </div>
      @endif

      @php
        $closedStatuses = ['completed', 'cancelled', 'goa'];

        $activeCalls = $serviceCalls->filter(function ($call) use ($closedStatuses) {
            return !in_array($call->status?->code, $closedStatuses, true);
        });

        $unassignedCount = $activeCalls->whereNull('assigned_user_id')->count();
      @endphp

      {{-- Metrics --}}
      <div class="row g-3 mb-4 d-none d-md-flex">

        <div class="col-md-3">
          <div class="card dispatch-metric-card">
            <div class="card-body py-3">
              <small class="text-muted d-block">Active Jobs</small>
              <h4 class="mb-0">{{ $activeCalls->count() }}</h4>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card dispatch-metric-card">
            <div class="card-body py-3">
              <small class="text-muted d-block">Unassigned</small>
              <h4 class="mb-0">{{ $unassignedCount }}</h4>
            </div>
          </div>
        </div>

        @foreach ($drivers->take(2) as $driver)
          @php
            $driverName = trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: $driver->email;
            $driverJobs = $activeCalls->where('assigned_user_id', $driver->id)->count();
          @endphp

          <div class="col-md-3">
            <div class="card dispatch-metric-card">
              <div class="card-body py-3">
                <small class="text-muted d-block">{{ $driverName }}</small>
                <h4 class="mb-0">{{ $driverJobs }}</h4>
              </div>
            </div>
          </div>
        @endforeach

      </div>

      {{-- Filters --}}
      <div class="dispatch-filter-bar">

        <div class="row g-3 mb-4">

          <div class="col-md-4">
            <label class="form-label">Search</label>

            <input type="text" class="form-control" id="dispatchSearch" placeholder="Customer, address, driver...">
          </div>

          <div class="col-md-3">
            <label class="form-label">Status</label>

            <select class="form-select" id="dispatchStatusFilter">
              <option value="active" selected>Active Jobs</option>
              <option value="all">All Jobs</option>

              @foreach ($serviceCallStatuses as $status)
                <option value="{{ $status->code }}">
                  {{ $status->name }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3">
            <label class="form-label">Driver</label>

            <select class="form-select" id="dispatchDriverFilter">
              <option value="all" selected>All Drivers</option>
              <option value="unassigned">Unassigned</option>

              @foreach ($drivers as $driver)
                <option value="{{ $driver->id }}">
                  {{ trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: $driver->email }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-outline-secondary w-100" id="dispatchClearFilters">
              Clear
            </button>
          </div>

        </div>

      </div>

      @if ($serviceCalls->isEmpty())

        <p class="mb-0">No service calls found.</p>
      @else
        {{-- Desktop / Tablet Table --}}
        <div class="table-responsive d-none d-lg-block">

          <table class="table table-bordered table-striped align-middle" id="dispatchBoardTable">

            <thead>
              <tr>
                <th class="dispatch-sortable" data-sort="created">Created</th>
                <th class="dispatch-sortable" data-sort="service">Service Type</th>
                <th class="dispatch-sortable" data-sort="customer">Customer</th>
                <th class="dispatch-sortable" data-sort="address">Address</th>
                <th class="dispatch-sortable" data-sort="driver">Driver</th>
                <th class="dispatch-sortable" data-sort="status">Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>

            <tbody>

              @foreach ($serviceCalls as $serviceCall)
                @php
                  $address = collect([
                      $serviceCall->address_1,
                      $serviceCall->city,
                      $serviceCall->state,
                      $serviceCall->postal_code,
                  ])
                      ->filter()
                      ->implode(', ');

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

                  $driverName = $serviceCall->assignedUser?->name ?? '';

                  $createdDisplay = optional($serviceCall->created_at)?->format('m/d h:i A') ?? '—';

                  $phoneDigits = preg_replace('/\D+/', '', $serviceCall->customer_mobile_phone ?? '');
                  $hasPhone = strlen($phoneDigits) >= 10;

                  $hasNavigation =
                      !empty($address) || (!empty($serviceCall->latitude) && !empty($serviceCall->longitude));
                @endphp

                <tr data-dispatch-row="1" data-status-code="{{ $currentStatusCode }}"
                  data-driver-id="{{ $serviceCall->assigned_user_id ?? '' }}"
                  data-created="{{ optional($serviceCall->created_at)?->timestamp ?? 0 }}"
                  data-service="{{ strtolower($serviceCall->serviceType?->name ?? '') }}"
                  data-customer="{{ strtolower($serviceCall->customer_name ?? '') }}"
                  data-address="{{ strtolower($address) }}" data-driver="{{ strtolower($driverName) }}"
                  data-status="{{ strtolower($currentStatusName) }}"
                  data-search-text="{{ strtolower(trim($createdDisplay . ' ' . ($serviceCall->serviceType?->name ?? '') . ' ' . ($serviceCall->customer_name ?? '') . ' ' . $address . ' ' . $driverName . ' ' . $currentStatusName)) }}">

                  <td>{{ $createdDisplay }}</td>

                  <td>{{ $serviceCall->serviceType?->name ?? '—' }}</td>

                  <td>
                    @php
                      $primaryCustomerName =
                          $serviceCall->customer?->company_name ?:
                          trim(
                              ($serviceCall->customer?->first_name ?? '') .
                                  ' ' .
                                  ($serviceCall->customer?->last_name ?? ''),
                          );

                      $serviceCustomerName = trim($serviceCall->customer_name ?? '');
                    @endphp

                    <span>{{ $primaryCustomerName }}</span>

                    @if ($serviceCustomerName && strcasecmp($primaryCustomerName, $serviceCustomerName) !== 0)
                      <br>
                      <small class="text-muted">For: {{ $serviceCustomerName }}</small>
                    @endif
                  </td>

                  <td>{{ $address ?: '—' }}</td>

                  <td>{{ $serviceCall->assignedUser?->name ?? '—' }}</td>

                  <td>
                    <span class="badge {{ $statusBadgeClass }}">
                      {{ $currentStatusName }}
                    </span>
                  </td>

                  <td class="text-center">

                    <div class="dropdown">

                      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                        <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
                      </button>

                      <div class="dropdown-menu">

                        <button type="button" class="dropdown-item js-edit-service-call" data-bs-toggle="modal"
                          data-bs-target="#editServiceCallModal"
                          data-update-url="{{ route('service-calls.update', $serviceCall) }}"
                          data-service-call-id="{{ $serviceCall->id }}" data-vehicle-id="{{ $serviceCall->vehicle_id }}"
                          data-assigned-user-id="{{ $serviceCall->assigned_user_id }}"
                          data-assigned-company-vehicle-id="{{ $serviceCall->assigned_company_vehicle_id }}"
                          data-service-type-id="{{ $serviceCall->service_type_id }}"
                          data-phone="{{ e($serviceCall->customer_mobile_phone) }}"
                          data-scheduled-for="{{ optional($serviceCall->scheduled_for)?->format('Y-m-d\TH:i') }}"
                          data-created-at="{{ optional($serviceCall->created_at)?->format('Y-m-d\TH:i') }}"
                          data-dispatched-at="{{ optional($serviceCall->dispatched_at)?->format('Y-m-d\TH:i') }}"
                          data-enroute-at="{{ optional($serviceCall->enroute_at)?->format('Y-m-d\TH:i') }}"
                          data-arrived-at="{{ optional($serviceCall->arrived_at)?->format('Y-m-d\TH:i') }}"
                          data-completed-at="{{ optional($serviceCall->completed_at)?->format('Y-m-d\TH:i') }}"
                          data-address1="{{ e($serviceCall->address_1) }}" data-city="{{ e($serviceCall->city) }}"
                          data-state="{{ e($serviceCall->state) }}"
                          data-postal-code="{{ e($serviceCall->postal_code) }}"
                          data-notes="{{ e($serviceCall->notes) }}" data-status-id="{{ $serviceCall->status_id }}">
                          <i class="bx bx-show me-1"></i> Open Call
                        </button>

                        @if ($hasPhone)
                          <a href="tel:{{ $phoneDigits }}" class="dropdown-item">
                            <i class="bx bx-phone-call me-1"></i> Call Customer
                          </a>
                        @endif

                        @if ($hasNavigation)
                          <button type="button" class="dropdown-item js-open-navigation-modal" data-bs-toggle="modal"
                            data-bs-target="#navigationChoiceModal" data-address="{{ e($address) }}"
                            data-latitude="{{ $serviceCall->latitude }}"
                            data-longitude="{{ $serviceCall->longitude }}">
                            <i class="bx bx-map me-1"></i> Navigate
                          </button>
                        @endif

                        @if ($nextStatusCode)
                          <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="status_code" value="{{ $nextStatusCode }}">

                            <button type="submit" class="dropdown-item">
                              <i class="bx bx-chevron-right-circle me-1"></i>
                              {{ $nextStatusLabel }}
                            </button>
                          </form>
                        @endif

                        @if (!in_array($currentStatusCode, ['completed', 'goa', 'cancelled'], true))
                          <div class="dropdown-divider"></div>

                          <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="status_code" value="cancelled">

                            <button type="submit" class="dropdown-item text-danger">
                              <i class="bx bx-x-circle me-1"></i> Cancelled
                            </button>
                          </form>

                          @if ($currentStatusCode === 'on_scene')
                            <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                              @csrf
                              @method('PATCH')

                              <input type="hidden" name="status_code" value="goa">

                              <button type="submit" class="dropdown-item text-warning">
                                <i class="bx bx-error-circle me-1"></i> GOA
                              </button>
                            </form>
                          @endif
                        @endif

                      </div>
                    </div>

                  </td>

                </tr>
              @endforeach

              <tr id="dispatchNoResultsRow" class="d-none">
                <td colspan="7" class="text-center text-muted py-4">
                  No calls match the selected filters.
                </td>
              </tr>

            </tbody>

          </table>

        </div>

        {{-- Mobile Tech Cards --}}
        <div class="d-lg-none dispatch-mobile-bottom-padding" id="dispatchMobileCards">

          @foreach ($serviceCalls as $serviceCall)
            @php
              $address = collect([
                  $serviceCall->address_1,
                  $serviceCall->city,
                  $serviceCall->state,
                  $serviceCall->postal_code,
              ])
                  ->filter()
                  ->implode(', ');

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

              $driverName = $serviceCall->assignedUser?->name ?? 'Unassigned';
              $createdDisplay = optional($serviceCall->created_at)?->format('m/d h:i A') ?? '—';

              $phoneDigits = preg_replace('/\D+/', '', $serviceCall->customer_mobile_phone ?? '');
              $hasPhone = strlen($phoneDigits) >= 10;

              $hasNavigation = !empty($address) || (!empty($serviceCall->latitude) && !empty($serviceCall->longitude));

              $showAddPhotos = in_array($currentStatusCode, ['on_scene', 'completed', 'goa'], true);
            @endphp

            <div class="dispatch-mobile-card" data-dispatch-row="1" data-status-code="{{ $currentStatusCode }}"
              data-driver-id="{{ $serviceCall->assigned_user_id ?? '' }}"
              data-created="{{ optional($serviceCall->created_at)?->timestamp ?? 0 }}"
              data-service="{{ strtolower($serviceCall->serviceType?->name ?? '') }}"
              data-customer="{{ strtolower($serviceCall->customer_name ?? '') }}"
              data-address="{{ strtolower($address) }}" data-driver="{{ strtolower($driverName) }}"
              data-status="{{ strtolower($currentStatusName) }}"
              data-search-text="{{ strtolower(trim($createdDisplay . ' ' . ($serviceCall->serviceType?->name ?? '') . ' ' . ($serviceCall->customer_name ?? '') . ' ' . $address . ' ' . $driverName . ' ' . $currentStatusName)) }}">

              <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <div>
                  <div class="dispatch-mobile-meta mb-1">
                    {{ $createdDisplay }}
                  </div>

                  <h5 class="mb-1">
                    {{ $serviceCall->customer_name ?? 'Unknown Customer' }}
                  </h5>
                </div>

                <span class="badge {{ $statusBadgeClass }}">
                  {{ $currentStatusName }}
                </span>
              </div>

              <div class="dispatch-mobile-meta mb-3">
                <div>{{ $serviceCall->serviceType?->name ?? 'No service type' }}</div>
                <div>{{ $address ?: 'No address' }}</div>
                <div><i class="bx bx-user me-1"></i>{{ $driverName }}</div>
              </div>

              <div class="dispatch-mobile-actions d-grid gap-2">

                <div class="row g-2">
                  <div class="col-6">
                    @if ($hasPhone)
                      <a href="tel:{{ $phoneDigits }}" class="btn btn-outline-primary w-100">
                        <i class="bx bx-phone-call me-1"></i> Call
                      </a>
                    @else
                      <button type="button" class="btn btn-outline-secondary w-100" disabled>
                        <i class="bx bx-phone-call me-1"></i> No Phone
                      </button>
                    @endif
                  </div>

                  <div class="col-6">
                    @if ($hasNavigation)
                      <button type="button" class="btn btn-outline-primary w-100 js-open-navigation-modal"
                        data-bs-toggle="modal" data-bs-target="#navigationChoiceModal"
                        data-address="{{ e($address) }}" data-latitude="{{ $serviceCall->latitude }}"
                        data-longitude="{{ $serviceCall->longitude }}">
                        <i class="bx bx-map me-1"></i> Navigate
                      </button>
                    @else
                      <button type="button" class="btn btn-outline-secondary w-100" disabled>
                        <i class="bx bx-map me-1"></i> No Address
                      </button>
                    @endif
                  </div>
                </div>

                @if ($nextStatusCode)
                  <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" name="status_code" value="{{ $nextStatusCode }}">

                    <button type="submit" class="btn btn-primary w-100">
                      <i class="bx bx-chevron-right-circle me-1"></i>
                      {{ $nextStatusLabel }}
                    </button>
                  </form>
                @endif

                @if ($showAddPhotos)
                  <button type="button" class="btn btn-outline-secondary w-100" disabled>
                    <i class="bx bx-camera me-1"></i> Add Photos
                  </button>
                @endif

                <div class="dispatch-mobile-secondary-actions mt-1">

                  <a href="{{ route('service-calls.tech', $serviceCall) }}">
                    <i class="bx bx-mobile-alt me-1"></i>Details
                  </a>

                  <button type="button" class="js-edit-service-call" data-bs-toggle="modal"
                    data-bs-target="#editServiceCallModal"
                    data-update-url="{{ route('service-calls.update', $serviceCall) }}"
                    data-service-call-id="{{ $serviceCall->id }}" data-vehicle-id="{{ $serviceCall->vehicle_id }}"
                    data-assigned-user-id="{{ $serviceCall->assigned_user_id }}"
                    data-assigned-company-vehicle-id="{{ $serviceCall->assigned_company_vehicle_id }}"
                    data-service-type-id="{{ $serviceCall->service_type_id }}"
                    data-phone="{{ e($serviceCall->customer_mobile_phone) }}"
                    data-scheduled-for="{{ optional($serviceCall->scheduled_for)?->format('Y-m-d\TH:i') }}"
                    data-created-at="{{ optional($serviceCall->created_at)?->format('Y-m-d\TH:i') }}"
                    data-dispatched-at="{{ optional($serviceCall->dispatched_at)?->format('Y-m-d\TH:i') }}"
                    data-enroute-at="{{ optional($serviceCall->enroute_at)?->format('Y-m-d\TH:i') }}"
                    data-arrived-at="{{ optional($serviceCall->arrived_at)?->format('Y-m-d\TH:i') }}"
                    data-completed-at="{{ optional($serviceCall->completed_at)?->format('Y-m-d\TH:i') }}"
                    data-address1="{{ e($serviceCall->address_1) }}" data-city="{{ e($serviceCall->city) }}"
                    data-state="{{ e($serviceCall->state) }}" data-postal-code="{{ e($serviceCall->postal_code) }}"
                    data-notes="{{ e($serviceCall->notes) }}" data-status-id="{{ $serviceCall->status_id }}">
                    <i class="bx bx-edit me-1"></i>Edit
                  </button>

                  @if (!in_array($currentStatusCode, ['completed', 'goa', 'cancelled'], true))
                    <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                      @csrf
                      @method('PATCH')

                      <input type="hidden" name="status_code" value="cancelled">

                      <button type="submit" class="text-danger">
                        <i class="bx bx-x-circle me-1"></i>Cancel
                      </button>
                    </form>

                    @if ($currentStatusCode === 'on_scene')
                      <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="status_code" value="goa">

                        <button type="submit" class="text-warning">
                          <i class="bx bx-error-circle me-1"></i>GOA
                        </button>
                      </form>
                    @endif
                  @endif

                </div>

              </div>

            </div>
          @endforeach

          <div id="dispatchMobileNoResults" class="d-none text-center text-muted py-4">
            No calls match the selected filters.
          </div>

        </div>

        <div id="dispatchMobileNoResults" class="d-none text-center text-muted py-4">
          No calls match the selected filters.
        </div>

    </div>

    @endif

  </div>
  </div>

  @include('content.shared.service-calls.create-modal', [
      'formAction' => route('service-calls.store'),
      'customers' => $customers,
      'selectedCustomer' => null,
      'lockCustomer' => false,
  ])

  @include('content.shared.service-calls.edit-modal', [
      'vehicles' => $vehicles,
  ])

  {{-- Mobile Bottom Navigation --}}
  <div class="dispatch-mobile-bottom-nav d-lg-none">
    <div class="d-flex justify-content-around text-center">

      <button type="button" class="btn flex-fill active js-mobile-board-filter" data-mobile-filter="board">
        <i class="bx bx-grid-alt"></i>
        Board
      </button>

      <button type="button" class="btn flex-fill js-mobile-board-filter" data-mobile-filter="my_jobs"
        data-current-user-id="{{ auth()->id() }}">
        <i class="bx bx-user"></i>
        My Jobs
      </button>

      <button type="button" class="btn flex-fill js-mobile-board-filter" data-mobile-filter="completed">
        <i class="bx bx-check-circle"></i>
        Completed
      </button>

      <button type="button" class="btn flex-fill js-mobile-board-filter" data-mobile-filter="more">
        <i class="bx bx-dots-horizontal-rounded"></i>
        More
      </button>

    </div>
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
          <p class="text-muted small mb-3" id="navigationDestinationText">
            Select a navigation app.
          </p>

          <div class="d-grid gap-2">
            <a href="#" target="_blank" rel="noopener" class="btn btn-outline-primary" id="appleMapsLink">
              Apple Maps
            </a>

            <a href="#" target="_blank" rel="noopener" class="btn btn-outline-primary" id="googleMapsLink">
              Google Maps
            </a>

            <a href="#" target="_blank" rel="noopener" class="btn btn-outline-primary" id="wazeLink">
              Waze
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

@endsection

@push('page-script')
  @include('content.shared.service-calls.edit-modal-script')
  @include('content.shared.service-calls.create-modal-script')

  <script>
    function setupDispatchBoardFilters() {
      const searchInput = document.getElementById('dispatchSearch');
      const statusFilter = document.getElementById('dispatchStatusFilter');
      const driverFilter = document.getElementById('dispatchDriverFilter');
      const clearButton = document.getElementById('dispatchClearFilters');

      const tableBody = document.querySelector('#dispatchBoardTable tbody');
      const desktopRows = Array.from(document.querySelectorAll('#dispatchBoardTable tbody tr[data-dispatch-row]'));
      const mobileCards = Array.from(document.querySelectorAll('#dispatchMobileCards [data-dispatch-row]'));

      const desktopNoResultsRow = document.getElementById('dispatchNoResultsRow');
      const mobileNoResults = document.getElementById('dispatchMobileNoResults');

      const sortableHeaders = document.querySelectorAll('.dispatch-sortable');

      const closedStatuses = ['completed', 'cancelled', 'goa'];

      let currentSort = {
        column: 'created',
        direction: 'asc'
      };

      function rowMatchesFilters(row) {
        const searchValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const selectedStatus = statusFilter ? statusFilter.value : 'active';
        const selectedDriver = driverFilter ? driverFilter.value : 'all';

        const rowStatus = row.dataset.statusCode || '';
        const rowDriver = row.dataset.driverId || '';
        const rowText = row.dataset.searchText || '';

        let visible = true;

        if (selectedStatus === 'active') {
          visible = !closedStatuses.includes(rowStatus);
        } else if (selectedStatus !== 'all') {
          visible = rowStatus === selectedStatus;
        }

        if (visible && selectedDriver === 'unassigned') {
          visible = rowDriver === '';
        } else if (visible && selectedDriver !== 'all') {
          visible = rowDriver === selectedDriver;
        }

        if (visible && searchValue !== '') {
          visible = rowText.includes(searchValue);
        }

        return visible;
      }

      function compareRows(a, b) {
        let aValue = a.dataset[currentSort.column] || '';
        let bValue = b.dataset[currentSort.column] || '';

        if (currentSort.column === 'created') {
          aValue = parseInt(aValue || '0', 10);
          bValue = parseInt(bValue || '0', 10);
        }

        if (aValue < bValue) {
          return currentSort.direction === 'asc' ? -1 : 1;
        }

        if (aValue > bValue) {
          return currentSort.direction === 'asc' ? 1 : -1;
        }

        return 0;
      }

      function sortDesktopRows() {
        if (!tableBody || !desktopRows.length) {
          return;
        }

        const sortedRows = [...desktopRows].sort(compareRows);

        sortedRows.forEach(function(row) {
          tableBody.appendChild(row);
        });

        if (desktopNoResultsRow) {
          tableBody.appendChild(desktopNoResultsRow);
        }
      }

      function sortMobileCards() {
        const mobileContainer = document.getElementById('dispatchMobileCards');

        if (!mobileContainer || !mobileCards.length) {
          return;
        }

        const sortedCards = [...mobileCards].sort(compareRows);

        sortedCards.forEach(function(card) {
          mobileContainer.appendChild(card);
        });

        if (mobileNoResults) {
          mobileContainer.appendChild(mobileNoResults);
        }
      }

      function applyFilters() {
        let visibleDesktopCount = 0;
        let visibleMobileCount = 0;

        desktopRows.forEach(function(row) {
          const visible = rowMatchesFilters(row);

          row.classList.toggle('d-none', !visible);

          if (visible) {
            visibleDesktopCount++;
          }
        });

        mobileCards.forEach(function(card) {
          const visible = rowMatchesFilters(card);

          card.classList.toggle('d-none', !visible);

          if (visible) {
            visibleMobileCount++;
          }
        });

        if (desktopNoResultsRow) {
          desktopNoResultsRow.classList.toggle('d-none', visibleDesktopCount > 0);
        }

        if (mobileNoResults) {
          mobileNoResults.classList.toggle('d-none', visibleMobileCount > 0);
        }

        sortDesktopRows();
        sortMobileCards();
      }

      sortableHeaders.forEach(function(header) {
        header.addEventListener('click', function() {
          const selectedColumn = header.dataset.sort;

          if (currentSort.column === selectedColumn) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
          } else {
            currentSort.column = selectedColumn;
            currentSort.direction = 'asc';
          }

          applyFilters();
        });
      });

      if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
      }

      if (statusFilter) {
        statusFilter.addEventListener('change', applyFilters);
      }

      if (driverFilter) {
        driverFilter.addEventListener('change', applyFilters);
      }

      if (clearButton) {
        clearButton.addEventListener('click', function() {
          if (searchInput) {
            searchInput.value = '';
          }

          if (statusFilter) {
            statusFilter.value = 'active';
          }

          if (driverFilter) {
            driverFilter.value = 'all';
          }

          currentSort = {
            column: 'created',
            direction: 'asc'
          };

          applyFilters();
        });
      }

      applyFilters();
    }

    function setupNavigationChoiceModal() {
      const buttons = document.querySelectorAll('.js-open-navigation-modal');

      const destinationText = document.getElementById('navigationDestinationText');
      const appleMapsLink = document.getElementById('appleMapsLink');
      const googleMapsLink = document.getElementById('googleMapsLink');
      const wazeLink = document.getElementById('wazeLink');

      if (!buttons.length || !appleMapsLink || !googleMapsLink || !wazeLink) {
        return;
      }

      buttons.forEach(function(button) {
        button.addEventListener('click', function() {
          const address = button.dataset.address || '';
          const latitude = button.dataset.latitude || '';
          const longitude = button.dataset.longitude || '';

          const hasCoordinates = latitude !== '' && longitude !== '';

          if (destinationText) {
            destinationText.textContent = hasCoordinates ?
              latitude + ', ' + longitude :
              (address || 'No destination found');
          }

          if (hasCoordinates) {
            const coords = encodeURIComponent(latitude + ',' + longitude);

            appleMapsLink.href = 'https://maps.apple.com/?daddr=' + coords;
            googleMapsLink.href = 'https://www.google.com/maps/dir/?api=1&destination=' + coords;
            wazeLink.href = 'https://waze.com/ul?ll=' + coords + '&navigate=yes';
          } else {
            const encodedAddress = encodeURIComponent(address);

            appleMapsLink.href = 'https://maps.apple.com/?daddr=' + encodedAddress;
            googleMapsLink.href = 'https://www.google.com/maps/dir/?api=1&destination=' + encodedAddress;
            wazeLink.href = 'https://waze.com/ul?q=' + encodedAddress + '&navigate=yes';
          }
        });
      });
    }

    function setupDispatchBoardAutoRefresh() {
      const refreshEveryMilliseconds = 60000;

      let userIsInteracting = false;

      const interactiveSelectors = [
        '#createServiceCallModal.show',
        '#editServiceCallModal.show',
        '#navigationChoiceModal.show'
      ];

      function hasOpenModal() {
        return interactiveSelectors.some(function(selector) {
          return document.querySelector(selector);
        });
      }

      function isFilterActive() {
        const searchInput = document.getElementById('dispatchSearch');
        const statusFilter = document.getElementById('dispatchStatusFilter');
        const driverFilter = document.getElementById('dispatchDriverFilter');

        const hasSearch = searchInput && searchInput.value.trim() !== '';
        const statusChanged = statusFilter && statusFilter.value !== 'active';
        const driverChanged = driverFilter && driverFilter.value !== 'all';

        return hasSearch || statusChanged || driverChanged;
      }

      document.addEventListener('input', function() {
        userIsInteracting = true;
      });

      document.addEventListener('change', function() {
        userIsInteracting = true;
      });

      setInterval(function() {
        if (hasOpenModal()) {
          return;
        }

        if (userIsInteracting && isFilterActive()) {
          return;
        }

        window.location.reload();
      }, refreshEveryMilliseconds);
    }

    function setupMobileBottomNav() {
      const buttons = document.querySelectorAll('.js-mobile-board-filter');
      const statusFilter = document.getElementById('dispatchStatusFilter');
      const driverFilter = document.getElementById('dispatchDriverFilter');
      const searchInput = document.getElementById('dispatchSearch');

      if (!buttons.length || !statusFilter || !driverFilter) {
        return;
      }

      buttons.forEach(function(button) {
        button.addEventListener('click', function() {
          buttons.forEach(function(otherButton) {
            otherButton.classList.remove('active');
          });

          button.classList.add('active');

          const selectedFilter = button.dataset.mobileFilter;

          if (searchInput) {
            searchInput.value = '';
          }

          if (selectedFilter === 'board') {
            statusFilter.value = 'active';
            driverFilter.value = 'all';
          }

          if (selectedFilter === 'my_jobs') {
            statusFilter.value = 'active';
            driverFilter.value = button.dataset.currentUserId || 'all';
          }

          if (selectedFilter === 'completed') {
            statusFilter.value = 'completed';
            driverFilter.value = 'all';
          }

          if (selectedFilter === 'more') {
            statusFilter.value = 'all';
            driverFilter.value = 'all';
          }

          statusFilter.dispatchEvent(new Event('change'));
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      setupEditServiceCallModal();
      setupTimelineEditors();
      setupEditServiceCallFormValidation();
      reopenEditServiceCallModalAfterValidationError();

      setupCreateServiceCallModal();

      setupDispatchBoardFilters();
      setupNavigationChoiceModal();
      setupDispatchBoardAutoRefresh();
      setupMobileBottomNav();
    });
  </script>
@endpush
