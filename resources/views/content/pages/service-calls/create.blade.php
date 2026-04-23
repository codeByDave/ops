@extends('layouts.contentNavbarLayout')

@section('title', 'Create Service Call')

@section('content')
<div class="card">
  <div class="card-header">
    <h5>Create Service Call</h5>
  </div>

  <div class="card-body">
    <form method="POST" action="{{ route('service-calls.store') }}">
      @csrf

      <!-- Customer -->
      <div class="mb-3">
        <label class="form-label">Customer</label>
        <select name="customer_id" class="form-control" required>
          <option value="">Select Customer</option>
          @foreach($customers as $customer)
            <option value="{{ $customer->id }}">
              {{ $customer->first_name }} {{ $customer->last_name }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Vehicle -->
      <div class="mb-3">
        <label class="form-label">Vehicle</label>
        <select name="vehicle_id" class="form-control" required>
          <option value="">Select Vehicle</option>
          @foreach(\App\Models\Vehicle::all() as $vehicle)
            <option value="{{ $vehicle->id }}">
              {{ $vehicle->year }} {{ $vehicle->make }} {{ $vehicle->model }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Service Type -->
      <div class="mb-3">
        <label class="form-label">Service Type</label>
        <select name="service_type_id" class="form-control">
          <option value="">Select</option>
          @foreach($serviceTypes as $type)
            <option value="{{ $type->id }}">{{ $type->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Status -->
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status_id" class="form-control">
          <option value="">Select</option>
          @foreach($statuses as $status)
            <option value="{{ $status->id }}">{{ $status->name }}</option>
          @endforeach
        </select>
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="customer_mobile_phone" class="form-control">
      </div>

      <!-- Address -->
      <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address_1" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">City</label>
        <input type="text" name="city" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">State</label>
        <input type="text" name="state" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">
        Create Service Call
      </button>

    </form>
  </div>
</div>
@endsection