@php
  $lockCustomer = $lockCustomer ?? false;
  $selectedCustomer = $selectedCustomer ?? null;
  $customerOptions = $customers ?? collect($selectedCustomer ? [$selectedCustomer] : []);
@endphp

<div class="modal fade" id="createServiceCallModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title">Add Service Call</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form method="POST" action="{{ $formAction }}">
          @csrf

          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_customer_id">Customer</label>

              @if ($lockCustomer && $selectedCustomer)
                <input type="hidden" id="service_call_locked_customer_id" name="customer_id"
                  value="{{ $selectedCustomer->id }}">

                <select class="form-select" id="service_call_customer_id" disabled>
                  <option selected>
                    {{ $selectedCustomer->company_name ?: trim(($selectedCustomer->first_name ?? '') . ' ' . ($selectedCustomer->last_name ?? '')) }}
                  </option>
                </select>
              @else
                <select class="form-select" id="service_call_customer_id" name="customer_id" required>
                  <option value="">Select Customer</option>

                  @foreach ($customerOptions as $customerOption)
                    <option value="{{ $customerOption->id }}" data-phone="{{ $customerOption->mobile_phone ?? '' }}"
                      data-address1="{{ e($customerOption->address_1) }}" data-city="{{ e($customerOption->city) }}"
                      data-state="{{ e($customerOption->state) }}"
                      data-postal-code="{{ e($customerOption->postal_code) }}">
                      {{ $customerOption->company_name ?: trim(($customerOption->first_name ?? '') . ' ' . ($customerOption->last_name ?? '')) }}
                    </option>
                  @endforeach
                </select>
              @endif
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_vehicle_id">Vehicle</label>
              <select class="form-select" id="service_call_vehicle_id" name="vehicle_id" required>
                <option value="">Select Vehicle</option>

                @foreach ($customerOptions as $customerOption)
                  @foreach ($customerOption->vehicles as $vehicle)
                    <option value="{{ $vehicle->id }}" data-customer-id="{{ $customerOption->id }}">
                      {{ trim(($vehicle->year ?? '') . ' ' . ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')) ?: 'Unknown Vehicle' }}
                      @if ($vehicle->tag_number)
                        - {{ $vehicle->tag_state }} {{ $vehicle->tag_number }}
                      @endif
                    </option>
                  @endforeach
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_service_type_id">Service Type</label>
              <select class="form-select" id="service_call_service_type_id" name="service_type_id" required>
                <option value="">Select Service Type</option>

                @foreach ($serviceTypes as $serviceType)
                  <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_customer_mobile_phone">Phone</label>
              <input type="text" class="form-control" id="service_call_customer_mobile_phone"
                name="customer_mobile_phone" maxlength="14" inputmode="numeric" oninput="formatPhoneNumber(this)">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_assigned_user_id">Assigned Driver</label>
              <select class="form-select" id="service_call_assigned_user_id" name="assigned_user_id">
                <option value="">Unassigned</option>

                @foreach ($drivers as $driver)
                  <option value="{{ $driver->id }}">
                    {{ trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: $driver->email }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_assigned_company_vehicle_id">Assigned Truck</label>
              <select class="form-select" id="service_call_assigned_company_vehicle_id"
                name="assigned_company_vehicle_id">
                <option value="">Unassigned</option>

                @foreach ($companyVehicles as $companyVehicle)
                  <option value="{{ $companyVehicle->id }}">
                    {{ $companyVehicle->description }}
                    @if ($companyVehicle->plate_number)
                      - {{ $companyVehicle->plate_number }}
                    @endif
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label d-block">Scheduling</label>

              <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" id="service_call_is_scheduled" autocomplete="off">
                <label class="form-check-label" for="service_call_is_scheduled">
                  Schedule for later
                </label>
              </div>
            </div>

            <div class="col-md-6 mb-3 d-none" id="service_call_scheduled_for_wrapper">
              <label class="form-label" for="service_call_scheduled_for">Scheduled For</label>
              <input type="datetime-local" class="form-control" id="service_call_scheduled_for" name="scheduled_for"
                disabled>
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="service_call_address_1">Address</label>
              <input type="text" class="form-control" id="service_call_address_1" name="address_1">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="service_call_city">City</label>
              <input type="text" class="form-control" id="service_call_city" name="city">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="service_call_state">State</label>
              <select class="form-select" id="service_call_state" name="state">
                <option value="">Select State</option>

                @foreach ($states as $abbr => $stateName)
                  <option value="{{ $abbr }}">{{ $stateName }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="service_call_postal_code">Postal Code</label>
              <input type="text" class="form-control" id="service_call_postal_code" name="postal_code">
            </div>

            <div class="col-12 mb-3">
              <label class="form-label" for="service_call_notes">Notes</label>
              <textarea class="form-control" id="service_call_notes" name="notes" rows="3"></textarea>
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Save Service Call</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
