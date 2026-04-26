@extends('layouts.contentNavbarLayout')

@section('title', isset($customer) ? 'Edit Profile' : 'Create Profile')

@section('content')
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">{{ isset($customer) ? 'Edit Profile' : 'Create Profile' }}</h5>
    </div>

    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST"
        action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}">
        @csrf

        @if (isset($customer))
          @method('PUT')
        @endif

        <div class="row">
          <div class="col-md-12 mb-3">
            <label class="form-label" for="customer_type_id">Customer Type</label>
            <select class="form-select" id="customer_type_id" name="customer_type_id" required>
              <option value="">Select Customer Type</option>
              @foreach ($customerTypes as $customerType)
                <option value="{{ $customerType->id }}" data-code="{{ $customerType->code }}"
                  {{ old('customer_type_id', $customer->customer_type_id ?? '') == $customerType->id ? 'selected' : '' }}>
                  {{ $customerType->name }}
                </option>
              @endforeach
            </select>
            @error('customer_type_id')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div id="consumer-name-fields" class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label" for="first_name">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name"
                value="{{ old('first_name', $customer->first_name ?? '') }}">
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label" for="last_name">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name"
                value="{{ old('last_name', $customer->last_name ?? '') }}">
            </div>
          </div>

          <div id="company-name-field" class="col-12 mb-3">
            <label class="form-label" for="company_name">Company Name</label>
            <input type="text" class="form-control" id="company_name" name="company_name"
              value="{{ old('company_name', $customer->company_name ?? '') }}">
          </div>

          <div class="col-md-12 mb-3">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email"
              value="{{ old('email', $customer->email ?? '') }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label" for="mobile_phone">Mobile Phone</label>
            <input type="text" class="form-control" id="mobile_phone" name="mobile_phone"
              value="{{ old('mobile_phone', $customer->formatted_mobile_phone ?? '') }}" maxlength="14"
              inputmode="numeric" oninput="formatPhoneNumber(this)">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label" for="address_1">Address 1</label>
            <input type="text" class="form-control" id="address_1" name="address_1"
              value="{{ old('address_1', $customer->address_1 ?? '') }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label" for="address_2">Address 2</label>
            <input type="text" class="form-control" id="address_2" name="address_2"
              value="{{ old('address_2', $customer->address_2 ?? '') }}">
          </div>

          <div class="col-md-6 mb-3">
            <label class="form-label" for="city">City</label>
            <input type="text" class="form-control" id="city" name="city"
              value="{{ old('city', $customer->city ?? '') }}">
          </div>

          <div class="col-md-3 mb-3">
            <label class="form-label" for="state">State</label>
            <select class="form-select" id="state" name="state">
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
            <label class="form-label" for="postal_code">Postal Code</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code"
              value="{{ old('postal_code', $customer->postal_code ?? '') }}">
          </div>

          <div class="col-12 mb-3">
            <label class="form-label" for="notes">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="4">{{ old('notes', $customer->notes ?? '') }}</textarea>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">
            {{ isset($customer) ? 'Update Profile' : 'Save Profile' }}
          </button>

          <a href="{{ isset($customer) ? route('customers.show', $customer) : route('customers.index') }}"
            class="btn btn-outline-secondary">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection

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

    function toggleCustomerFields() {
      const typeSelect = document.getElementById('customer_type_id');
      const selectedOption = typeSelect.options[typeSelect.selectedIndex];
      const selectedCode = selectedOption ? selectedOption.dataset.code : '';

      const consumerFields = document.getElementById('consumer-name-fields');
      const companyField = document.getElementById('company-name-field');

      const firstName = document.getElementById('first_name');
      const lastName = document.getElementById('last_name');
      const companyName = document.getElementById('company_name');

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

    document.addEventListener('DOMContentLoaded', function() {
      const typeSelect = document.getElementById('customer_type_id');

      if (typeSelect) {
        typeSelect.addEventListener('change', toggleCustomerFields);
        toggleCustomerFields();
      }
    });
  </script>
@endpush
