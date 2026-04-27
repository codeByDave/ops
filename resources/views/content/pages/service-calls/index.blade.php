@extends('layouts.contentNavbarLayout')

@section('title', 'Dispatch Board')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Dispatch Board</h5>

      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createServiceCallModal">
        New Service Call
      </button>
    </div>

    <div class="card-body">
      @if ($errors->has('assignment'))
        <div class="alert alert-danger">
          {{ $errors->first('assignment') }}
        </div>
      @endif

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($serviceCalls->isEmpty())
        <p class="mb-0">No service calls found.</p>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle">
            <thead>
              <tr>
                <th>Service Type</th>
                <th>Customer</th>
                <th>Address</th>
                <th>Driver</th>
                <th>Status</th>
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
                @endphp

                <tr>
                  <td>{{ $serviceCall->serviceType?->name ?? '—' }}</td>
                  <td>{{ $serviceCall->customer_name ?? '—' }}</td>
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
                          <i class="bx bx-show me-1"></i> View / Edit
                        </button>

                        @if ($nextStatusCode)
                          <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_code" value="{{ $nextStatusCode }}">

                            <button type="submit" class="dropdown-item">
                              <i class="icon-base bx bx-chevron-right-circle me-1"></i>
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
                              <i class="icon-base bx bx-x-circle me-1"></i>
                              Cancelled
                            </button>
                          </form>

                          @if ($currentStatusCode === 'on_scene')
                            <form method="POST" action="{{ route('service-calls.update-status', $serviceCall) }}">
                              @csrf
                              @method('PATCH')
                              <input type="hidden" name="status_code" value="goa">

                              <button type="submit" class="dropdown-item text-warning">
                                <i class="icon-base bx bx-error-circle me-1"></i>
                                GOA
                              </button>
                            </form>
                          @endif
                        @endif
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

  {{-- Create Service Call Modal --}}
  @include('content.shared.service-calls.create-modal', [
      'formAction' => route('service-calls.store'),
      'customers' => $customers,
      'selectedCustomer' => null,
      'lockCustomer' => false,
  ])

  {{-- Edit Service Call Modal --}}
  @include('content.shared.service-calls.edit-modal', [
      'vehicles' => $vehicles,
  ])
@endsection

@push('page-script')
  @include('content.shared.service-calls.edit-modal-script')
  @include('content.shared.service-calls.create-modal-script')

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      setupEditServiceCallModal();
      setupTimelineEditors();
      setupEditServiceCallFormValidation();
      reopenEditServiceCallModalAfterValidationError();
      setupCreateServiceCallModal();
    });
  </script>
@endpush
