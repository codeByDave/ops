@extends('layouts.contentNavbarLayout')

@section('title', 'Customers')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Customers</h5>
      <a href="{{ route('customers.create') }}" class="btn btn-primary">
        New Customer
      </a>
    </div>

    <div class="card-body">
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($customers->isEmpty())
        <p class="mb-0">No customers found.</p>
      @else
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Name</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Balance</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($customers as $customer)
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

                <tr>
                  <td>{{ $displayName ?: '—' }}</td>
                  <td>{{ $fullAddress ?: '—' }}</td>
                  <td>{{ $customer->display_phone ?: '—' }}</td>
                  <td>{{ $customer->customerType->name ?? '—' }}</td>
                  <td>—</td>
                  <td>
                    <div class="d-flex gap-2">
                      {{-- VIEW BUTTON --}}
                      <a href="{{ route('customers.show', $customer) }}"
                        class="btn btn-sm btn-icon btn-outline-primary"
                        title="View Profile">
                        <i class="bx bx-show"></i>
                      </a>
                      {{-- DELETE BUTTON --}}
                      <form method="POST"
                            action="{{ route('customers.destroy', $customer) }}"
                            onsubmit="return confirm('Are you sure you want to delete this profile?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-icon btn-outline-danger"
                                title="Delete Profile">
                          <i class="bx bx-trash"></i>
                        </button>
                      </form>
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
@endsection