@extends('layouts.contentNavbarLayout')

@section('title', 'Service Calls')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Service Calls</h5>
    </div>

    <a href="{{ route('service-calls.create') }}" class="btn btn-primary mb-3">
      New Service Call
    </a>
    <div class="card-body">
      @if ($serviceCalls->isEmpty())
        <p class="mb-0">No service calls found.</p>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>Phone</th>
                <th>City</th>
                <th>Scheduled For</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($serviceCalls as $serviceCall)
                <tr>
                  <td>{{ $serviceCall->id }}</td>
                  <td>{{ $serviceCall->customer_name }}</td>
                  <td>{{ $serviceCall->vehicle_label }}</td>
                  <td>{{ $serviceCall->serviceType?->name ?? '—' }}</td>
                  <td>{{ $serviceCall->status?->name ?? '—' }}</td>
                  <td>{{ $serviceCall->customer_mobile_phone ?? '—' }}</td>
                  <td>{{ $serviceCall->city ?? '—' }}</td>
                  <td>{{ $serviceCall->scheduled_for ?? '—' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
@endsection