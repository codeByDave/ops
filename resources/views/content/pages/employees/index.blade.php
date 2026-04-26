@extends('layouts.contentNavbarLayout')

@section('title', 'Employees')

@section('content')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Employees</h4>

    <button
      type="button"
      class="btn btn-primary"
      data-bs-toggle="modal"
      data-bs-target="#addUserModal"
    >
      Add Employee
    </button>
  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th class="text-center">Status</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse($users as $user)
            <tr>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->formatted_phone ?? '—' }}</td>
              <td>{{ ucfirst($user->role) }}</td>

              <td class="text-center">
                @if($user->is_active)
                  <span class="badge bg-label-success">Active</span>
                @else
                  <span class="badge bg-label-secondary">Inactive</span>
                @endif
              </td>

              <td class="text-center">
                <button
                  type="button"
                  class="btn btn-sm btn-primary js-edit-user"
                  data-bs-toggle="modal"
                  data-bs-target="#editUserModal"
                  data-update-url="{{ route('employees.update', $user) }}"
                  data-first-name="{{ $user->first_name }}"
                  data-last-name="{{ $user->last_name }}"
                  data-email="{{ $user->email }}"
                  data-phone="{{ $user->phone }}"
                  data-role="{{ $user->role }}"
                  data-is-active="{{ $user->is_active ? '1' : '0' }}"
                >
                  Edit
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">
                No employees found.
              </td>
            </tr>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
</div>

@endsection

{{-- ADD EMPLOYEE MODAL --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">

      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title">Add Employee</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        @if ($errors->createEmployee->any())
          <div class="alert alert-danger">
            <strong>Could not create employee.</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->createEmployee->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('employees.store') }}">
          @csrf

          <div class="row">

            <div class="col-md-6 mb-3">
              <label class="form-label">First Name</label>
              <input
                type="text"
                class="form-control"
                name="first_name"
                value="{{ old('first_name') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Last Name</label>
              <input
                type="text"
                class="form-control"
                name="last_name"
                value="{{ old('last_name') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                name="email"
                value="{{ old('email') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input
                type="text"
                class="form-control"
                name="phone"
                id="phone"
                maxlength="14"
                value="{{ old('phone') }}"
                oninput="formatPhoneNumber(this)"
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" required>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="office" {{ old('role') == 'office' ? 'selected' : '' }}>Office</option>
                <option value="technician" {{ old('role', 'technician') == 'technician' ? 'selected' : '' }}>Technician</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Password</label>

              <div class="input-group">
                <input
                  type="password"
                  class="form-control"
                  name="password"
                  id="password"
                  required
                >

                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  onclick="togglePassword('password','passwordIcon')"
                >
                  <i class="bx bx-show" id="passwordIcon"></i>
                </button>

                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  onclick="generatePassword('password')"
                >
                  Generate
                </button>
              </div>
            </div>

            <div class="col-12 mb-3">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="must_change_password"
                  id="must_change_password"
                  value="1"
                  checked
                >
                <label class="form-check-label" for="must_change_password">
                  Require password change on next sign-in
                </label>
              </div>
            </div>

          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Save Employee</button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

{{-- EDIT EMPLOYEE MODAL --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg mt-10">
    <div class="modal-content">

      <div class="modal-header border-0 pb-0">
        <h4 class="modal-title">Edit Employee</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        @if ($errors->updateEmployee->any())
          <div class="alert alert-danger">
            <strong>Could not update employee.</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->updateEmployee->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" id="editUserForm" action="{{ old('_employee_update_url') }}">
          @csrf
          @method('PUT')

          <input
            type="hidden"
            name="_employee_update_url"
            id="edit_employee_update_url"
            value="{{ old('_employee_update_url') }}"
          >

          <div class="row">

            <div class="col-md-6 mb-3">
              <label class="form-label">First Name</label>
              <input
                type="text"
                class="form-control"
                name="first_name"
                id="edit_first_name"
                value="{{ old('first_name') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Last Name</label>
              <input
                type="text"
                class="form-control"
                name="last_name"
                id="edit_last_name"
                value="{{ old('last_name') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input
                type="email"
                class="form-control"
                name="email"
                id="edit_email"
                value="{{ old('email') }}"
                required
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input
                type="text"
                class="form-control"
                name="phone"
                id="edit_phone"
                maxlength="14"
                value="{{ old('phone') }}"
                oninput="formatPhoneNumber(this)"
              >
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">Role</label>
              <select class="form-select" name="role" id="edit_role" required>
                <option value="admin">Admin</option>
                <option value="office">Office</option>
                <option value="technician">Technician</option>
              </select>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label d-block">Status</label>

              <div class="form-check form-switch mt-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="is_active"
                  id="edit_is_active"
                  value="1"
                  {{ !$errors->updateEmployee->any() || old('is_active') ? 'checked' : '' }}
                >
                <label class="form-check-label" for="edit_is_active">
                  Active
                </label>
              </div>
            </div>

            <div class="col-12 mb-3">
              <label class="form-label">
                New Password
                <span class="text-muted">(leave blank to keep current)</span>
              </label>

              <div class="input-group">
                <input
                  type="password"
                  class="form-control"
                  name="password"
                  id="edit_password"
                >

                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  onclick="togglePassword('edit_password','editPasswordIcon')"
                >
                  <i class="bx bx-show" id="editPasswordIcon"></i>
                </button>

                <button
                  type="button"
                  class="btn btn-outline-secondary"
                  onclick="generatePassword('edit_password')"
                >
                  Generate
                </button>
              </div>
            </div>

            <div class="col-12 mb-3">
              <div class="form-check">
                <input
                  class="form-check-input"
                  type="checkbox"
                  name="must_change_password"
                  id="edit_must_change_password"
                  value="1"
                >
                <label class="form-check-label" for="edit_must_change_password">
                  Require password change on next sign-in
                </label>
              </div>
            </div>

          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary me-2">Update Employee</button>
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
    formatted = `(${digits.substring(0,3)}) ${digits.substring(3,6)}-${digits.substring(6,10)}`;
  } else if (digits.length > 3) {
    formatted = `(${digits.substring(0,3)}) ${digits.substring(3,6)}`;
  } else if (digits.length > 0) {
    formatted = `(${digits.substring(0,3)}`;
  }

  input.value = formatted;
}

function togglePassword(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon = document.getElementById(iconId);

  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.remove('bx-show');
    icon.classList.add('bx-hide');
  } else {
    input.type = 'password';
    icon.classList.remove('bx-hide');
    icon.classList.add('bx-show');
  }
}

function generatePassword(inputId) {
  const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
  const lower = 'abcdefghijklmnopqrstuvwxyz';
  const nums = '0123456789';
  const spec = '!@#$%^&*';
  const all = upper + lower + nums + spec;

  let pass = '';
  pass += upper[Math.floor(Math.random() * upper.length)];
  pass += lower[Math.floor(Math.random() * lower.length)];
  pass += nums[Math.floor(Math.random() * nums.length)];
  pass += spec[Math.floor(Math.random() * spec.length)];

  while (pass.length < 10) {
    pass += all[Math.floor(Math.random() * all.length)];
  }

  pass = pass.split('').sort(() => Math.random() - 0.5).join('');

  document.getElementById(inputId).value = pass;

  if (inputId === 'edit_password') {
    document.getElementById('edit_must_change_password').checked = true;
  }
}

document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.js-edit-user').forEach(function (button) {
    button.addEventListener('click', function () {

      document.getElementById('editUserForm').action = button.dataset.updateUrl;
      document.getElementById('edit_employee_update_url').value = button.dataset.updateUrl;

      document.getElementById('edit_first_name').value = button.dataset.firstName;
      document.getElementById('edit_last_name').value = button.dataset.lastName;
      document.getElementById('edit_email').value = button.dataset.email;
      document.getElementById('edit_role').value = button.dataset.role;

      const phone = document.getElementById('edit_phone');
      phone.value = button.dataset.phone || '';
      formatPhoneNumber(phone);

      document.getElementById('edit_is_active').checked = button.dataset.isActive === '1';

      document.getElementById('edit_password').value = '';
      document.getElementById('edit_password').type = 'password';
      document.getElementById('edit_must_change_password').checked = false;

      document.getElementById('editPasswordIcon').classList.remove('bx-hide');
      document.getElementById('editPasswordIcon').classList.add('bx-show');
    });
  });

  document.getElementById('edit_password').addEventListener('input', function () {
    document.getElementById('edit_must_change_password').checked =
      this.value.trim() !== '';
  });

  @if ($errors->createEmployee->any())
    new bootstrap.Modal(document.getElementById('addUserModal')).show();
  @endif

  @if ($errors->updateEmployee->any())
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
  @endif

});
</script>
@endpush