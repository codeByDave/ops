@extends('layouts.contentNavbarLayout')

@section('title', 'Profile')

@section('content')

  @if ($errors->has('assignment'))
    <div class="alert alert-danger">
      {{ $errors->first('assignment') }}
    </div>
  @endif

  @php
    $displayName = $customer->company_name ?: trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));

    $profileAddressParts = collect([
        $customer->address_1,
        $customer->address_2,
        $customer->city,
        $customer->state,
        $customer->postal_code,
    ])->filter();

    $fullAddress = $profileAddressParts->implode(', ');

    $hasProfileAddress = $profileAddressParts->isNotEmpty();
  @endphp

  {{-- Customer Summary --}}
  <div class="card mb-4">
    <div class="card-body">
      <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

        <div class="d-flex align-items-center gap-3">
          <div class="avatar avatar-xl">
            <span class="avatar-initial rounded-circle bg-label-primary">
              @php
                $name = trim($displayName ?: 'Profile');
                $parts = preg_split('/\s+/', $name);
                $initials = strtoupper(substr($parts[0], 0, 1));

                if (count($parts) > 1) {
                    $initials .= strtoupper(substr($parts[1], 0, 1));
                }
              @endphp

              {{ $initials }}
            </span>
          </div>

          <div>
            <h4 class="mb-1">{{ $displayName ?: 'Unnamed Profile' }}</h4>

            <span class="badge bg-label-primary">
              {{ $customer->customerType?->name ?? 'No Type' }}
            </span>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit
          </button>

          <button type="button" class="btn btn-label-danger">
            Suspend
          </button>
        </div>
      </div>

      <hr class="my-4">

      <div class="row">
        <div class="col-md-4 mb-3 mb-md-0">
          <small class="text-muted d-block">Email</small>
          <span>{{ $customer->email ?? '—' }}</span>
        </div>

        <div class="col-md-4 mb-3 mb-md-0">
          <small class="text-muted d-block">Phone</small>
          <span>{{ $customer->formatted_mobile_phone ?? '—' }}</span>
        </div>

        <div class="col-md-4">
          <small class="text-muted d-block">Status</small>
          <span>Active</span>
        </div>

        <div class="col-12 mt-3">
          <small class="text-muted d-block">Address</small>
          <span>{{ $fullAddress ?: '—' }}</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Tabs --}}
  <ul class="nav nav-pills mb-4" role="tablist">
    <li class="nav-item">
      <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#job-history-tab"
        aria-controls="job-history-tab" aria-selected="true">
        <i class="bx bx-list-ul me-1"></i> Job History
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#vehicles-tab"
        aria-controls="vehicles-tab" aria-selected="false">
        <i class="bx bx-car me-1"></i> Vehicles
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#security-tab"
        aria-controls="security-tab" aria-selected="false">
        <i class="bx bx-lock-alt me-1"></i> Security
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#billing-tab"
        aria-controls="billing-tab" aria-selected="false">
        <i class="bx bx-credit-card me-1"></i> Billing & Plans
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#invoices-tab"
        aria-controls="invoices-tab" aria-selected="false">
        <i class="bx bx-receipt me-1"></i> Invoices
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#communications-tab"
        aria-controls="communications-tab" aria-selected="false">
        <i class="bx bx-phone me-1"></i> Communications
      </button>
    </li>

    <li class="nav-item">
      <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#notes-tab"
        aria-controls="notes-tab" aria-selected="false">
        <i class="bx bx-note me-1"></i> Notes
      </button>
    </li>
  </ul>

  <div class="tab-content p-0">

    {{-- JOB HISTORY TAB --}}
    <div class="tab-pane fade show active" id="job-history-tab" role="tabpanel">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Job History</h5>
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#createServiceCallModal">
            Add Service Call
          </button>
        </div>

        <div class="card-body">
          @if ($customer->serviceCalls->isEmpty())
            <p class="text-muted mb-0">No service call history yet.</p>
          @else
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Service</th>
                    <th>Vehicle</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($customer->serviceCalls as $call)
                    <tr>
                      <td>{{ optional($call->created_at)->format('m/d/Y') }}</td>

                      <td>
                        {{ $call->serviceType->name ?? '-' }}

                        @if (!empty($call->notes))
                          <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ e($call->notes) }}">
                            <i class="bx bx-note text-warning fs-5"></i>
                          </span>
                        @endif
                      </td>

                      <td>{{ $call->vehicle_label ?? '-' }}</td>

                      <td class="text-center">
                        @php
                          $statusCode = $call->status?->code;

                          $statusBadgeClass = match ($statusCode) {
                              'new' => 'bg-label-primary',
                              'scheduled' => 'bg-label-info',
                              'dispatched' => 'bg-label-warning',
                              'en_route' => 'bg-label-warning',
                              'on_scene' => 'bg-label-dark',
                              'completed' => 'bg-label-success',
                              'goa' => 'bg-label-secondary',
                              'cancelled' => 'bg-label-danger',
                              default => 'bg-label-secondary',
                          };

                          $scheduledTooltip = $call->scheduled_for
                              ? 'Scheduled for ' . $call->scheduled_for->format('m/d/Y g:i A')
                              : null;
                        @endphp

                        <span class="badge {{ $statusBadgeClass }}"
                          @if ($scheduledTooltip) data-bs-toggle="tooltip"
                          data-bs-placement="top"
                          title="{{ $scheduledTooltip }}" @endif>
                          {{ $call->status?->name ?? 'Unknown' }}
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
                              data-update-url="{{ route('service-calls.update', $call) }}"
                              data-service-call-id="{{ $call->id }}" data-vehicle-id="{{ $call->vehicle_id }}"
                              data-assigned-user-id="{{ $call->assigned_user_id }}"
                              data-assigned-company-vehicle-id="{{ $call->assigned_company_vehicle_id }}"
                              data-service-type-id="{{ $call->service_type_id }}"
                              data-phone="{{ e($call->customer_mobile_phone) }}"
                              data-scheduled-for="{{ optional($call->scheduled_for)?->format('Y-m-d\TH:i') }}"
                              data-created-at="{{ optional($call->created_at)?->format('Y-m-d\TH:i') }}"
                              data-dispatched-at="{{ optional($call->dispatched_at)?->format('Y-m-d\TH:i') }}"
                              data-enroute-at="{{ optional($call->enroute_at)?->format('Y-m-d\TH:i') }}"
                              data-arrived-at="{{ optional($call->arrived_at)?->format('Y-m-d\TH:i') }}"
                              data-completed-at="{{ optional($call->completed_at)?->format('Y-m-d\TH:i') }}"
                              data-address1="{{ e($call->address_1) }}" data-city="{{ e($call->city) }}"
                              data-state="{{ e($call->state) }}" data-postal-code="{{ e($call->postal_code) }}"
                              data-notes="{{ e($call->notes) }}" data-status-id="{{ $call->status_id }}">
                              <i class="bx bx-show me-1"></i> View / Edit
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- VEHICLES TAB --}}
    <div class="tab-pane fade" id="vehicles-tab" role="tabpanel">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Vehicles</h5>
          <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
            data-bs-target="#addVehicleModal">
            Add Vehicle
          </button>
        </div>

        <div class="card-body">
          @if ($customer->vehicles->isEmpty())
            <p class="text-muted mb-0">No vehicles linked to this profile yet.</p>
          @else
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Vehicle</th>
                    <th class="text-center">Plate</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>

                <tbody>
                  @foreach ($customer->vehicles as $vehicle)
                    <tr>
                      <td>
                        @php
                          $vehicleLabel = trim(
                              ($vehicle->year ?? '') . ' ' . ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? ''),
                          );

                          $vehicleColor = trim($vehicle->color ?? '');

                          if ($vehicleColor !== '' && strtolower($vehicleColor) !== 'unknown') {
                              $vehicleLabel = trim($vehicleLabel . ' - ' . $vehicleColor);
                          }
                        @endphp

                        {{ $vehicleLabel ?: '—' }}

                        @if (!empty($vehicle->notes))
                          <span data-bs-toggle="tooltip" data-bs-placement="top" title="{{ e($vehicle->notes) }}">
                            <i class="bx bx-note text-warning fs-5"></i>
                          </span>
                        @endif
                      </td>

                      <td class="text-center">
                        @php
                          $plate = null;

                          if (!empty($vehicle->tag_state) && !empty($vehicle->tag_number)) {
                              $plate = strtoupper($vehicle->tag_state) . ' - ' . strtoupper($vehicle->tag_number);
                          } elseif (!empty($vehicle->tag_number)) {
                              $plate = strtoupper($vehicle->tag_number);
                          }
                        @endphp

                        {{ $plate ?: '—' }}
                      </td>

                      <td class="text-center">
                        <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="icon-base bx bx-dots-vertical-rounded icon-md"></i>
                          </button>

                          <div class="dropdown-menu">
                            <button type="button" class="dropdown-item js-edit-vehicle" data-bs-toggle="modal"
                              data-bs-target="#editVehicleModal"
                              data-update-url="{{ route('vehicles.update', $vehicle) }}"
                              data-year="{{ $vehicle->year }}" data-make="{{ $vehicle->make }}"
                              data-model="{{ $vehicle->model }}" data-color="{{ $vehicle->color }}"
                              data-vin="{{ $vehicle->vin }}" data-tag-state="{{ $vehicle->tag_state }}"
                              data-tag-number="{{ $vehicle->tag_number }}" data-notes="{{ $vehicle->notes }}">
                              <i class="bx bx-show me-1"></i> View / Edit
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- SECURITY TAB --}}
    <div class="tab-pane fade" id="security-tab" role="tabpanel">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Security</h5>
        </div>

        <div class="card-body">
          <p class="text-muted mb-0">
            Placeholder for portal access, password resets, last login, MFA, and authorized users.
          </p>
        </div>
      </div>
    </div>

    {{-- BILLING TAB --}}
    <div class="tab-pane fade" id="billing-tab" role="tabpanel">
      <div class="row">
        <div class="col-xl-6 mb-4">
          <div class="card h-100 border border-primary">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="badge bg-label-primary">Membership</span>
                <h4 class="mb-0">$0<span class="fs-6 fw-normal"> / month</span></h4>
              </div>

              <p class="mb-2"><strong>Status:</strong> No active membership</p>

              <ul class="ps-3 mb-3">
                <li>No plan selected</li>
                <li>Benefits will show here later</li>
                <li>Renewal tracking will be added later</li>
              </ul>

              <button type="button" class="btn btn-primary w-100">
                Manage Membership
              </button>
            </div>
          </div>
        </div>

        <div class="col-xl-6 mb-4">
          <div class="card h-100">
            <div class="card-header">
              <h5 class="mb-0">Payment Method</h5>
            </div>

            <div class="card-body">
              <div class="border rounded p-4 bg-lighter">
                <div class="d-flex justify-content-between align-items-start mb-4">
                  <div>
                    <small class="text-muted d-block">Saved Card</small>
                    <h5 class="mb-0">No card on file</h5>
                  </div>

                  <i class="bx bx-credit-card fs-2 text-primary"></i>
                </div>

                <div class="mb-4">
                  <span class="fs-4 letter-spacing-2">•••• •••• •••• ————</span>
                </div>

                <div class="d-flex justify-content-between">
                  <div>
                    <small class="text-muted d-block">Card Holder</small>
                    <span>{{ $displayName ?: '—' }}</span>
                  </div>

                  <div class="text-end">
                    <small class="text-muted d-block">Expires</small>
                    <span>—</span>
                  </div>
                </div>
              </div>

              <button type="button" class="btn btn-outline-primary w-100 mt-3">
                Add Payment Method
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- INVOICES TAB --}}
    <div class="tab-pane fade" id="invoices-tab" role="tabpanel">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Invoices</h5>
          <button type="button" class="btn btn-sm btn-primary">
            Create Invoice
          </button>
        </div>

        <div class="card-body">
          <p class="text-muted mb-0">
            Placeholder for invoice table, open balances, paid invoices, refunds, and credits.
          </p>
        </div>
      </div>
    </div>

    {{-- COMMUNICATIONS TAB --}}
    <div class="tab-pane fade" id="communications-tab" role="tabpanel">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Communications</h5>
          <button type="button" class="btn btn-sm btn-primary">Log Communication</button>
        </div>

        <div class="card-body">
          <p class="text-muted mb-0">
            Placeholder for phone calls, emails, SMS messages, contact attempts, and communication history.
          </p>
        </div>
      </div>
    </div>

    {{-- NOTES TAB --}}
    <div class="tab-pane fade" id="notes-tab" role="tabpanel">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Notes History</h5>
          <button type="button" class="btn btn-sm btn-primary">Add Note</button>
        </div>

        <div class="card-body">
          @if ($customer->notes)
            <p>{{ $customer->notes }}</p>
          @else
            <p class="text-muted mb-0">
              Placeholder for internal notes, customer-visible notes, timestamps, and note history.
            </p>
          @endif
        </div>
      </div>
    </div>

  </div>

@endsection

{{-- Edit Profile Modal --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <h4 class="mb-2">Edit Profile</h4>
          <p class="text-muted mb-0">Update profile information.</p>
        </div>

        <form method="POST" action="{{ route('customers.update', $customer) }}">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-12 mb-3">
              <label class="form-label" for="modal_customer_type_id">Customer Type</label>
              <select class="form-select" id="modal_customer_type_id" name="customer_type_id" required>
                <option value="">Select Customer Type</option>
                @foreach ($customerTypes as $customerType)
                  <option value="{{ $customerType->id }}" data-code="{{ $customerType->code }}"
                    {{ old('customer_type_id', $customer->customer_type_id ?? '') == $customerType->id ? 'selected' : '' }}>
                    {{ $customerType->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3 modal-consumer-name-field">
              <label class="form-label" for="modal_first_name">First Name</label>
              <input type="text" class="form-control" id="modal_first_name" name="first_name"
                value="{{ old('first_name', $customer->first_name ?? '') }}">
            </div>

            <div class="col-md-6 mb-3 modal-consumer-name-field">
              <label class="form-label" for="modal_last_name">Last Name</label>
              <input type="text" class="form-control" id="modal_last_name" name="last_name"
                value="{{ old('last_name', $customer->last_name ?? '') }}">
            </div>

            <div id="modal-company-name-field" class="col-12 mb-3">
              <label class="form-label" for="modal_company_name">Company Name</label>
              <input type="text" class="form-control" id="modal_company_name" name="company_name"
                value="{{ old('company_name', $customer->company_name ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_email">Email</label>
              <input type="email" class="form-control" id="modal_email" name="email"
                value="{{ old('email', $customer->email ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_mobile_phone">Phone</label>
              <input type="text" class="form-control" id="modal_mobile_phone" name="mobile_phone"
                value="{{ old('mobile_phone', $customer->formatted_mobile_phone ?? '') }}" maxlength="14"
                inputmode="numeric" oninput="formatPhoneNumber(this)">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_address_1">Address 1</label>
              <input type="text" class="form-control" id="modal_address_1" name="address_1"
                value="{{ old('address_1', $customer->address_1 ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_address_2">Address 2</label>
              <input type="text" class="form-control" id="modal_address_2" name="address_2"
                value="{{ old('address_2', $customer->address_2 ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_city">City</label>
              <input type="text" class="form-control" id="modal_city" name="city"
                value="{{ old('city', $customer->city ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="modal_state">State</label>
              <select class="form-select" id="modal_state" name="state">
                <option value="">Select State</option>
                @foreach ($states as $abbr => $stateName)
                  <option value="{{ $abbr }}"
                    {{ old('state', $customer->state ?? '') == $abbr ? 'selected' : '' }}>
                    {{ $stateName }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="modal_postal_code">Postal Code</label>
              <input type="text" class="form-control" id="modal_postal_code" name="postal_code"
                value="{{ old('postal_code', $customer->postal_code ?? '') }}">
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="modal_notes">Notes</label>
              <textarea class="form-control" id="modal_notes" name="notes" rows="4">{{ old('notes', $customer->notes ?? '') }}</textarea>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Update Profile</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Add Vehicle Modal --}}
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">

  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <div class="text-center mb-4">
          <h4 class="mb-2">Add Vehicle</h4>
          <p class="text-muted mb-0">Add a vehicle to this profile.</p>
        </div>

        <form method="POST" action="{{ route('customers.vehicles.store', $customer) }}">
          @csrf

          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label" for="vehicle_vin">VIN <span class="text-muted">(optional)</span></label>
              <input type="text" class="form-control" id="vehicle_vin" name="vin" maxlength="17"
                style="text-transform: uppercase;">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="vehicle_color">Color</label>
              <input type="text" class="form-control" id="vehicle_color" name="color"
                list="vehicle_color_options" required>

              <datalist id="vehicle_color_options">
                @foreach ($vehicleColors as $color)
                  <option value="{{ $color }}">
                @endforeach
              </datalist>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="vehicle_year">Year</label>
              <input type="number" class="form-control" id="vehicle_year" name="year" min="1900"
                max="{{ date('Y') + 1 }}">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="vehicle_make">Make</label>
              <input type="text" class="form-control" id="vehicle_make" name="make"
                list="vehicle_make_options" required>

              <datalist id="vehicle_make_options">
                @foreach ($vehicleMakes as $make)
                  <option value="{{ $make }}">
                @endforeach
              </datalist>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="vehicle_model">Model</label>
              <input type="text" class="form-control" id="vehicle_model" name="model">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="vehicle_tag_state">Tag State</label>
              <select class="form-select" id="vehicle_tag_state" name="tag_state">
                <option value="">Select State</option>
                @foreach ($states as $abbr => $stateName)
                  <option value="{{ $abbr }}">{{ $stateName }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-8 mb-3">
              <label class="form-label" for="vehicle_tag_number">Tag Number</label>
              <input type="text" class="form-control" id="vehicle_tag_number" name="tag_number">
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="vehicle_notes">Notes</label>
              <textarea class="form-control" id="vehicle_notes" name="notes" rows="3"></textarea>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Save Vehicle</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Edit Vehicle Modal --}}
<div class="modal fade" id="editVehicleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">

      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title">Edit Vehicle</h4>

        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>

      <div class="modal-body">
        <form id="editVehicleForm" method="POST" action="">
          @csrf
          @method('PUT')

          <div class="row">
            <div class="col-md-8 mb-3">
              <label class="form-label" for="edit_vehicle_vin">VIN <span class="text-muted">(optional)</span></label>
              <input type="text" class="form-control" id="edit_vehicle_vin" name="vin" maxlength="17"
                style="text-transform: uppercase;">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="edit_vehicle_color">Color</label>
              <input type="text" class="form-control" id="edit_vehicle_color" name="color"
                list="vehicle_color_options" required>
            </div>

            {{-- Line 2: Year / Make / Model --}}
            <div class="col-md-4 mb-3">
              <label class="form-label" for="edit_vehicle_year">Year</label>
              <input type="number" class="form-control" id="edit_vehicle_year" name="year" min="1900"
                max="{{ date('Y') + 1 }}">
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="edit_vehicle_make">Make</label>
              <input type="text" class="form-control" id="edit_vehicle_make" name="make"
                list="vehicle_make_options" required>
            </div>

            <div class="col-md-4 mb-3">
              <label class="form-label" for="edit_vehicle_model">Model</label>
              <input type="text" class="form-control" id="edit_vehicle_model" name="model">
            </div>

            {{-- Line 3: Tag State / Tag Number --}}
            <div class="col-md-4 mb-3">
              <label class="form-label" for="edit_vehicle_tag_state">Tag State</label>
              <select class="form-select" id="edit_vehicle_tag_state" name="tag_state">
                <option value="">Select State</option>
                @foreach ($states as $abbr => $stateName)
                  <option value="{{ $abbr }}">{{ $stateName }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-8 mb-3">
              <label class="form-label" for="edit_vehicle_tag_number">Tag Number</label>
              <input type="text" class="form-control" id="edit_vehicle_tag_number" name="tag_number"
                style="text-transform: uppercase;">
            </div>

            {{-- Line 4: Notes --}}
            <div class="col-12 mb-3">
              <label class="form-label" for="edit_vehicle_notes">Notes</label>
              <textarea class="form-control" id="edit_vehicle_notes" name="notes" rows="3"></textarea>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Update Vehicle</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

{{-- Create Service Call Modal --}}
@include('content.shared.service-calls.create-modal', [
    'formAction' => route('customers.service-calls.store', $customer),
    'customers' => collect([$customer]),
    'selectedCustomer' => $customer,
    'lockCustomer' => true,
])

{{-- Edit Service Call Modal --}}
@include('content.shared.service-calls.edit-modal', [
    'vehicles' => $customer->vehicles,
])

@push('page-script')
  @include('content.shared.service-calls.create-modal-script')
  @include('content.shared.service-calls.edit-modal-script')

  <script>
    function setupTooltips() {
      document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
        new bootstrap.Tooltip(el);
      });
    }

    function toggleModalCustomerFields() {
      const typeSelect = document.getElementById('modal_customer_type_id');

      if (!typeSelect) {
        return;
      }

      const selectedOption = typeSelect.options[typeSelect.selectedIndex];
      const selectedCode = selectedOption ? selectedOption.dataset.code : '';

      const consumerFields = document.querySelectorAll('.modal-consumer-name-field');
      const companyField = document.getElementById('modal-company-name-field');

      const firstName = document.getElementById('modal_first_name');
      const lastName = document.getElementById('modal_last_name');
      const companyName = document.getElementById('modal_company_name');

      if (selectedCode === 'consumer') {
        consumerFields.forEach(function(field) {
          field.style.display = '';
        });

        companyField.style.display = 'none';

        firstName.disabled = false;
        lastName.disabled = false;
        companyName.disabled = true;

        firstName.required = true;
        lastName.required = true;
        companyName.required = false;
      } else if (
        selectedCode === 'business' ||
        selectedCode === 'motor_club' ||
        selectedCode === 'insurance'
      ) {
        consumerFields.forEach(function(field) {
          field.style.display = 'none';
        });

        companyField.style.display = 'block';

        firstName.disabled = true;
        lastName.disabled = true;
        companyName.disabled = false;

        firstName.required = false;
        lastName.required = false;
        companyName.required = true;
      } else {
        consumerFields.forEach(function(field) {
          field.style.display = '';
        });

        companyField.style.display = 'block';

        firstName.disabled = false;
        lastName.disabled = false;
        companyName.disabled = false;

        firstName.required = false;
        lastName.required = false;
        companyName.required = false;
      }
    }

    function setupEditVehicleModal() {
      document.querySelectorAll('.js-edit-vehicle').forEach(function(button) {
        button.addEventListener('click', function() {
          document.getElementById('editVehicleForm').action = button.dataset.updateUrl;

          document.getElementById('edit_vehicle_year').value = button.dataset.year || '';
          document.getElementById('edit_vehicle_make').value = button.dataset.make || '';
          document.getElementById('edit_vehicle_model').value = button.dataset.model || '';
          document.getElementById('edit_vehicle_color').value = button.dataset.color || '';
          document.getElementById('edit_vehicle_vin').value = button.dataset.vin || '';
          document.getElementById('edit_vehicle_tag_state').value = button.dataset.tagState || '';
          document.getElementById('edit_vehicle_tag_number').value = button.dataset.tagNumber || '';
          document.getElementById('edit_vehicle_notes').value = button.dataset.notes || '';
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      const typeSelect = document.getElementById('modal_customer_type_id');

      if (typeSelect) {
        typeSelect.addEventListener('change', toggleModalCustomerFields);
        toggleModalCustomerFields();
      }

      setupTooltips();
      setupEditVehicleModal();
      setupCreateServiceCallModal();

      setupEditServiceCallModal();
      setupTimelineEditors();
      setupEditServiceCallFormValidation();
      reopenEditServiceCallModalAfterValidationError();
    });
  </script>
@endpush
