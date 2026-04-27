{{-- Edit Service Call Modal --}}
<div class="modal fade" id="editServiceCallModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl mt-10">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title">Edit Service Call</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <form method="POST" id="editServiceCallForm" action="">
          @csrf
          @method('PUT')

          @if ($errors->has('assignment'))
            <div class="alert alert-danger">
              {{ $errors->first('assignment') }}
            </div>
          @endif

          <div class="row">
            <div class="col-lg-6">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label" for="edit_service_status_id">Status</label>
                  <select class="form-select" id="edit_service_status_id" name="status_id" required>
                    @foreach ($serviceCallStatuses as $status)
                      <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label" for="edit_service_service_type_id">Service Type</label>
                  <select class="form-select" id="edit_service_service_type_id" name="service_type_id">
                    @foreach ($serviceTypes as $serviceType)
                      <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label" for="edit_service_assigned_user_id">Assigned Driver</label>
                  <select class="form-select" id="edit_service_assigned_user_id" name="assigned_user_id">
                    <option value="">Unassigned</option>

                    @foreach ($drivers as $driver)
                      <option value="{{ $driver->id }}">
                        {{ trim(($driver->first_name ?? '') . ' ' . ($driver->last_name ?? '')) ?: $driver->email }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label" for="edit_service_assigned_company_vehicle_id">Assigned Truck</label>
                  <select class="form-select" id="edit_service_assigned_company_vehicle_id"
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
                  <label class="form-label" for="edit_service_vehicle_id">Vehicle</label>
                  <select class="form-select" id="edit_service_vehicle_id" name="vehicle_id" required>
                    @foreach ($vehicles as $vehicle)
                      <option value="{{ $vehicle->id }}">
                        {{ trim(($vehicle->year ?? '') . ' ' . ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')) ?: 'Unknown Vehicle' }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label" for="edit_service_phone">Phone</label>
                  <input type="text" class="form-control" id="edit_service_phone" name="customer_mobile_phone"
                    maxlength="14" inputmode="numeric" oninput="formatPhoneNumber(this)">
                </div>

                <div class="col-12 mb-3">
                  <label class="form-label" for="edit_service_address_1">Address</label>
                  <input type="text" class="form-control" id="edit_service_address_1" name="address_1">
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label" for="edit_service_city">City</label>
                  <input type="text" class="form-control" id="edit_service_city" name="city">
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label" for="edit_service_state">State</label>
                  <select class="form-select" id="edit_service_state" name="state">
                    <option value="">Select State</option>

                    @foreach ($states as $abbr => $stateName)
                      <option value="{{ $abbr }}">{{ $stateName }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-4 mb-3">
                  <label class="form-label" for="edit_service_postal_code">Postal Code</label>
                  <input type="text" class="form-control" id="edit_service_postal_code" name="postal_code">
                </div>

                <div class="col-12 mb-3">
                  <label class="form-label" for="edit_service_notes">Notes</label>
                  <textarea class="form-control" rows="4" id="edit_service_notes" name="notes"></textarea>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="border rounded p-3 bg-lighter h-100">
                <h5 class="mb-3">Timeline</h5>

                @php
                  $timeline = [
                      'created_at' => 'Call Received',
                      'scheduled_for' => 'Scheduled For',
                      'dispatched_at' => 'Dispatched',
                      'enroute_at' => 'En Route',
                      'arrived_at' => 'On Scene',
                      'completed_at' => 'Closed Out',
                  ];
                @endphp

                @foreach ($timeline as $field => $label)
                  <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted d-block">{{ $label }}</small>
                        <span id="label_{{ $field }}">—</span>
                      </div>

                      <button type="button" class="btn btn-sm btn-icon btn-outline-primary timeline-edit-btn"
                        data-target="{{ $field }}">
                        <i class="bx bx-pencil"></i>
                      </button>
                    </div>

                    <div class="timeline-edit-row d-none mt-2" id="row_{{ $field }}">
                      <div class="input-group">
                        <input type="datetime-local" class="form-control" name="{{ $field }}"
                          id="input_{{ $field }}">

                        <button type="button" class="btn btn-success timeline-save-btn"
                          data-target="{{ $field }}">
                          <i class="bx bx-check"></i>
                        </button>

                        <button type="button" class="btn btn-outline-secondary timeline-cancel-btn"
                          data-target="{{ $field }}">
                          <i class="bx bx-x"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary me-2">
              Update Service Call
            </button>

            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
