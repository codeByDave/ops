@extends('layouts.contentNavbarLayout')

@section('title', 'Profile')

@section('content')

@php
  $displayName = $customer->company_name
    ?: trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? ''));

  $fullAddress = collect([
    $customer->address_1,
    $customer->address_2,
    $customer->city,
    $customer->state,
    $customer->postal_code,
  ])->filter()->implode(', ');
@endphp

<div class="row">

  {{-- LEFT PROFILE COLUMN --}}
  <div class="col-xl-4 col-lg-5 col-md-5">

    {{-- Profile Summary --}}
    <div class="card mb-4">
      <div class="card-body text-center">

        <div class="mb-3">
          <div class="avatar avatar-xl mx-auto">
            <span class="avatar-initial rounded-circle bg-label-primary">
              {{ strtoupper(substr($displayName ?: 'P', 0, 1)) }}
            </span>
          </div>
        </div>

        <h4 class="mb-1">{{ $displayName ?: 'Unnamed Profile' }}</h4>

        <span class="badge bg-label-primary mb-3">
          {{ $customer->customerType?->name ?? 'No Type' }}
        </span>

        <div class="text-start mt-4">
          <h6 class="pb-2 border-bottom">Details</h6>

          <p class="mb-2"><strong>Email:</strong> {{ $customer->email ?? '—' }}</p>
          <p class="mb-2"><strong>Mobile:</strong> {{ $customer->formatted_mobile_phone ?? '—' }}</p>
          <p class="mb-2"><strong>Home:</strong> {{ $customer->formatted_home_phone ?? '—' }}</p>
          <p class="mb-2"><strong>Address:</strong> {{ $fullAddress ?: '—' }}</p>
          <p class="mb-0"><strong>Status:</strong> Active</p>
        </div>

        <div class="d-flex justify-content-center gap-2 mt-4">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            Edit
          </button>
          <button type="button" class="btn btn-label-danger">Suspend</button>
        </div>
      </div>
    </div>

    {{-- Membership / Plan --}}
    <div class="card mb-4 border border-primary">
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

  {{-- RIGHT CONTENT COLUMN --}}
  <div class="col-xl-8 col-lg-7 col-md-7">

    {{-- Tabs --}}
    <ul class="nav nav-pills mb-4" role="tablist">
      <li class="nav-item">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#account-tab" aria-controls="account-tab" aria-selected="true">
          <i class="bx bx-user me-1"></i> Account
        </button>
      </li>

      <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#security-tab" aria-controls="security-tab" aria-selected="false">
          <i class="bx bx-lock-alt me-1"></i> Security
        </button>
      </li>

      <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#billing-tab" aria-controls="billing-tab" aria-selected="false">
          <i class="bx bx-credit-card me-1"></i> Billing & Plans
        </button>
      </li>

      <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#communications-tab" aria-controls="communications-tab" aria-selected="false">
          <i class="bx bx-phone me-1"></i> Communications
        </button>
      </li>

      <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#notes-tab" aria-controls="notes-tab" aria-selected="false">
          <i class="bx bx-note me-1"></i> Notes
        </button>
      </li>
    </ul>

    <div class="tab-content p-0">

      {{-- ACCOUNT TAB --}}
      <div class="tab-pane fade show active" id="account-tab" role="tabpanel">

        {{-- Vehicles --}}
        <div class="card mb-4">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Vehicles</h5>
            <button type="button" class="btn btn-sm btn-primary">Add Vehicle</button>
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
                      <th>Plate</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($customer->vehicles as $vehicle)
                      <tr>
                        <td>
                          {{ trim(($vehicle->year ?? '') . ' ' . ($vehicle->make ?? '') . ' ' . ($vehicle->model ?? '')) ?: '—' }}
                        </td>
                        <td>
                          {{ trim(($vehicle->tag_state ?? '') . ' ' . ($vehicle->tag_number ?? '')) ?: '—' }}
                        </td>
                        <td>
                          @if ($vehicle->is_active)
                            <span class="badge bg-label-success">Active</span>
                          @else
                            <span class="badge bg-label-secondary">Inactive</span>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>

        {{-- Job / Service Call History --}}
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">Job History</h5>
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
                      <th>Vehicle</th>
                      <th>Service</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($customer->serviceCalls as $serviceCall)
                      <tr>
                        <td>{{ $serviceCall->created_at?->format('m/d/Y') }}</td>
                        <td>{{ $serviceCall->vehicle_label ?? '—' }}</td>
                        <td>{{ $serviceCall->serviceType?->name ?? '—' }}</td>
                        <td>{{ $serviceCall->status?->name ?? '—' }}</td>
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
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="mb-0">Billing & Plans</h5>
          </div>
          <div class="card-body">
            <p class="text-muted mb-0">
              Placeholder for billing profile, memberships, saved payment methods, and plans.
            </p>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h5 class="mb-0">Balance / Invoices / Payments</h5>
          </div>
          <div class="card-body">
            <p class="text-muted mb-0">
              Placeholder for balance, open invoices, payment history, refunds, and credits.
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
  </div>
</div>

@endsection

// Edit Modal
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-simple modal-edit-user">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

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
                  <option
                    value="{{ $customerType->id }}"
                    data-code="{{ $customerType->code }}"
                    {{ old('customer_type_id', $customer->customer_type_id ?? '') == $customerType->id ? 'selected' : '' }}
                  >
                    {{ $customerType->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div id="modal-consumer-name-fields" class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label" for="modal_first_name">First Name</label>
                <input type="text" class="form-control" id="modal_first_name" name="first_name" value="{{ old('first_name', $customer->first_name ?? '') }}">
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label" for="modal_last_name">Last Name</label>
                <input type="text" class="form-control" id="modal_last_name" name="last_name" value="{{ old('last_name', $customer->last_name ?? '') }}">
              </div>
            </div>

            <div id="modal-company-name-field" class="col-12 mb-3">
              <label class="form-label" for="modal_company_name">Company Name</label>
              <input type="text" class="form-control" id="modal_company_name" name="company_name" value="{{ old('company_name', $customer->company_name ?? '') }}">
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label" for="modal_email">Email</label>
              <input type="email" class="form-control" id="modal_email" name="email" value="{{ old('email', $customer->email ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_mobile_phone">Mobile Phone</label>
              <input
                type="text"
                class="form-control"
                id="modal_mobile_phone"
                name="mobile_phone"
                value="{{ old('mobile_phone', $customer->formatted_mobile_phone ?? '') }}"
                maxlength="14"
                inputmode="numeric"
                oninput="formatPhoneNumber(this)"
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_home_phone">Home Phone</label>
              <input
                type="text"
                class="form-control"
                id="modal_home_phone"
                name="home_phone"
                value="{{ old('home_phone', $customer->formatted_home_phone ?? '') }}"
                maxlength="14"
                inputmode="numeric"
                oninput="formatPhoneNumber(this)"
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_address_1">Address 1</label>
              <input type="text" class="form-control" id="modal_address_1" name="address_1" value="{{ old('address_1', $customer->address_1 ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_address_2">Address 2</label>
              <input type="text" class="form-control" id="modal_address_2" name="address_2" value="{{ old('address_2', $customer->address_2 ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="modal_city">City</label>
              <input type="text" class="form-control" id="modal_city" name="city" value="{{ old('city', $customer->city ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="modal_state">State</label>
              <select class="form-select" id="modal_state" name="state">
                <option value="">Select State</option>
                @foreach ($states as $abbr => $stateName)
                  <option value="{{ $abbr }}" {{ old('state', $customer->state ?? '') == $abbr ? 'selected' : '' }}>
                    {{ $stateName }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 mb-3">
              <label class="form-label" for="modal_postal_code">Postal Code</label>
              <input type="text" class="form-control" id="modal_postal_code" name="postal_code" value="{{ old('postal_code', $customer->postal_code ?? '') }}">
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

@push('page-script')
<script>
  function formatPhoneNumber(input) {
    let digits = input.value.replace(/\D/g, '').substring(0, 10);
    let formatted = digits;

    if (digits.length > 6) {
      formatted = `(${digits.substring(0, 3)}) ${digits.substring(3, 6)}-${digits.substring(6, 10)}`;
    } else if (digits.length > 3) {
      formatted = `(${digits.substring(0, 3)}) ${digits.substring(3, 6)}`;
    } else if (digits.length > 0) {
      formatted = `(${digits.substring(0, 3)}`;
    }

    input.value = formatted;
  }

  function toggleModalCustomerFields() {
    const typeSelect = document.getElementById('modal_customer_type_id');
    const selectedOption = typeSelect.options[typeSelect.selectedIndex];
    const selectedCode = selectedOption ? selectedOption.dataset.code : '';

    const consumerFields = document.getElementById('modal-consumer-name-fields');
    const companyField = document.getElementById('modal-company-name-field');

    const firstName = document.getElementById('modal_first_name');
    const lastName = document.getElementById('modal_last_name');
    const companyName = document.getElementById('modal_company_name');

    if (selectedCode === 'consumer') {
      consumerFields.style.display = 'flex';
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
      consumerFields.style.display = 'none';
      companyField.style.display = 'block';

      firstName.disabled = true;
      lastName.disabled = true;
      companyName.disabled = false;

      firstName.required = false;
      lastName.required = false;
      companyName.required = true;
    } else {
      consumerFields.style.display = 'flex';
      companyField.style.display = 'block';

      firstName.disabled = false;
      lastName.disabled = false;
      companyName.disabled = false;

      firstName.required = false;
      lastName.required = false;
      companyName.required = false;
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('modal_customer_type_id');

    if (typeSelect) {
      typeSelect.addEventListener('change', toggleModalCustomerFields);
      toggleModalCustomerFields();
    }
  });
</script>
@endpush